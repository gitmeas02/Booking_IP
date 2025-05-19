<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\OwnerController;
use App\Http\Controllers\RoleController;
// use App\Models\OwnerApplication;
use App\Http\Controllers\RoomTypeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

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

//
Route::middleware('auth:sanctum')->prefix('owner')->group(function () {
    Route::post('/property/{id}/room', [RoomTypeController::class, 'store']);
    Route::post('/room/{id}/price', [RoomTypeController::class, 'updatePrice']);
});

