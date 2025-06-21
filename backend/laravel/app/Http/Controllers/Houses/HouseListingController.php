<?php

namespace App\Http\Controllers\Houses;

use App\Http\Controllers\Controller;
use App\Models\OwnerApplication;
use App\Models\Room\BlockedRoom;
use App\Models\RoomType;
use Carbon\Carbon;
use Illuminate\Http\Request;

class HouseListingController extends Controller
{
    public function getAllHouses(Request $request)
    {
        $today = Carbon::now('UTC')->toDateString();

        $query = OwnerApplication::where('status', 'approved')
            ->where('property_status', 'active')
            ->whereHas('roomTypes', function ($query) use ($today) {
                $query->whereDoesntHave('blockDates', function ($q) use ($today) {
                    $q->where('start_date', '<=', $today)
                        ->where('end_date', '>=', $today);
                });
            });

        // Search by property_name if street param provided
        if ($request->has('street') && $request->street !== '') {
            $searchTerm = $request->street;
            $query->where('property_name', 'like', '%' . $searchTerm . '%');
        }

        // Add other filters like capacity, pets, etc. here if needed

        $houses = $query->with([
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
        ])->get();

        return response()->json($houses);
    }


    public function getHouseById($id)
    {
        $today = Carbon::now('UTC')->toDateString();

        $house = OwnerApplication::where('id', $id)
            ->where('status', 'approved')
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
            ->first();

        if (!$house) {
            return response()->json(['message' => 'House not found or not available'], 404);
        }

        return response()->json($house);
    }

    public function getTodayBlockedRooms()
    {
        $today = Carbon::now('UTC')->toDateString();

        $blockedRooms = BlockedRoom::where('start_date', '<=', $today)
            ->where('end_date', '>=', $today)
            ->with('rooms.property')
            ->get();

        return response()->json($blockedRooms);
    }

    public function getBlockedDates($roomTypeId)
    {
        $roomType = RoomType::findOrFail($roomTypeId);

        $blockedDates = $roomType->blockDates()->get(['start_date', 'end_date', 'reason']);

        return response()->json([
            'room_type_id' => $roomTypeId,
            'blocked_dates' => $blockedDates,
        ]);
    }
}
