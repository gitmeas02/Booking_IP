<?php

namespace App\Http\Controllers\Room;

use App\Models\RoomImage;
use App\Models\RoomPrice;
use App\Models\RoomType;
use Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class RoomTypeController extends Controller
{
   public function storeMultiple(Request $request, $ownerApplicationId)
{
    $data = $request->validate([
        'rooms' => 'required|array|min:1',
        'rooms.*.id' => 'nullable|integer',
        'application_id' => $ownerApplicationId,
        'rooms.*.name' => 'required|string',
        'rooms.*.description' => 'nullable|string',
        'rooms.*.capacity' => 'required|integer|min:1',
        'rooms.*.default_price' => 'required|numeric',
        'rooms.*.images' => 'nullable|array',
        'rooms.*.images.*.url' => 'required|string',
    ]);

    foreach ($data['rooms'] as $roomData) {
        $capacity = $roomData['capacity'];

        for ($i = 1; $i <= $capacity; $i++) {
            // Create unique name like "Deluxe King #1", "Deluxe King #2"
            $roomName = $capacity > 1 ? $roomData['name'] . " #$i" : $roomData['name'];

            $room = RoomType::create([
                'name' => $roomName,
                'capacity' => 1, // Each individual room entry is for 1 room
                'description' => $roomData['description'] ?? null,
                'default_price' => $roomData['default_price'],
                'application_id' => $ownerApplicationId,
            ]);

            // Handle images
            if (!empty($roomData['images'])) {
                $images = array_map(function ($image) {
                    return new RoomImage(['image_url' => $image['url']]);
                }, $roomData['images']);

                $room->images()->saveMany($images);
            }
        }
    }

    return response()->json(['message' => 'Multiple rooms created based on capacity']);
}
public function getRoomByIdAndOwnerApplication(){
   
}
public function updateRoomByIdAndOwnerApplication(){
   
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


 
