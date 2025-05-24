<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\OwnerController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\RoomTypeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {

    // Authenticated user info
    Route::get('/user', function (Request $request) {
        $user = $request->user()->load('roles');
        return response()->json([
            'user' => $user,
            'roles' => $user->roles->pluck('name'),
        ]);
    });

    Route::get('/me', [AuthController::class, 'me']);

    // Role management
    Route::get('/check-role/{role}', [RoleController::class, 'checkRole']);
    Route::post('/switch-role', [RoleController::class, 'switchRole']);
    Route::get('/user-roles', [RoleController::class, 'getUserRoles']);

    // Owner application
    Route::post('/owner/apply', [OwnerController::class, 'apply']);
    Route::post('/owner/approve/{id}', [OwnerController::class, 'approve']); // Possibly admin only

    // Owner property management
    Route::prefix('owner')->group(function () {
        Route::post('/property/{id}/room', [RoomTypeController::class, 'store']);
        Route::post('/room/{id}/price', [RoomTypeController::class, 'updatePrice']);
    });
});
