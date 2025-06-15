<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Transactions;
use App\Services\KHQRService;
use Illuminate\Support\Facades\Log;

class UpdateTransactionStatus implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;
    public $timeout = 60;

    private string $transactionId;
    private int $attempt;

    /**
     * Create a new job instance.
     */
    public function __construct(string $transactionId, int $attempt = 0)
    {
        $this->transactionId = $transactionId;
        $this->attempt = $attempt;
    }

    /**
     * Execute the job.
     */
    public function handle(KHQRService $khqrService): void
    {
        try {
            $transaction = Transactions::where('transaction_id', $this->transactionId)->first();
            
            if (!$transaction) {
                Log::warning('Transaction not found for automatic status update', [
                    'transaction_id' => $this->transactionId,
                    'attempt' => $this->attempt
                ]);
                return;
            }

            // Skip if already in final state
            if (in_array($transaction->status, ['success', 'failed', 'expired'])) {
                Log::info('Transaction already in final state, skipping check', [
                    'transaction_id' => $this->transactionId,
                    'status' => $transaction->status,
                    'attempt' => $this->attempt
                ]);
                return;
            }

            // Check if transaction has expired
            if ($transaction->expires_at && $transaction->expires_at->isPast()) {
                $transaction->update(['status' => 'expired']);
                Log::info('Transaction marked as expired', [
                    'transaction_id' => $this->transactionId,
                    'expired_at' => $transaction->expires_at
                ]);
                return;
            }

            Log::info('Checking transaction status automatically', [
                'transaction_id' => $this->transactionId,
                'attempt' => $this->attempt
            ]);

            // Use enhanced multi-method checking as recommended by Bakong documentation
            $result = $this->checkTransactionWithMultipleMethods($transaction, $khqrService);
            
            if ($result['success'] && isset($result['data']) && !empty($result['data'])) {
                // Determine new status from API response
                $newStatus = $this->determineStatusFromApiResponse($result['data']);
                
                if ($newStatus !== 'pending' && $newStatus !== $transaction->status) {
                    $oldStatus = $transaction->status;
                    
                    // Update transaction
                    $transaction->update([
                        'status' => $newStatus,
                        'response_data' => $result['data'],
                        'updated_at' => now()
                    ]);

                    Log::info('Transaction status updated automatically', [
                        'transaction_id' => $this->transactionId,
                        'old_status' => $oldStatus,
                        'new_status' => $newStatus,
                        'attempt' => $this->attempt,
                        'method_used' => $result['method_used'] ?? 'unknown',
                        'api_response' => $result['data']
                    ]);

                    // Handle successful payment
                    if ($newStatus === 'success') {
                        $this->handlePaymentSuccess($transaction);
                    }
                } else {
                    Log::info('No status change needed for automatic check', [
                        'transaction_id' => $this->transactionId,
                        'current_status' => $transaction->status,
                        'attempt' => $this->attempt,
                        'method_used' => $result['method_used'] ?? 'unknown'
                    ]);
                }
            } else {
                Log::info('Payment still pending - automatic check', [
                    'transaction_id' => $this->transactionId,
                    'attempt' => $this->attempt,
                    'message' => $result['message'] ?? 'No payment data found',
                    'method_used' => $result['method_used'] ?? 'all_failed'
                ]);
            }

        } catch (\Exception $e) {
            Log::error('Error in automatic transaction status update', [
                'transaction_id' => $this->transactionId,
                'attempt' => $this->attempt,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            // Don't retry on certain errors to avoid spam
            if (!str_contains($e->getMessage(), 'Connection refused') && 
                !str_contains($e->getMessage(), 'timeout')) {
                $this->fail($e);
            }
        }
    }

    /**
     * Check transaction using multiple methods as recommended by Bakong documentation
     * Priority: Short Hash -> MD5 -> Full Hash
     */
    private function checkTransactionWithMultipleMethods(Transactions $transaction, KHQRService $khqrService): array
    {
        // Method 1: Short Hash with amount verification (RECOMMENDED by documentation)
        if ($transaction->qr_short_hash && $transaction->amount) {
            Log::info('Background job: Checking with short hash method', [
                'transaction_id' => $transaction->transaction_id,
                'short_hash' => $transaction->qr_short_hash,
                'amount' => $transaction->amount,
                'attempt' => $this->attempt
            ]);

            $result = $khqrService->checkTransactionByShortHash(
                $transaction->qr_short_hash, 
                (float)$transaction->amount, 
                $transaction->currency ?? 'KHR'
            );

            if ($result['success'] && isset($result['data']) && !empty($result['data'])) {
                $result['method_used'] = 'short_hash';
                return $result;
            }

            Log::info('Background job: Short hash method returned no data, trying MD5', [
                'transaction_id' => $transaction->transaction_id,
                'attempt' => $this->attempt,
                'short_hash_result' => $result['message'] ?? 'No message'
            ]);
        }

        // Method 2: MD5 Hash (traditional method)
        if ($transaction->qr_md5) {
            Log::info('Background job: Checking with MD5 method', [
                'transaction_id' => $transaction->transaction_id,
                'md5' => $transaction->qr_md5,
                'attempt' => $this->attempt
            ]);

            $result = $khqrService->checkTransactionByMD5($transaction->qr_md5);

            if ($result['success'] && isset($result['data']) && !empty($result['data'])) {
                $result['method_used'] = 'md5';
                return $result;
            }

            Log::info('Background job: MD5 method returned no data, trying full hash', [
                'transaction_id' => $transaction->transaction_id,
                'attempt' => $this->attempt,
                'md5_result' => $result['message'] ?? 'No message'
            ]);
        }

        // Method 3: Full Hash (SHA256)
        if ($transaction->qr_full_hash) {
            Log::info('Background job: Checking with full hash method', [
                'transaction_id' => $transaction->transaction_id,
                'full_hash' => $transaction->qr_full_hash,
                'attempt' => $this->attempt
            ]);

            $result = $khqrService->checkTransactionByFullHash($transaction->qr_full_hash);

            if ($result['success'] && isset($result['data']) && !empty($result['data'])) {
                $result['method_used'] = 'full_hash';
                return $result;
            }

            Log::info('Background job: Full hash method returned no data', [
                'transaction_id' => $transaction->transaction_id,
                'attempt' => $this->attempt,
                'full_hash_result' => $result['message'] ?? 'No message'
            ]);
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

        // Check various status fields that Bakong might use
        if (isset($apiData['status'])) {
            $apiStatus = strtolower($apiData['status']);
            if (in_array($apiStatus, ['completed', 'success', 'paid', 'confirmed'])) {
                return 'success';
            }
            if (in_array($apiStatus, ['failed', 'rejected', 'cancelled', 'error'])) {
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

        // If we have substantial transaction data, assume payment was successful
        if (isset($apiData['amount']) || isset($apiData['transactionId']) || isset($apiData['paymentReference'])) {
            return 'success';
        }

        return 'pending';
    }

    /**
     * Handle successful payment
     */
    private function handlePaymentSuccess(Transactions $transaction): void
    {
        try {
            Log::info('Handling successful payment', [
                'transaction_id' => $transaction->transaction_id,
                'amount' => $transaction->amount,
                'booking_reference' => $transaction->booking_reference
            ]);

            // If booking reference exists and Booking model is available, update booking status
            if ($transaction->booking_reference && class_exists('App\Models\Booking')) {
                try {
                    $booking = \App\Models\Booking::where('reference', $transaction->booking_reference)->first();
                    if ($booking) {
                        $booking->update([
                            'payment_status' => 'paid',
                            'status' => 'confirmed'
                        ]);

                        Log::info('Booking status updated after payment', [
                            'booking_reference' => $transaction->booking_reference,
                            'transaction_id' => $transaction->transaction_id
                        ]);
                    }
                } catch (\Exception $e) {
                    Log::warning('Could not update booking status', [
                        'transaction_id' => $transaction->transaction_id,
                        'booking_reference' => $transaction->booking_reference,
                        'error' => $e->getMessage()
                    ]);
                }
            }

            // You can add additional success handling here:
            // - Send email notifications
            // - Update inventory
            // - Trigger webhooks
            // - etc.

        } catch (\Exception $e) {
            Log::error('Error handling payment success', [
                'transaction_id' => $transaction->transaction_id,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Handle job failure
     */
    public function failed(\Throwable $exception): void
    {
        Log::error('UpdateTransactionStatus job failed permanently', [
            'transaction_id' => $this->transactionId,
            'attempt' => $this->attempt,
            'error' => $exception->getMessage()
        ]);
    }
}
