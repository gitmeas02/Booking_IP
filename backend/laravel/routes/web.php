<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\PaymentController;
use App\Services\KHQRService;

// Ecommerce test interface
Route::get('/ecommerce-test', function () {
    return view('ecommerce-test');
});

// API routes for ecommerce integration (handles both API and web form submissions)
Route::post('/payment', [PaymentController::class, 'create']);
Route::get('/payment/status/{transactionId}', [PaymentController::class, 'checkStatus']);
Route::get('/payments', [PaymentController::class, 'getTransactions']);

// Web routes for testing and demo
Route::get('/', function () {
    return view('welcome');
});


// Generate QR with transaction tracking (improved)
Route::get('/generate-trackable-khqr', [PaymentController::class, 'generateForWeb']);

// Check transaction status (improved - now uses transaction ID from session)
Route::get('/check-transaction', function (KHQRService $service) {
    $transactionId = session('current_transaction_id');
    $md5Hash = session('current_qr_md5') ?? request('md5');
    
    if (!$md5Hash && !$transactionId) {
        return response()->json([
            'success' => false,
            'message' => 'No transaction found to check'
        ], 400);
    }
    
    // If we have transaction ID, use the PaymentController method
    if ($transactionId) {
        $controller = new PaymentController($service);
        return $controller->checkStatus($transactionId);
    }
    
    // Fallback to direct MD5 check
    $result = $service->checkTransactionByMD5($md5Hash);
    return response()->json($result);
});

// Check API token status
Route::get('/token-status', function (KHQRService $service) {
    $status = $service->getTokenStatus();
    return response()->json($status);
});

// Renew token endpoint
Route::post('/renew-token', function (KHQRService $service) {
    $email = request('email');
    
    if (!$email) {
        return response()->json([
            'success' => false,
            'message' => 'Email is required'
        ], 400);
    }
    
    $result = KHQRService::renewToken($email, true); // Use testing environment
    return response()->json($result);
});

// Hash Manager routes
Route::get('/hash-manager', function () {
    return view('hash-manager');
});

Route::post('/hash-manager/find', function (Request $request) {
    $hash = $request->input('hash');
    $transaction = \App\Services\KHQRHashService::findTransactionByHash($hash);
    
    if ($transaction) {
        return response()->json([
            'success' => true,
            'transaction' => $transaction,
            'message' => 'Transaction found'
        ]);
    }
    
    return response()->json([
        'success' => false,
        'transaction' => null,
        'message' => 'No transaction found with this hash'
    ]);
});

Route::post('/hash-manager/show', function (Request $request) {
    $transactionId = $request->input('transaction_id');
    $hashes = \App\Services\KHQRHashService::getTransactionHashes($transactionId);
    
    if ($hashes) {
        return response()->json([
            'success' => true,
            'hashes' => $hashes,
            'message' => 'Hashes retrieved successfully'
        ]);
    }
    
    return response()->json([
        'success' => false,
        'hashes' => null,
        'message' => 'Transaction not found'
    ]);
});

Route::post('/hash-manager/update', function () {
    $result = \App\Services\KHQRHashService::updateAllMissingHashes();
    
    return response()->json([
        'success' => true,
        'updated_count' => $result['updated_count'],
        'total_transactions' => $result['total_transactions'],
        'results' => $result['results'],
        'message' => 'Hashes updated successfully'
    ]);
});

