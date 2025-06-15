<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Transactions;
use App\Services\KHQRService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class CheckPendingPayments extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'payments:check-pending 
                            {--limit=50 : Maximum number of transactions to check}
                            {--age=5 : Minimum age in minutes before checking}
                            {--dry-run : Show what would be updated without making changes}';

    /**
     * The console command description.
     */
    protected $description = 'Check pending payment transactions and update their status from Bakong API';

    private KHQRService $khqrService;

    public function __construct(KHQRService $khqrService)
    {
        parent::__construct();
        $this->khqrService = $khqrService;
    }

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $limit = (int) $this->option('limit');
        $ageMinutes = (int) $this->option('age');
        $dryRun = $this->option('dry-run');

        $this->info("Starting payment status check...");
        $this->info("Limit: {$limit}, Min Age: {$ageMinutes} minutes, Dry Run: " . ($dryRun ? 'Yes' : 'No'));

        // Get pending transactions older than specified age
        $cutoffTime = Carbon::now()->subMinutes($ageMinutes);
        
        $pendingTransactions = Transactions::where('status', 'pending')
            ->where('created_at', '<=', $cutoffTime)
            ->where('expires_at', '>', Carbon::now()) // Not expired yet
            ->limit($limit)
            ->get();

        if ($pendingTransactions->isEmpty()) {
            $this->info('No pending transactions found to check.');
            return self::SUCCESS;
        }

        $this->info("Found {$pendingTransactions->count()} pending transactions to check.");

        $bar = $this->output->createProgressBar($pendingTransactions->count());
        $bar->start();

        $updated = 0;
        $errors = 0;
        $expired = 0;

        foreach ($pendingTransactions as $transaction) {
            try {
                // Check if transaction has expired
                if ($transaction->isExpired()) {
                    if (!$dryRun) {
                        $transaction->update(['status' => 'expired']);
                    }
                    $this->line("\n[EXPIRED] Transaction {$transaction->transaction_id}");
                    $expired++;
                    $bar->advance();
                    continue;
                }

                // Check with Bakong API using enhanced multi-method approach
                $result = $this->checkTransactionWithMultipleMethods($transaction);

                if ($result['success'] && isset($result['data']) && !empty($result['data'])) {
                    $newStatus = $this->determineStatusFromApiResponse($result['data']);
                    
                    if ($newStatus !== 'pending') {
                        if (!$dryRun) {
                            $transaction->update([
                                'status' => $newStatus,
                                'response_data' => $result['data'],
                                'updated_at' => now()
                            ]);
                        }
                        
                        $this->line("\n[UPDATED] Transaction {$transaction->transaction_id}: pending -> {$newStatus} (Method: {$result['method_used']})");
                        $updated++;
                    }
                } else {
                    $this->line("\n[ERROR] Failed to check transaction {$transaction->transaction_id}: " . ($result['message'] ?? 'Unknown error') . " (Methods tried: {$result['method_used']})");
                    $errors++;
                }

            } catch (\Exception $e) {
                $this->error("\n[EXCEPTION] Error checking transaction {$transaction->transaction_id}: " . $e->getMessage());
                Log::error("Error in payment status check", [
                    'transaction_id' => $transaction->transaction_id,
                    'error' => $e->getMessage()
                ]);
                $errors++;
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);

        // Summary
        $this->info("Payment status check completed!");
        $this->table(
            ['Status', 'Count'],
            [
                ['Updated', $updated],
                ['Expired', $expired],
                ['Errors', $errors],
                ['Total Checked', $pendingTransactions->count()]
            ]
        );

        if ($dryRun) {
            $this->warn('This was a dry run - no changes were made to the database.');
        }

        return self::SUCCESS;
    }

    /**
     * Determine transaction status from Bakong API response
     */
    private function determineStatusFromApiResponse($apiData): string
    {
        if (empty($apiData)) {
            return 'pending';
        }

        // Check various status fields
        if (isset($apiData['status'])) {
            $apiStatus = strtolower($apiData['status']);
            if (in_array($apiStatus, ['completed', 'success', 'paid', 'confirmed'])) {
                return 'success';
            }
            if (in_array($apiStatus, ['failed', 'rejected', 'cancelled'])) {
                return 'failed';
            }
        }

        if (isset($apiData['transactionStatus'])) {
            $transactionStatus = strtolower($apiData['transactionStatus']);
            if (in_array($transactionStatus, ['success', 'completed'])) {
                return 'success';
            }
            if (in_array($transactionStatus, ['failed', 'error'])) {
                return 'failed';
            }
        }

        // If we have transaction data, assume success
        if (isset($apiData['amount']) || isset($apiData['transactionId']) || isset($apiData['paymentReference'])) {
            return 'success';
        }

        return 'pending';
    }

    /**
     * Check transaction status using multiple methods as recommended by Bakong documentation
     * Priority: Short Hash -> MD5 -> Full Hash
     */
    private function checkTransactionWithMultipleMethods(Transactions $transaction): array
    {
        // Method 1: Short Hash with amount verification (RECOMMENDED by documentation)
        if ($transaction->qr_short_hash && $transaction->amount) {
            $result = $this->khqrService->checkTransactionByShortHash(
                $transaction->qr_short_hash, 
                (float)$transaction->amount, 
                $transaction->currency ?? 'KHR'
            );

            if ($result['success'] && isset($result['data']) && !empty($result['data'])) {
                $result['method_used'] = 'short_hash';
                return $result;
            }
        }

        // Method 2: MD5 Hash (traditional method)
        if ($transaction->qr_md5) {
            $result = $this->khqrService->checkTransactionByMD5($transaction->qr_md5);

            if ($result['success'] && isset($result['data']) && !empty($result['data'])) {
                $result['method_used'] = 'md5';
                return $result;
            }
        }

        // Method 3: Full Hash (SHA256)
        if ($transaction->qr_full_hash) {
            $result = $this->khqrService->checkTransactionByFullHash($transaction->qr_full_hash);

            if ($result['success'] && isset($result['data']) && !empty($result['data'])) {
                $result['method_used'] = 'full_hash';
                return $result;
            }
        }

        // All methods failed
        return [
            'success' => false,
            'message' => 'Transaction not found using any checking method',
            'method_used' => 'all_failed',
            'data' => null
        ];
    }
}