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

// {
//   "rooms": [
//     {
//       "name": "Single Bed",
//       "description": "Spacious room with king bed",
//       "capacity": 2,
//       "default_price": 150.00,
//       "amenities":[1,2,3,4,5],
//       "images": [
//         { "url": "https://example.com/images/deluxe1.jpg" },
//         { "url": "https://example.com/images/deluxe2.jpg" }
//       ]
//     }
//   ]
// }
