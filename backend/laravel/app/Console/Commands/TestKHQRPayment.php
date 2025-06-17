<?php

namespace App\Console\Commands;

use App\Models\Transactions;
use App\Services\KHQRService;
use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Carbon\Carbon;

class TestKHQRPayment extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'khqr:test 
                            {--check-token : Check API token status}
                            {--generate= : Generate test payment with specified amount}
                            {--generate-live-test= : Generate live test payment with specified amount}
                            {--list-transactions : List all transactions}
                            {--check-pending : Check status of pending transactions}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test KHQR payment functionality';

    private KHQRService $khqrService;

    public function __construct(KHQRService $khqrService)
    {
        parent::__construct();
        $this->khqrService = $khqrService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🏦 KHQR Payment Testing Tool');
        $this->line('================================');

        if ($this->option('check-token')) {
            return $this->checkToken();
        }

        if ($this->option('generate')) {
            return $this->generateTestPayment($this->option('generate'));
        }

        if ($this->option('generate-live-test')) {
            return $this->generateLiveTestPayment($this->option('generate-live-test'));
        }

        if ($this->option('list-transactions')) {
            return $this->listTransactions();
        }

        if ($this->option('check-pending')) {
            return $this->checkPendingTransactions();
        }

        // Show help if no options provided
        $this->showHelp();
    }

    /**
     * Check API token status
     */
    private function checkToken()
    {
        $this->info('🔑 Checking API Token Status...');
        
        $token = env('BAKONG_API_TOKEN');
        
        if (empty($token)) {
            $this->error('❌ BAKONG_API_TOKEN is not set in .env file');
            $this->line('');
            $this->line('Please add your Bakong API token to .env:');
            $this->line('BAKONG_API_TOKEN=your_token_here');
            return 1;
        }

        $this->info('✅ API Token is configured');
        $this->line("Token: " . substr($token, 0, 10) . '...' . substr($token, -4));
        
        // Test token validity by making a simple API call
        $this->line('');
        $this->info('🔍 Testing token validity...');
        
        try {
            $result = $this->khqrService->generateIndividual(100, 'Test Token Validation');
            
            if ($result['success']) {
                $this->info('✅ Token is valid and working');
                $this->line('✅ API connection successful');
                $this->line('✅ QR generation working');
            } else {
                $this->error('❌ Token test failed: ' . $result['message']);
                return 1;
            }
        } catch (\Exception $e) {
            $this->error('❌ Token validation error: ' . $e->getMessage());
            return 1;
        }

        return 0;
    }

    /**
     * Generate test payment
     */
    private function generateTestPayment($amount)
    {
        $this->info("💰 Generating test payment for {$amount} KHR...");
        
        if (!is_numeric($amount) || $amount <= 0) {
            $this->error('❌ Invalid amount. Please provide a positive number.');
            return 1;
        }

        try {
            $result = $this->khqrService->generateIndividual(
                (float)$amount, 
                'KHQR Test Payment'
            );

            if (!$result['success']) {
                $this->error('❌ Failed to generate payment: ' . $result['message']);
                return 1;
            }

            // Create transaction record
            $transaction = Transactions::create([
                'transaction_id' => 'TEST_' . Str::upper(Str::random(12)),
                'bakong_account_id' => 'sok_chanmakara@aclb',
                'qr_string' => $result['qr_string'],
                'qr_md5' => $result['md5'],
                'qr_full_hash' => $result['full_hash'] ?? null,
                'qr_short_hash' => $result['short_hash'] ?? null,
                'amount' => $amount,
                'currency' => 'KHR',
                'merchant_name' => 'KHQR Test Payment',
                'status' => 'pending',
                'expires_at' => Carbon::now()->addMinutes(5),
                'booking_reference' => 'TEST_' . date('YmdHis'),
            ]);

            $this->info('✅ Test payment generated successfully!');
            $this->line('');
            $this->line('📄 Transaction Details:');
            $this->line("Transaction ID: {$transaction->transaction_id}");
            $this->line("Amount: {$amount} KHR");
            $this->line("MD5 Hash: {$result['md5']}");
            $this->line("Expires: {$transaction->expires_at}");
            $this->line('');
            $this->line('🔗 QR String (first 50 chars):');
            $this->line(substr($result['qr_string'], 0, 50) . '...');

            return 0;
        } catch (\Exception $e) {
            $this->error('❌ Error generating test payment: ' . $e->getMessage());
            return 1;
        }
    }

    /**
     * Generate live test payment
     */
    private function generateLiveTestPayment($amount)
    {
        $this->info("🚀 Generating LIVE test payment for {$amount} KHR...");
        $this->warn('⚠️  This will create a REAL payment QR that can be paid!');
        
        if (!$this->confirm('Do you want to continue?')) {
            $this->line('Test cancelled.');
            return 0;
        }

        if (!is_numeric($amount) || $amount <= 0) {
            $this->error('❌ Invalid amount. Please provide a positive number.');
            return 1;
        }

        try {
            $result = $this->khqrService->generateIndividual(
                (float)$amount, 
                'LIVE KHQR Test Payment'
            );

            if (!$result['success']) {
                $this->error('❌ Failed to generate live payment: ' . $result['message']);
                return 1;
            }

            // Create transaction record
            $transaction = Transactions::create([
                'transaction_id' => 'LIVE_' . Str::upper(Str::random(12)),
                'bakong_account_id' => 'sok_chanmakara@aclb',
                'qr_string' => $result['qr_string'],
                'qr_md5' => $result['md5'],
                'qr_full_hash' => $result['full_hash'] ?? null,
                'qr_short_hash' => $result['short_hash'] ?? null,
                'amount' => $amount,
                'currency' => 'KHR',
                'merchant_name' => 'LIVE KHQR Test Payment',
                'status' => 'pending',
                'expires_at' => Carbon::now()->addMinutes(5),
                'booking_reference' => 'LIVE_TEST_' . date('YmdHis'),
            ]);

            $this->info('✅ LIVE test payment generated successfully!');
            $this->line('');
            $this->line('📄 Transaction Details:');
            $this->line("Transaction ID: {$transaction->transaction_id}");
            $this->line("Amount: {$amount} KHR");
            $this->line("MD5 Hash: {$result['md5']}");
            $this->line("Status: {$transaction->status}");
            $this->line("Expires: {$transaction->expires_at}");
            $this->line('');
            $this->line('🔗 Full QR String:');
            $this->line($result['qr_string']);
            $this->line('');
            $this->info('💡 You can now scan this QR with a Bakong app to make a real payment!');
            $this->line('');
            $this->line('📊 To check payment status later, run:');
            $this->line("php artisan khqr:test --check-pending");

            return 0;
        } catch (\Exception $e) {
            $this->error('❌ Error generating live test payment: ' . $e->getMessage());
            return 1;
        }
    }

    /**
     * List all transactions
     */
    private function listTransactions()
    {
        $this->info('📋 Listing all transactions...');
        
        $transactions = Transactions::orderBy('created_at', 'desc')->take(20)->get();
        
        if ($transactions->isEmpty()) {
            $this->warn('No transactions found.');
            return 0;
        }

        $this->line('');
        $this->line('📊 Recent Transactions (last 20):');
        $this->line('=====================================');

        $headers = ['ID', 'Transaction ID', 'Amount', 'Status', 'Created', 'Expires'];
        $rows = [];

        foreach ($transactions as $transaction) {
            $rows[] = [
                $transaction->id,
                $transaction->transaction_id,
                $transaction->amount . ' ' . $transaction->currency,
                $this->getStatusWithEmoji($transaction->status),
                $transaction->created_at->format('Y-m-d H:i:s'),
                $transaction->expires_at ? $transaction->expires_at->format('Y-m-d H:i:s') : 'N/A'
            ];
        }

        $this->table($headers, $rows);

        // Show status summary
        $statusCounts = $transactions->groupBy('status')->map->count();
        $this->line('');
        $this->line('📈 Status Summary:');
        foreach ($statusCounts as $status => $count) {
            $this->line("  {$this->getStatusWithEmoji($status)}: {$count}");
        }

        return 0;
    }

    /**
     * Check pending transactions
     */
    private function checkPendingTransactions()
    {
        $this->info('🔍 Checking pending transactions...');
        
        $pendingTransactions = Transactions::where('status', 'pending')
            ->where(function($query) {
                $query->whereNull('expires_at')
                      ->orWhere('expires_at', '>', now());
            })
            ->orderBy('created_at', 'desc')
            ->get();

        if ($pendingTransactions->isEmpty()) {
            $this->info('✅ No pending transactions found.');
            return 0;
        }

        $this->line("Found {$pendingTransactions->count()} pending transactions:");
        $this->line('');

        foreach ($pendingTransactions as $transaction) {
            $this->line("🔄 Checking {$transaction->transaction_id}...");
            
            try {
                // Use enhanced multi-method checking like the other components
                $result = $this->checkTransactionWithMultipleMethods($transaction);
                
                if ($result['success'] && isset($result['data']) && !empty($result['data'])) {
                    $newStatus = $this->determineStatusFromApiResponse($result['data']);
                    
                    if ($newStatus !== 'pending') {
                        $transaction->update([
                            'status' => $newStatus,
                            'response_data' => $result['data'],
                            'updated_at' => now()
                        ]);
                        
                        $this->info("  ✅ Payment completed! Updated to {$newStatus} (Method: {$result['method_used']}).");
                    } else {
                        $this->line("  ⏳ Still pending...");
                    }
                } else {
                    $this->line("  ⏳ Still pending (no payment data found)...");
                }
            } catch (\Exception $e) {
                $this->error("  ❌ Error checking: " . $e->getMessage());
            }
        }

        return 0;
    }

    /**
     * Check transaction using multiple methods as recommended by Bakong documentation
     * Priority: Short Hash -> MD5 -> Full Hash (but use best result)
     */
    private function checkTransactionWithMultipleMethods(Transactions $transaction): array
    {
        $results = [];

        // Method 1: Short Hash with amount verification (RECOMMENDED by documentation)
        if ($transaction->qr_short_hash && $transaction->amount) {
            $result = $this->khqrService->checkTransactionByShortHash(
                $transaction->qr_short_hash, 
                (float)$transaction->amount, 
                $transaction->currency ?? 'KHR'
            );

            if ($result['success'] && isset($result['data']) && !empty($result['data'])) {
                $result['method_used'] = 'short_hash';
                $results['short_hash'] = $result;
            }
        }

        // Method 2: MD5 Hash (traditional method)
        if ($transaction->qr_md5) {
            $result = $this->khqrService->checkTransactionByMD5($transaction->qr_md5);

            if ($result['success'] && isset($result['data']) && !empty($result['data'])) {
                $result['method_used'] = 'md5';
                $results['md5'] = $result;
            }
        }

        // Method 3: Full Hash (SHA256)
        if ($transaction->qr_full_hash) {
            $result = $this->khqrService->checkTransactionByFullHash($transaction->qr_full_hash);

            if ($result['success'] && isset($result['data']) && !empty($result['data'])) {
                $result['method_used'] = 'full_hash';
                $results['full_hash'] = $result;
            }
        }

        // Return the best result (prioritize success responses)
        foreach (['short_hash', 'md5', 'full_hash'] as $method) {
            if (isset($results[$method])) {
                $data = $results[$method]['data'];
                // Check if this result indicates success
                if (isset($data['responseCode']) && $data['responseCode'] === 0) {
                    return $results[$method];
                }
            }
        }

        // If no success found, return the first available result
        if (!empty($results)) {
            return array_values($results)[0];
        }

        // All methods failed
        return [
            'success' => false,
            'message' => 'Transaction not found using any checking method',
            'method_used' => 'all_failed',
            'data' => null
        ];
    }

    /**
     * Determine transaction status from API response
     */
    private function determineStatusFromApiResponse($apiData): string
    {
        if (empty($apiData)) {
            return 'pending';
        }

        // Check for Bakong API error responses first
        if (isset($apiData['responseCode'])) {
            $responseCode = (int)$apiData['responseCode'];
            
            // Success codes (transaction found and completed)
            if ($responseCode === 0 || $responseCode === 200) {
                return 'success';
            }
            
            // Error codes that indicate transaction not found (still pending)
            if (in_array($responseCode, [1, 404, 4040])) {
                return 'pending'; // Transaction not found yet, keep checking
            }
            
            // Other error codes that indicate failure
            if ($responseCode > 0) {
                return 'failed';
            }
        }

        // Check for error codes in different format
        if (isset($apiData['errorCode'])) {
            $errorCode = (int)$apiData['errorCode'];
            
            // Success (no error)
            if ($errorCode === 0) {
                return 'success';
            }
            
            // Transaction not found (still pending)
            if (in_array($errorCode, [1, 404, 4040])) {
                return 'pending';
            }
            
            // Other errors
            if ($errorCode > 0) {
                return 'failed';
            }
        }

        // Check various status fields that Bakong might use
        if (isset($apiData['status'])) {
            $apiStatus = strtolower($apiData['status']);
            if (in_array($apiStatus, ['completed', 'success', 'paid', 'confirmed', 'successful'])) {
                return 'success';
            }
            if (in_array($apiStatus, ['failed', 'rejected', 'cancelled', 'error'])) {
                return 'failed';
            }
        }

        if (isset($apiData['transactionStatus'])) {
            $transactionStatus = strtolower($apiData['transactionStatus']);
            if (in_array($transactionStatus, ['success', 'completed', 'successful'])) {
                return 'success';
            }
            if (in_array($transactionStatus, ['failed', 'error'])) {
                return 'failed';
            }
        }

        // Check response message for success indicators
        if (isset($apiData['responseMessage'])) {
            $message = strtolower($apiData['responseMessage']);
            if (strpos($message, 'success') !== false || strpos($message, 'completed') !== false) {
                return 'success';
            }
            if (strpos($message, 'not found') !== false || strpos($message, 'could not be found') !== false) {
                return 'pending'; // Transaction not found yet, keep checking
            }
        }

        // If we have substantial transaction data (like amount, references), assume payment was successful
        if (isset($apiData['amount']) || isset($apiData['transactionId']) || isset($apiData['paymentReference']) || isset($apiData['transactionRef'])) {
            // But only if there's no error code indicating failure
            if (!isset($apiData['responseCode']) || (int)$apiData['responseCode'] === 0) {
                return 'success';
            }
        }

        return 'pending';
    }

    /**
     * Get status with emoji
     */
    private function getStatusWithEmoji($status)
    {
        return match($status) {
            'pending' => '⏳ pending',
            'success' => '✅ success', 
            'failed' => '❌ failed',
            'expired' => '⏰ expired',
            default => "❓ {$status}"
        };
    }

    /**
     * Show help information
     */
    private function showHelp()
    {
        $this->line('📖 Available Commands:');
        $this->line('');
        $this->line('🔑 Token Management:');
        $this->line('  php artisan khqr:test --check-token                Check API token status');
        $this->line('');
        $this->line('💰 Payment Generation:');
        $this->line('  php artisan khqr:test --generate=1000              Generate test payment');
        $this->line('  php artisan khqr:test --generate-live-test=100     Generate LIVE test payment');
        $this->line('');
        $this->line('📊 Transaction Management:');
        $this->line('  php artisan khqr:test --list-transactions          List all transactions');
        $this->line('  php artisan khqr:test --check-pending              Check pending transactions');
        $this->line('');
        $this->line('💡 Examples:');
        $this->line('  php artisan khqr:test --check-token');
        $this->line('  php artisan khqr:test --generate=500');
        $this->line('  php artisan khqr:test --generate-live-test=100');
        $this->line('  php artisan khqr:test --list-transactions');
    }
}