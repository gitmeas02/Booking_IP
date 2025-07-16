<?php

namespace App\Http\Controllers;

use App\Models\RoomType;
use App\Models\OwnerApplication;
use App\Models\Amenity;
use App\Models\RoomImage;
use App\Models\Booking;
use App\Services\CacheService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class OwnerRoomController extends Controller
{
    /**
     * Get all properties owned by the authenticated user
     */
    public function getOwnerProperties()
    {
        try {
            $userId = Auth::id();
            
            // Use caching for better performance
            $properties = CacheService::cachePropertyList($userId, function() use ($userId) {
                return OwnerApplication::where('user_id', $userId)
                    ->with(['photos' => function($query) {
                        $query->limit(1)->select('id', 'application_id', 'photo_url');
                    }, 'amenities' => function($query) {
                        $query->select('id', 'name');
                    }, 'location' => function($query) {
                        $query->select('id', 'application_id', 'address', 'city', 'province');
                    }])
                    ->select('id', 'property_name', 'description', 'star_rating', 'status', 'created_at')
                    ->get();
            });

            return response()->json([
                'success' => true,
                'data' => $properties,
                'message' => 'Properties retrieved successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching owner properties: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error fetching properties: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get rooms for a specific property
     */
    public function getPropertyRooms($propertyId)
    {
        try {
            // Verify property ownership
            $property = OwnerApplication::where('id', $propertyId)
                ->where('user_id', Auth::id())
                ->first();

            if (!$property) {
                return response()->json([
                    'success' => false,
                    'message' => 'Property not found or unauthorized'
                ], 404);
            }

            // Use caching for room data
            $rooms = CacheService::cacheRoomTypes($propertyId, function() use ($propertyId) {
                return RoomType::where('application_id', $propertyId)
                    ->with(['images' => function($query) {
                        $query->select('id', 'room_type_id', 'image_url');
                    }, 'amenities' => function($query) {
                        $query->select('amenities.id', 'amenities.name');
                    }, 'prices' => function($query) {
                        $query->select('id', 'room_type_id', 'date', 'price');
                    }])
                    ->select('id', 'application_id', 'name', 'capacity', 'default_price', 'description', 'created_at')
                    ->get();
            });

            return response()->json([
                'success' => true,
                'data' => [
                    'property' => $property,
                    'rooms' => $rooms
                ],
                'message' => 'Rooms retrieved successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching rooms: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Create a new room for a property
     */
    public function createRoom(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'application_id' => 'required|exists:owner_applications,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'default_price' => 'required|numeric|min:0',
            'capacity' => 'required|integer|min:1',
            'bed_type' => 'nullable|string|in:single,double,queen,king,bunk',
            'room_size' => 'nullable|numeric|min:0',
            'amenities' => 'nullable|array',
            'amenities.*' => 'integer|exists:amenities,id',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:5120' // 5MB max
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Verify property ownership
            $property = OwnerApplication::where('id', $request->application_id)
                ->where('user_id', Auth::id())
                ->first();

            if (!$property) {
                return response()->json([
                    'success' => false,
                    'message' => 'Property not found or unauthorized'
                ], 404);
            }

            DB::beginTransaction();

            // Create room type
            $roomType = RoomType::create([
                'application_id' => $request->application_id,
                'name' => $request->name,
                'description' => $request->description,
                'default_price' => $request->default_price,
                'capacity' => $request->capacity,
                'bed_type' => $request->bed_type ?? 'double',
                'room_size' => $request->room_size,
                'created_by' => Auth::id()
            ]);

            // Attach amenities if provided
            if ($request->has('amenities') && is_array($request->amenities)) {
                $roomType->amenities()->attach($request->amenities);
            }

            // Handle image uploads
            if ($request->hasFile('images')) {
                $this->uploadRoomImages($request->file('images'), $roomType->id);
            }

            DB::commit();

            // Return created room with relationships
            $createdRoom = RoomType::with(['images', 'amenities', 'prices'])
                ->find($roomType->id);

            return response()->json([
                'success' => true,
                'data' => $createdRoom,
                'message' => 'Room created successfully'
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error creating room: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update an existing room
     */
    public function updateRoom(Request $request, $roomId)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'default_price' => 'required|numeric|min:0',
            'capacity' => 'required|integer|min:1',
            'bed_type' => 'nullable|string|in:single,double,queen,king,bunk',
            'room_size' => 'nullable|numeric|min:0',
            'amenities' => 'nullable|array',
            'amenities.*' => 'integer|exists:amenities,id',
            'new_images' => 'nullable|array',
            'new_images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'remove_images' => 'nullable|array',
            'remove_images.*' => 'integer|exists:room_images,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Get room and verify ownership
            $roomType = RoomType::whereHas('property', function ($query) {
                $query->where('user_id', Auth::id());
            })->with(['property', 'images', 'amenities'])->find($roomId);

            if (!$roomType) {
                return response()->json([
                    'success' => false,
                    'message' => 'Room not found or unauthorized'
                ], 404);
            }

            DB::beginTransaction();

            // Update room type
            $roomType->update([
                'name' => $request->name,
                'description' => $request->description,
                'default_price' => $request->default_price,
                'capacity' => $request->capacity,
                'bed_type' => $request->bed_type ?? 'double',
                'room_size' => $request->room_size,
                'updated_by' => Auth::id()
            ]);

            // Update amenities
            if ($request->has('amenities')) {
                $roomType->amenities()->sync($request->amenities);
            }

            // Remove images if requested
            if ($request->has('remove_images')) {
                $imagesToRemove = RoomImage::where('room_type_id', $roomType->id)
                    ->whereIn('id', $request->remove_images)
                    ->get();

                foreach ($imagesToRemove as $image) {
                    if (Storage::disk('public')->exists($image->image_url)) {
                        Storage::disk('public')->delete($image->image_url);
                    }
                    $image->delete();
                }
            }

            // Add new images
            if ($request->hasFile('new_images')) {
                $this->uploadRoomImages($request->file('new_images'), $roomType->id);
            }

            DB::commit();

            // Return updated room
            $updatedRoom = RoomType::with(['images', 'amenities', 'prices'])
                ->find($roomType->id);

            return response()->json([
                'success' => true,
                'data' => $updatedRoom,
                'message' => 'Room updated successfully'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error updating room: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete a room
     */
    public function deleteRoom($roomId)
    {
        try {
            $roomType = RoomType::whereHas('property', function ($query) {
                $query->where('user_id', Auth::id());
            })->with(['images'])->find($roomId);

            if (!$roomType) {
                return response()->json([
                    'success' => false,
                    'message' => 'Room not found or unauthorized'
                ], 404);
            }

            // Check if room has active bookings
            $hasActiveBookings = Booking::where('room_id', $roomId)
                ->where('status', 'confirmed')
                ->where('check_out', '>', now())
                ->exists();

            if ($hasActiveBookings) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete room with active bookings'
                ], 409);
            }

            DB::beginTransaction();

            // Delete room images from storage
            foreach ($roomType->images as $image) {
                if (Storage::disk('public')->exists($image->image_url)) {
                    Storage::disk('public')->delete($image->image_url);
                }
                $image->delete();
            }

            // Delete room type
            $roomType->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Room deleted successfully'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error deleting room: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get available amenities for rooms
     */
    public function getAmenities()
    {
        try {
            $amenities = Amenity::select('id', 'amenity_name', 'icon')
                ->orderBy('amenity_name')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $amenities,
                'message' => 'Amenities retrieved successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching amenities: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Upload room images
     */
    private function uploadRoomImages($images, $roomTypeId)
    {
        foreach ($images as $image) {
            $filename = Str::uuid() . '.' . $image->getClientOriginalExtension();
            $path = $image->storeAs('room-images', $filename, 'public');

            RoomImage::create([
                'room_type_id' => $roomTypeId,
                'image_url' => $path,
                'alt_text' => 'Room image',
                'is_primary' => false,
                'uploaded_by' => Auth::id()
            ]);
        }
    }

    /**
     * Get room statistics for owner dashboard
     */
    public function getRoomStatistics()
    {
        try {
            $userId = Auth::id();
            
            $stats = [
                'total_properties' => OwnerApplication::where('user_id', $userId)->count(),
                'total_rooms' => RoomType::whereHas('property', function ($query) use ($userId) {
                    $query->where('user_id', $userId);
                })->count(),
                'total_bookings' => Booking::whereHas('room.property', function ($query) use ($userId) {
                    $query->where('user_id', $userId);
                })->count(),
                'active_bookings' => Booking::whereHas('room.property', function ($query) use ($userId) {
                    $query->where('user_id', $userId);
                })->where('status', 'confirmed')
                  ->where('check_out', '>', now())
                  ->count(),
            ];

            return response()->json([
                'success' => true,
                'data' => $stats,
                'message' => 'Statistics retrieved successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching statistics: ' . $e->getMessage()
            ], 500);
        }
    }
}
