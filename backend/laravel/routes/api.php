<?php

use App\Http\Controllers\Admin\RoomsController;
use App\Http\Controllers\Amenity\AmenityController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BlockRoom\BlockRoomController;
use App\Http\Controllers\Bookings\BookingController;
use App\Http\Controllers\Houses\HouseListingController;
use App\Http\Controllers\Images\ImageController;
use App\Http\Controllers\ImageProxyController;
use App\Http\Controllers\OptimizedImageController;
use App\Http\Controllers\OwnerController;
use App\Http\Controllers\OwnerRoomController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PrivateFileController;
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
Route::post('/payments/with-booking', [PaymentController::class, 'createPaymentWithBooking']);
Route::post('/payments/create-booking', [PaymentController::class, 'createBookingAfterPayment']);
Route::get('/payments/{transactionId}/status', [PaymentController::class, 'checkStatus']);
Route::get('/payments/{transactionId}/status-with-booking', [PaymentController::class, 'checkStatusAndCreateBooking']);
Route::get('/payments', [PaymentController::class, 'getTransactions'])->middleware('auth:sanctum');

// Image optimization routes
Route::get('/images/proxy', [OptimizedImageController::class, 'proxy']);
Route::post('/images/clear-cache', [OptimizedImageController::class, 'clearCache']);

// Legacy image routes
Route::get('/images/{path}', [ImageProxyController::class, 'getImage'])->where('path', '.*');
Route::get('/test-minio', [ImageProxyController::class, 'testMinioConnection']);

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
   
    // Room Management Routes
    Route::get('/properties', [OwnerRoomController::class, 'getOwnerProperties']);
    Route::get('/properties/{propertyId}/rooms', [OwnerRoomController::class, 'getPropertyRooms']);
    Route::post('/rooms', [OwnerRoomController::class, 'createRoom']);
    Route::put('/rooms/{roomId}', [OwnerRoomController::class, 'updateRoom']);
    Route::delete('/rooms/{roomId}', [OwnerRoomController::class, 'deleteRoom']);
    Route::get('/amenities', [OwnerRoomController::class, 'getAmenities']);
    Route::get('/statistics', [OwnerRoomController::class, 'getRoomStatistics']);
});
// amenity
Route::get('/amenities', [AmenityController::class, 'index']);
Route::get('/rooms/available', [RoomTypeController::class, 'getRoomsIsAvailableIds']);
Route::get('/rooms', [RoomTypeController::class, 'getAllRooms']);

// Image proxy routes
Route::get('/images/{imageName}', [ImageProxyController::class, 'getImage'])->where('imageName', '.*');
Route::get('/images', [ImageProxyController::class, 'listImages']);
Route::get('/test-minio', [ImageProxyController::class, 'testMinioConnection']);
Route::get('/test-image/{imagePath}', [ImageProxyController::class, 'testSpecificImage'])->where('imagePath', '.*');

//Bookings
Route::post('/bookings', [BookingController::class, 'store']);


// Update Route
Route::middleware('auth:sanctum')->put('/me', [AuthController::class, 'updateMe']);

//Get all Applications
Route::get('/allhouse',[HouseListingController::class,'getAllHouses']);
Route::get('/hotels',[HouseListingController::class,'getAllHouses']); // Alias for allhouse

// Test route
Route::get('/test', function () {
    return response()->json(['message' => 'API is working!', 'timestamp' => now()]);
});

Route::get('/rooms/blocked-today', [BlockRoomController::class, 'getTodayBlockedRooms']);
Route::get('/house/{id}', [HouseListingController::class, 'getHouseById']);

// api.php
//block room
// Block a room (POST)
Route::post('/block-room/{roomId}', [BlockRoomController::class, 'blockRoom'])
    ->middleware('auth:sanctum');

// Unblock a room (PATCH or POST)
Route::post('/unblock-room/{roomId}', [BlockRoomController::class, 'unblock'])
    ->middleware('auth:sanctum');
Route::get('/block-room/{roomid}', [HouseListingController::class, 'getBlockedDates'])->middleware('auth:sanctum');
// Get houses owned by the authenticated user
Route::middleware('auth:sanctum')->get('/houses/owner', [RoomsController::class, 'getHousesOwner'])->middleware('auth:sanctum');
Route::middleware('auth:sanctum')->get('/houses/owner/{id}', [RoomsController::class, 'getOwnerHouseById'])->middleware('auth:sanctum');
// Get all room types
Route::middleware('auth:sanctum')->get('/houses/{houseId}/rooms', [RoomsController::class, 'getRoomsBelogingToHouse'])->middleware('auth:sanctum');
// Get a specific room type by ID
Route::middleware('auth:sanctum')->get('/houses/{houseId}/rooms/{roomId}', [RoomsController::class, 'getRoomByIdBelongtoHouseId'])->middleware('auth:sanctum');
// Update a specific room type by ID
Route::middleware('auth:sanctum')->put('/houses/{houseId}/rooms/{roomId}', [RoomsController::class, 'updateRoombyIdBelongtoHouseId'])->middleware('auth:sanctum');

// Room Management Routes for Property Owners
Route::middleware('auth:sanctum')->group(function () {
    Route::prefix('owner')->group(function () {
        // Property management
        Route::get('properties', [OwnerRoomController::class, 'getOwnerProperties']);
        Route::get('properties/{propertyId}/rooms', [OwnerRoomController::class, 'getPropertyRooms']);
        
        // Room management
        Route::post('rooms', [OwnerRoomController::class, 'createRoom']);
        Route::put('rooms/{roomId}', [OwnerRoomController::class, 'updateRoom']);
        Route::delete('rooms/{roomId}', [OwnerRoomController::class, 'deleteRoom']);
        Route::get('rooms/{roomId}', [OwnerRoomController::class, 'getRoom']);
        
        // Room amenities
        Route::get('amenities', [OwnerRoomController::class, 'getAmenities']);
        Route::post('rooms/{roomId}/amenities', [OwnerRoomController::class, 'updateRoomAmenities']);
        
        // Room statistics
        Route::get('statistics', [OwnerRoomController::class, 'getRoomStatistics']);
    });
});

// Private file routes (require authentication)
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/files/private/upload', [PrivateFileController::class, 'uploadPrivateFile']);
    Route::get('/files/private/{path}', [PrivateFileController::class, 'getPrivateFile'])->where('path', '.*');
    Route::get('/files/private/stream/{path}', [PrivateFileController::class, 'streamPrivateFile'])->where('path', '.*');
    Route::get('/files/private/download/{path}', [PrivateFileController::class, 'downloadPrivateFile'])->where('path', '.*');
    Route::delete('/files/private/{path}', [PrivateFileController::class, 'deletePrivateFile'])->where('path', '.*');
    Route::get('/files/private/list/{directory?}', [PrivateFileController::class, 'listPrivateFiles'])->where('directory', '.*');
});