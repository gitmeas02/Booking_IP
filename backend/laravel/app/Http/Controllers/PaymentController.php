<?php
namespace App\Http\Controllers;

use App\Models\Transactions;
use App\Services\KHQRService;
use App\Jobs\UpdateTransactionStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class PaymentController extends Controller
{
    private KHQRService $khqrService;

    public function __construct(KHQRService $khqrService)
    {
        $this->khqrService = $khqrService;
    }

    /**
     * Create a new payment QR code for ecommerce
     */
    public function create(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'booking_reference' => 'nullable|string|max:255',
            'merchant_name' => 'nullable|string|max:255',
        ]);

        $amount = $request->input('amount');
        $bookingReference = $request->input('booking_reference');
        $merchantName = $request->input('merchant_name', 'Hotel Booking Payment');

        // Generate KHQR using the service
        $result = $this->khqrService->generateIndividual($amount, $merchantName);

        if (!$result['success']) {
            // Handle web form submission
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $result['message']
                ], 400);
            } else {
                return redirect('/ecommerce-test')->with('error', $result['message']);
            }
        }

        // Create transaction record
        $transaction = Transactions::create([
            'transaction_id' => 'TXN_' . Str::upper(Str::random(12)),
            'bakong_account_id' => 'sok_chanmakara@aclb', // Could be dynamic based on merchant
            'qr_string' => $result['qr_string'],
            'qr_md5' => $result['md5'],
            'qr_full_hash' => $result['full_hash'],
            'qr_short_hash' => $result['short_hash'],
            'amount' => $amount,
            'currency' => 'KHR',
            'merchant_name' => $merchantName,
            'status' => 'pending',
            'expires_at' => null, // No expiration
            'booking_reference' => $bookingReference,
        ]);

        // Dispatch background jobs for automatic status checking
        $this->scheduleStatusUpdates($transaction->transaction_id);

        $responseData = [
            'success' => true,
            'transaction_id' => $transaction->transaction_id,
            'qr_string' => $result['qr_string'],
            'md5' => $result['md5'],
            'amount' => $amount,
            'expires_at' => $transaction->expires_at?->toISOString(),
            'message' => 'Payment QR generated successfully'
        ];

        // Handle web form submission vs API request
        if ($request->expectsJson()) {
            return response()->json($responseData);
        } else {
            return redirect('/ecommerce-test')->with('payment_created', $responseData);
        }
    }

    /**
     * Check payment status with enhanced multi-method approach
     */
    public function checkStatus($transactionId)
    {
        $transaction = Transactions::where('transaction_id', $transactionId)->first();

        if (!$transaction) {
            return response()->json([
                'success' => false,
                'message' => 'Transaction not found'
            ], 404);
        }

        // Check if expired and update status
        $transaction->checkAndMarkExpired();

        // If already completed, return cached status
        if (in_array($transaction->status, ['success', 'failed', 'expired'])) {
            return response()->json([
                'success' => true,
                'status' => $transaction->status,
                'transaction' => $transaction,
                'message' => 'Transaction status: ' . $transaction->status
            ]);
        }

        // For pending transactions, use enhanced checking with multiple methods
        $result = $this->checkTransactionWithMultipleMethods($transaction);

        if ($result['success'] && isset($result['data']) && !empty($result['data'])) {
            // Determine status from API response
            $newStatus = $this->determineStatusFromApiResponse($result['data']);
            
            if ($newStatus !== 'pending') {
                // Update transaction with API response
                $transaction->update([
                    'status' => $newStatus,
                    'response_data' => $result['data'],
                    'updated_at' => now()
                ]);

                Log::info("Transaction status updated via manual check", [
                    'transaction_id' => $transactionId,
                    'new_status' => $newStatus,
                    'method_used' => $result['method_used'] ?? 'unknown'
                ]);
            }

            return response()->json([
                'success' => true,
                'status' => $newStatus,
                'transaction' => $transaction->fresh(),
                'api_response' => $result['data'],
                'method_used' => $result['method_used'],
                'message' => $newStatus === 'success' ? 'Payment completed successfully' : "Transaction status: {$newStatus}"
            ]);
        }

        return response()->json([
            'success' => true,
            'status' => $transaction->status,
            'transaction' => $transaction,
            'message' => $result['message'] ?? 'Payment still pending'
        ]);
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
            Log::info('Checking with short hash method', [
                'transaction_id' => $transaction->transaction_id,
                'short_hash' => $transaction->qr_short_hash,
                'amount' => $transaction->amount
            ]);

            $result = $this->khqrService->checkTransactionByShortHash(
                $transaction->qr_short_hash, 
                (float)$transaction->amount, 
                $transaction->currency ?? 'KHR'
            );

            if ($result['success'] && isset($result['data']) && !empty($result['data'])) {
                $result['method_used'] = 'short_hash';
                $results['short_hash'] = $result;
            }

            Log::info('Short hash method result', [
                'transaction_id' => $transaction->transaction_id,
                'success' => $result['success'],
                'has_data' => isset($result['data']) && !empty($result['data']),
                'response_code' => $result['data']['responseCode'] ?? 'N/A'
            ]);
        }

        // Method 2: MD5 Hash (traditional method)
        if ($transaction->qr_md5) {
            Log::info('Checking with MD5 method', [
                'transaction_id' => $transaction->transaction_id,
                'md5' => $transaction->qr_md5
            ]);

            $result = $this->khqrService->checkTransactionByMD5($transaction->qr_md5);

            if ($result['success'] && isset($result['data']) && !empty($result['data'])) {
                $result['method_used'] = 'md5';
                $results['md5'] = $result;
            }

            Log::info('MD5 method result', [
                'transaction_id' => $transaction->transaction_id,
                'success' => $result['success'],
                'has_data' => isset($result['data']) && !empty($result['data']),
                'response_code' => $result['data']['responseCode'] ?? 'N/A'
            ]);
        }

        // Method 3: Full Hash (SHA256)
        if ($transaction->qr_full_hash) {
            Log::info('Checking with full hash method', [
                'transaction_id' => $transaction->transaction_id,
                'full_hash' => $transaction->qr_full_hash
            ]);

            $result = $this->khqrService->checkTransactionByFullHash($transaction->qr_full_hash);

            if ($result['success'] && isset($result['data']) && !empty($result['data'])) {
                $result['method_used'] = 'full_hash';
                $results['full_hash'] = $result;
            }

            Log::info('Full hash method result', [
                'transaction_id' => $transaction->transaction_id,
                'success' => $result['success'],
                'has_data' => isset($result['data']) && !empty($result['data']),
                'response_code' => $result['data']['responseCode'] ?? 'N/A'
            ]);
        }

        // Return the best result (prioritize successful responses)
        foreach (['short_hash', 'md5', 'full_hash'] as $method) {
            if (isset($results[$method])) {
                $data = $results[$method]['data'];
                // Check if this result indicates success
                if (isset($data['responseCode']) && $data['responseCode'] === 0) {
                    Log::info('Using successful result from method', [
                        'transaction_id' => $transaction->transaction_id,
                        'method' => $method,
                        'response_code' => $data['responseCode']
                    ]);
                    return $results[$method];
                }
            }
        }

        // If no success found, return the first available result
        if (!empty($results)) {
            $firstResult = array_values($results)[0];
            Log::info('No successful result found, using first available', [
                'transaction_id' => $transaction->transaction_id,
                'method_used' => $firstResult['method_used'],
                'response_code' => $firstResult['data']['responseCode'] ?? 'N/A'
            ]);
            return $firstResult;
        }

        // All methods failed
        Log::info('All methods failed for transaction', [
            'transaction_id' => $transaction->transaction_id
        ]);

        return [
            'success' => false,
            'message' => 'Transaction not found using any checking method',
            'method_used' => 'all_failed',
            'data' => null
        ];
    }

    /**
     * Webhook endpoint for Bakong notifications
     */
    public function webhook(Request $request)
    {
        Log::info('Webhook received', [
            'headers' => $request->headers->all(),
            'body' => $request->all()
        ]);

        // Validate webhook
        $validator = Validator::make($request->all(), [
            'transactionId' => 'sometimes|string',
            'md5' => 'sometimes|string',
            'qr_string' => 'sometimes|string',
            'amount' => 'sometimes|numeric',
            'status' => 'sometimes|string'
        ]);

        if ($validator->fails()) {
            Log::warning('Invalid webhook payload', [
                'errors' => $validator->errors(),
                'payload' => $request->all()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Invalid webhook payload',
                'errors' => $validator->errors()
            ], 400);
        }

        $data = $request->all();
        $transaction = null;

        // Try to find transaction by various identifiers
        if (isset($data['md5'])) {
            $transaction = Transactions::where('qr_md5', $data['md5'])->first();
        }
        
        if (!$transaction && isset($data['qr_string'])) {
            $transaction = Transactions::where('qr_string', $data['qr_string'])->first();
        }

        if (!$transaction && isset($data['transactionId'])) {
            $transaction = Transactions::where('transaction_id', $data['transactionId'])->first();
        }

        if (!$transaction) {
            Log::warning('Transaction not found for webhook', [
                'webhook_data' => $data
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Transaction not found'
            ], 404);
        }

        // Update transaction status based on webhook data
        $newStatus = $this->determineStatusFromApiResponse($data);
        $oldStatus = $transaction->status;

        if ($newStatus !== $oldStatus && !in_array($oldStatus, ['success', 'failed'])) {
            $transaction->update([
                'status' => $newStatus,
                'response_data' => $data,
                'updated_at' => now()
            ]);

            Log::info('Transaction updated via webhook', [
                'transaction_id' => $transaction->transaction_id,
                'old_status' => $oldStatus,
                'new_status' => $newStatus,
                'webhook_data' => $data
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Webhook processed successfully',
            'transaction_id' => $transaction->transaction_id,
            'status' => $transaction->status
        ]);
    }

    /**
     * Get all transactions (for admin/merchant dashboard)
     */
    public function getTransactions(Request $request)
    {
        $query = Transactions::query();

        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // Filter by date range
        if ($request->has('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }

        if ($request->has('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        $transactions = $query->orderBy('created_at', 'desc')->paginate(20);

        return response()->json([
            'success' => true,
            'data' => $transactions
        ]);
    }

    /**
     * Generate QR for web interface (improved)
     */
    public function generateForWeb(Request $request)
    {
        $amount = $request->input('amount', 200);
        $bookingReference = $request->input('booking_reference');
        
        $result = $this->khqrService->generateIndividual($amount, 'Hotel Booking Payment');

        if (!$result['success']) {
            return view('khqr-error', ['error' => $result['message']]);
        }

        // Create transaction record for tracking
        $transaction = Transactions::create([
            'transaction_id' => 'WEB_' . Str::upper(Str::random(12)),
            'bakong_account_id' => 'sok_chanmakara@aclb',
            'qr_string' => $result['qr_string'],
            'qr_md5' => $result['md5'],
            'qr_full_hash' => $result['full_hash'],
            'qr_short_hash' => $result['short_hash'],
            'amount' => $amount,
            'currency' => 'KHR',
            'merchant_name' => 'Pteas Khmer',
            'status' => 'pending',
            'expires_at' => Carbon::now()->addMinutes(5),
            'booking_reference' => $bookingReference,
        ]);

        // Schedule automatic status updates
        $this->scheduleStatusUpdates($transaction->transaction_id);

        // Store transaction ID in session for tracking
        session(['current_transaction_id' => $transaction->transaction_id]);

        return view('khqr-trackable', [
            'qrString' => $result['qr_string'],
            'md5' => $result['md5'],
            'amount' => $amount,
            'transaction_id' => $transaction->transaction_id,
            'expires_at' => $transaction->expires_at,
            'message' => $result['message'],
            'hasApiToken' => !empty(env('BAKONG_API_TOKEN'))
        ]);
    }

    /**
     * Schedule automatic status update jobs
     */
    private function scheduleStatusUpdates(string $transactionId): void
    {
        // Immediate check (30 seconds)
        UpdateTransactionStatus::dispatch($transactionId, 0)->delay(now()->addSeconds(30));
        
        // Second check (1 minute)
        UpdateTransactionStatus::dispatch($transactionId, 1)->delay(now()->addMinute());
        
        // Third check (2 minutes)
        UpdateTransactionStatus::dispatch($transactionId, 2)->delay(now()->addMinutes(2));
        
        // Fourth check (3 minutes)
        UpdateTransactionStatus::dispatch($transactionId, 3)->delay(now()->addMinutes(3));
        
        // Final check (4 minutes)
        UpdateTransactionStatus::dispatch($transactionId, 4)->delay(now()->addMinutes(4));

        Log::info("Scheduled automatic status updates", [
            'transaction_id' => $transactionId,
            'checks_scheduled' => 5
        ]);
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
}
