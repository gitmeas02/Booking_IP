<?php

use App\Http\Controllers\Amenity\AmenityController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Images\ImageController;
use App\Http\Controllers\OwnerController;
use App\Http\Controllers\RoleController;
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
    Route::post('/owner/apply', [OwnerController::class, 'apply']);
});
// company
Route::middleware('auth:sanctum')->prefix('company')->group(function () {
Route::post(  '/approve-owner/{id}', [OwnerController::class, 'approve']);
});
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

