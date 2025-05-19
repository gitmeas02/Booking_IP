<?php

namespace App\Http\Controllers;

use App\Models\RoomPrice;
use App\Models\RoomType;
use Illuminate\Http\Request;

class RoomTypeController extends Controller
{
    public function store(Request $request, $ownerApplicationId)
    {
        $request->validate([
            'name' => 'required|string',
            'capacity' => 'required|integer',
            'default_price' => 'required|integer',
            'description' => 'nullable|string',
            'amenities' => 'array',
            'images' => 'array',
        ]);

        $room = RoomType::create([
            'owner_application_id' => $ownerApplicationId,
            'name' => $request->name,
            'capacity' => $request->capacity,
            'default_price' => $request->default_price,
            'description' => $request->description,
        ]);

        // Attach amenities
        if ($request->has('amenities')) {
            $room->amenities()->sync($request->amenities);
        }

        // Save images
        if ($request->has('images')) {
            foreach ($request->images as $img) {
                $room->images()->create(['image_url' => $img]);
            }
        }

        return response()->json($room->load(['images', 'amenities']), 201);
    }

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
