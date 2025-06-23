<?php

namespace App\Http\Controllers\Admin;
use Intervention\Image\Facades\Image;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Storage;

class RoomsController extends Controller
{
    public function getHousesOwner(Request $request) // Get all houses owned by the authenticated user
    {  
         $user = auth()->user();
    $houses = $user->ownerApplication()
        ->get();

    return response()->json($houses,200);
    }
    public function getOwnerHouseById($id) // Get a specific house by ID
    {
        $house = auth()->user()->ownerApplication()
            ->where('id', $id)
            ->first();

        if (!$house) {
            return response()->json(['message' => 'House not found'], 404);
        }

        return response()->json($house, 200);
    }
    public function getRoomsBelogingToHouse($houseId) // Get all rooms belonging to a specific house
    {
        $house = auth()->user()->ownerApplication()
            ->where('id', $houseId)
            ->first();

        if (!$house) {
            return response()->json(['message' => 'House not found'], 404);
        }

        $rooms = $house->roomTypes()->with('images', 'amenities', 'blockDates')->get();
        if ($rooms->isEmpty()) {
            return response()->json(['message' => 'No rooms found for this house'], 404);
        }
        return response()->json($rooms, 200);
    }
public function updateRoombyIdBelongtoHouseId(Request $request, $houseId, $roomId)
{
    // Verify the house belongs to the authenticated user
    $house = auth()->user()->ownerApplication()->where('id', $houseId)->first();
    if (!$house) {
        return response()->json(['message' => 'House not found'], 404);
    }
    
    // Find the room
    $room = $house->roomTypes()->where('id', $roomId)->first();
    if (!$room) {
        return response()->json(['message' => 'Room not found'], 404);
    }
    
    // Validate the request data
    $data = $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'capacity' => 'required|integer|min:1',
        'default_price' => 'required|numeric|min:0',
        'is_available' => 'boolean',
        'images' => 'nullable|array',
        'images.*' => 'image|max:48128',
        'amenities' => 'nullable|array',
        'amenities.*' => 'exists:amenities,id',
        'deleted_images' => 'nullable|array',
        'deleted_images.*' => 'integer|exists:room_images,id',
    ]);
    
    // ✅ DELETE old images if specified
    if (!empty($data['deleted_images'])) {
        foreach ($data['deleted_images'] as $imageId) {
            $image = $room->images()->find($imageId);
            if ($image) {
                // Remove 'ownerimages/' prefix before deleting from storage
                $imagePathForDeletion = str_replace('ownerimages/', '', $image->image_url);
                $thumbnailPathForDeletion = str_replace('ownerimages/', '', $image->thumbnail_url);
                
                Storage::disk('minio')->delete($imagePathForDeletion);
                Storage::disk('minio')->delete($thumbnailPathForDeletion);
                $image->delete();
            }
        }
    }
    
    // ✅ Update room information
    $room->update([
        'name' => $data['name'],
        'description' => $data['description'] ?? $room->description,
        'capacity' => $data['capacity'],
        'default_price' => $data['default_price'],
        'is_available' => $data['is_available'] ?? $room->is_available,
    ]);
    
    // ✅ Update amenities if provided
    if (isset($data['amenities'])) {
        $room->amenities()->sync($data['amenities']);
    }
    
    // ✅ Upload new images
    if ($request->hasFile('images')) {
        foreach ($request->file('images') as $image) {
            // Generate filename exactly like your existing code
            $fileName = 'room_' . $room->id . '_' . time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            
            // Save original image to room-images/ directory
            $imagePath = 'room-images/' . $fileName;
            Storage::disk('minio')->put($imagePath, file_get_contents($image));
            
            // Create relative path with ownerimages/ prefix for database storage
            $relativeImagePath = 'ownerimages/' . $imagePath;
            
            // Generate thumbnail filename and path
            $thumbnailFileName = 'thum_' . $fileName;
            $thumbnailPath = 'rooms/thumbnails/' . $thumbnailFileName;
            
            // Create and save thumbnail
            $thumbnail = Image::make($image)
                ->resize(200, 200, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                })
                ->encode($image->getClientOriginalExtension());
            
            Storage::disk('minio')->put($thumbnailPath, $thumbnail->getEncoded());
            
            // Create relative path with ownerimages/ prefix for database storage
            $relativeThumbnailPath = 'ownerimages/' . $thumbnailPath;
            
            // Save to database
            $room->images()->create([
                'image_url' => $relativeImagePath,
                'thumbnail_url' => $relativeThumbnailPath,
            ]);
        }
    }
    
    // Return updated room with relationships
    $room = $room->fresh(['images', 'amenities', 'blockDates']);
    return response()->json([
        'message' => 'Room updated successfully',
        'room' => $room,
    ], 200);
}

    public function getRoomByIdBelongtoHouseId($houseId, $roomId) // Delete a specific room by ID
    {
        $house = auth()->user()->ownerApplication()
            ->where('id', $houseId)
            ->first();

        if (!$house) {
            return response()->json(['message' => 'House not found'], 404);
        }

        $room = $house->roomTypes()->with('images', 'amenities', 'blockDates')->where('id', $roomId)->first();

        if (!$room) {
            return response()->json(['message' => 'Room not found'], 404);
        }

        return response()->json($room, 200);
    }
}
