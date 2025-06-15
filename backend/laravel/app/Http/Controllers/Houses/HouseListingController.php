<?php

namespace App\Http\Controllers\Houses;

use App\Http\Controllers\Controller;
use App\Models\OwnerApplication;
use App\Models\Room\BlockedRoom;
use App\Models\RoomType;
use Carbon\Carbon;
use Illuminate\Http\Request;

class HouseListingController extends Controller
{public function getAllHouses()
{
    $today = Carbon::now('UTC')->toDateString();// Hardcoded for this context, typically use Carbon::now()->toDateString()
    $houses = OwnerApplication::where('status', 'approved')
        ->where('property_status', 'active')
        ->whereHas('roomTypes', function ($query) use ($today) {
            $query->whereDoesntHave('blockDates', function ($q) use ($today) {
                $q->where('start_date', '<=', $today)
                  ->where('end_date', '>=', $today);
            });
        })
        ->with([
            'user',
            'personalInfo',
            'location',
            'amenities',
            'services',
            'photos',
            'houseRules',
            'paymentMethods',
            'roomTypes.images',
            'roomTypes.amenities',
            'roomTypes.blockDates',
            'feedbacks',
            'commissions',
        ])
        ->get();

    return response()->json($houses);
}
public function getTodayBlockedRooms()
{
    $today = Carbon::now('UTC')->toDateString(); // or just Carbon::today() if you're okay with timezone

    $blockedRooms = BlockedRoom::where('start_date', '<=', $today)
        ->where('end_date', '>=', $today)
        ->with('rooms.property') // eager load room and its hotel
        ->get();

    return response()->json($blockedRooms);
}
public function getBlockedDates($roomTypeId)
{
    $roomType = RoomType::findOrFail($roomTypeId);

    // Load blocked dates related to the room type
    $blockedDates = $roomType->blockDates()->get(['start_date', 'end_date', 'reason']);

    return response()->json([
        'room_type_id' => $roomTypeId,
        'blocked_dates' => $blockedDates,
    ]);
}

}
