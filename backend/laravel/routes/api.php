<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\OwnerController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PaymentController;
// use App\Models\OwnerApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Payment routes
Route::post('/payments', [PaymentController::class, 'create']);
Route::get('/payments/{transactionId}/status', [PaymentController::class, 'checkStatus']);
Route::get('/payments', [PaymentController::class, 'getTransactions'])->middleware('auth:sanctum');

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    // Route::get('/check-role', [AuthController::class, 'checkRole']);
    Route::get('/me', [AuthController::class, 'me']);
    // Route::post('/logout', [AuthController::class, 'logout']);
});
//Role Check API 
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/check-role/{role}', [RoleController::class, 'checkRole']);
});

Route::post('/switch-role', [RoleController::class, 'switchRole']);
Route::get('/user-roles', [RoleController::class, 'getUserRoles']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/apply-owner', [OwnerController::class, 'apply']);
    Route::post('/approve-owner/{id}', [OwnerController::class, 'approve']); // Optional for admin
});
