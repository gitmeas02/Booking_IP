<?php

use App\Http\Controllers\Amenity\AmenityController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BlockRoom\BlockRoomController;
use App\Http\Controllers\Houses\HouseListingController;
use App\Http\Controllers\Images\ImageController;
use App\Http\Controllers\OwnerController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PaymentController;
// use App\Models\OwnerApplication;
use App\Http\Controllers\Room\RoomTypeController;
use App\Models\RoomType;
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
    Route::get('/user-roles/{user}', [RoleController::class, 'getUserRoles']);
    // Role switching
    Route::post('/switch-role', [RoleController::class, 'switchRole']);
    
    // Role management (admin only - add middleware as needed)
    Route::post('/assign-role', [RoleController::class, 'assignRole']);
    Route::post('/remove-role', [RoleController::class, 'removeRole']);

});


Route::middleware('auth:sanctum')->group(function () {
    Route::post('/owner/apply', [OwnerController::class, 'apply']);
});
// company
// Route::middleware('auth:sanctum')->prefix('company')->group(function () {
Route::post(  '/approve-owner/{id}', [OwnerController::class, 'approve']);
// });
//owner
Route::middleware('auth:sanctum')->prefix('owner')->group(function () {
    Route::post('/property/{id}/room', [RoomTypeController::class, 'storeMultiple']);
    Route::post('/room/{id}/price', [RoomTypeController::class, 'updatePrice']);
    Route::get('/application/{id}/images', [ImageController::class, 'getUserApplicationImages']);
   //http://localhost:8100/api/owner/application/7/images
});
// amenity
Route::get('/amenities', [AmenityController::class, 'index']);
Route::get('/rooms', [RoomTypeController::class, 'getRooms']);

//Images


// Update Route
Route::middleware('auth:sanctum')->put('/me', [AuthController::class, 'updateMe']);

//Get all Applications
Route::get('/allhouse',[HouseListingController::class,'getAllHouses']);
Route::get('/rooms/blocked-today', [BlockRoomController::class, 'getTodayBlockedRooms']);

// api.php
//block room
// Block a room (POST)
Route::post('/block-room/{roomId}', [BlockRoomController::class, 'blockRoom'])
    ->middleware('auth:sanctum');

// Unblock a room (PATCH or POST)
Route::post('/unblock-room/{roomId}', [BlockRoomController::class, 'unblock'])
    ->middleware('auth:sanctum');
Route::get('/block-room/{roomid}', [HouseListingController::class, 'getBlockedDates'])->middleware('auth:sanctum');
