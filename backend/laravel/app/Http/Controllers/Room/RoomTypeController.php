<?php

namespace App\Http\Controllers\Room;

use App\Models\RoomImage;
use App\Models\RoomPrice;
use App\Models\RoomType;
use Auth;
use Intervention\Image\Facades\Image;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Storage;


class RoomTypeController extends Controller
{
   public function storeMultiple(Request $request, $ownerApplicationId)
{
    $data = $request->validate([
        'rooms' => 'required|array|min:1',
        'rooms.*.id' => 'nullable|integer',
        'rooms.*.name' => 'required|string',
        'rooms.*.description' => 'nullable|string',
        'rooms.*.capacity' => 'required|integer|min:1',
        'rooms.*.amenities' => 'nullable|array|exists:amenities,id', // Moved amenities validation here
        'rooms.*.default_price' => 'required|numeric',
        'rooms.*.images' => 'nullable|array',
        'rooms.*.images.*' => 'required|image|max:48128',
        // 'rooms.*.images' => 'nullable|array',
        // 'rooms.*.images.*.url' => 'required|string',
    ]);

    foreach ($data['rooms'] as $roomData) {
        $capacity = $roomData['capacity'];

        for ($i = 1; $i <= $capacity; $i++) {
            // Create unique name like "Deluxe King #1", "Deluxe King #2"
            $roomName = $capacity > 1 ? $roomData['name'] . " #$i" : $roomData['name'];

            // Create room type
            $room = RoomType::create([
                'name' => $roomName,
                'capacity' => 1, // Each individual room entry is for 1 room
                'description' => $roomData['description'] ?? null,
                'default_price' => $roomData['default_price'],
                'application_id' => $ownerApplicationId,
            ]);

            // Sync amenities if provided
            if (!empty($roomData['amenities'])) {
                $room->amenities()->sync($roomData['amenities']);
            }

            // Handle images
            if (!empty($roomData['images'])) {

                // $images = array_map(function ($image) {
                //     return new RoomImage(['image_url' => $image['url']]);
                // }, $roomData['images']);
                // $room->images()->saveMany($images);
                $images = [];
                foreach($roomData['images'] as $image){
                    $fileName='room_'.$room->id . '_' . time() . '_'.uniqid() . '.' . $image->getClientOriginalExtension();
                    $path= Storage::disk('minio')->put('room-images/',$image, $fileName);
                    $url= Storage::disk('minio')->url($path);
                     //thumbnailfile name
                    $thumbnailFileName='thum_'. $fileName;
                    // resize image
                    $thumbnail=Image::make($image)->resize(200,200, function($constraint){
                        $constraint->aspectRatio();
                        $constraint->upsize();
                    })->encode($image->getClientOriginalExtension());
                    //path in minio thumbnails
                    $thumbnailPath='rooms/thumbnails'. $thumbnailFileName;
                    //post to minio
                    Storage::disk('minio')->put($thumbnailPath, $thumbnail->getEncoded());

                    $thumbnailUrl = Storage::disk('minio')->url($thumbnailPath);
                    // Storage::disk('minio')->put('thumbnails/rooms'. $thumbnailFileName);
                    $images[] = new RoomImage([
                        'image_url'=>$url,
                        'thumbnail_url'=> $thumbnailUrl,
                    ]);

                }
                $room->images()->saveMany($images);
            }
        }
    }

    return response()->json(['message' => 'Multiple rooms created based on capacity']);
}

public function deleteRoomByIdAndOwnerApplication(Request $request, $roomId){
    // delete from table and delete in minio 
}
public function getRoomByIdAndOwnerApplication(Request $request, $roomId){
   
}
public function updateRoomByIdAndOwnerApplication(Request $request, $roomId){
    
}
public function getRooms(){
    $data= RoomType::all();
    return response()->json($data);
}

 // update price 
    public function updatePrice(Request $request, $roomTypeId)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'custom_price' => 'required|integer',
        ]);

        $price = RoomPrice::create([
            'room_type_id' => $roomTypeId,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'custom_price' => $request->custom_price,
        ]);

        return response()->json($price, 201);
    }
}


 
