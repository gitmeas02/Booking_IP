<?php

namespace App\Http\Controllers\Room;

use App\Models\Booking;
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
        'rooms.*.amenities' => 'nullable|array|exists:amenities,id',
        'rooms.*.default_price' => 'required|numeric',
        'rooms.0.images' => 'nullable|array',
        'rooms.0.images.*' => 'required|image|max:48128',
    ]);

    foreach ($data['rooms'] as $roomData) {
        $room = RoomType::create([
            'name' => $roomData['name'],
            'capacity' => $roomData['capacity'],
            'description' => $roomData['description'] ?? null,
            'default_price' => $roomData['default_price'],
            'application_id' => $ownerApplicationId,
        ]);

        if (!empty($roomData['amenities'])) {
            $room->amenities()->sync($roomData['amenities']);
        }

        // if (!empty($roomData['images'])) {
        //     $images = [];
        //     foreach ($roomData['images'] as $image) {
        //         $fileName = 'room_' . $room->id . '_' . time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
        //         $imagePath = 'room-images/' . $fileName;
        //         Storage::disk('minio')->put($imagePath, file_get_contents($image));
        //         $relativeImagePath = 'ownerimages/' . $imagePath;

        //         $thumbnailFileName = 'thum_' . $fileName;
        //         $thumbnailPath = 'rooms/thumbnails/' . $thumbnailFileName;
        //         $thumbnail = Image::make($image)
        //             ->resize(200, 200, function ($constraint) {
        //                 $constraint->aspectRatio();
        //                 $constraint->upsize();
        //             })
        //             ->encode($image->getClientOriginalExtension());
        //         Storage::disk('minio')->put($thumbnailPath, $thumbnail->getEncoded());
        //         $relativeThumbnailPath = 'ownerimages/' . $thumbnailPath;

        //         $images[] = new RoomImage([
        //             'image_url' => $relativeImagePath,
        //             'thumbnail_url' => $relativeThumbnailPath,
        //         ]);
        //     }
        //     $room->images()->saveMany($images);
        // }
        $sharedImage = $request->file('rooms.0.images')??[];
        if(!empty($sharedImage)){
          $images = [];
          foreach($sharedImage as $image){
           $fileName = 'room_' . $room->id . '_' . time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $imagePath = 'room-images/' . $fileName;
            Storage::disk('minio')->put($imagePath, file_get_contents($image));
            $relativeImagePath = 'ownerimages/' . $imagePath;

            $thumbnailFileName = 'thum_' . $fileName;
            $thumbnailPath = 'rooms/thumbnails/' . $thumbnailFileName;
            $thumbnail = Image::make($image)
                ->resize(200, 200, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            })
            ->encode($image->getClientOriginalExtension());
            Storage::disk('minio')->put($thumbnailPath, $thumbnail->getEncoded());
            $relativeThumbnailPath = 'ownerimages/' . $thumbnailPath;

           $images[] = new RoomImage([
            'image_url' => $relativeImagePath,
            'thumbnail_url' => $relativeThumbnailPath,
        ]);
          }
        }
        $room->images()->saveMany($images);
    }

    return response()->json(['message' => 'Rooms created successfully'], 201);
}

public function deleteRoomByIdAndOwnerApplication(Request $request, $roomId){
    // delete from table and delete in minio 
}
public function getRoomByIdAndOwnerApplication(Request $request, $roomId){
   
}
public function updateRoomByIdAndOwnerApplication(Request $request, $roomId){
    
}
public function getAllRooms(){
    $rooms = RoomType::with('blockDates')->get();
    return response()->json($rooms);
}

//{
//     "name": "King Bed",
//     "default_price": 21.00,
//     "start_date": "2025-06-22",
//     "end_date": "2025-06-24",
//     "quantity": 2
// }->

public function getRoomsIsAvailableIds(Request $request)
{
    try {
        // Log incoming request
        \Log::info('Room availability request:', $request->all());

        // Validate request
        $validated = $request->validate([
            'name' => 'required|string',
            'default_price' => 'required',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'quantity' => 'required|integer|min:1',
        ]);

        // Convert price to float for proper comparison
        $defaultPrice = (float)$validated['default_price'];

        // Find rooms matching name and price
        $rooms = RoomType::with('blockDates')
            ->where('name', $validated['name'])
            ->where('default_price', $defaultPrice)
            ->get();

        \Log::info('Found rooms:', ['count' => $rooms->count(), 'ids' => $rooms->pluck('id')]);
        
        if ($rooms->isEmpty()) {
            return response()->json([
                'room_ids' => [],
                'count' => 0,
                'message' => 'No rooms found matching criteria'
            ]);
        }

        $availableRooms = [];

        foreach ($rooms as $room) {
            $isBlocked = false;
            
            // Check block dates
            if ($room->blockDates) {
                foreach ($room->blockDates as $block) {
                    if ($block->start_date < $validated['end_date'] && $block->end_date > $validated['start_date']) {
                        $isBlocked = true;
                        \Log::info('Room is blocked:', ['room_id' => $room->id]);
                        break;
                    }
                }
            }

            // Skip booking check if already blocked - safest approach for now
            if (!$isBlocked) {
                // TEMP: Skip booking check until we fix the booking table
                // Just assume no bookings exist for now
                $availableRooms[] = $room->id;
                \Log::info('Room is available:', ['room_id' => $room->id]);
            }
        }

        \Log::info('Available rooms:', ['count' => count($availableRooms)]);

        $requestedQuantity = min((int)$validated['quantity'], count($availableRooms));
        $selectedRoomIds = array_slice($availableRooms, 0, $requestedQuantity);

        return response()->json([
            'room_ids' => $selectedRoomIds,
            'count' => count($selectedRoomIds),
            'total_available' => count($availableRooms)
        ]);
    } catch (\Exception $e) {
        \Log::error('Room availability error: ' . $e->getMessage(), [
            'trace' => $e->getTraceAsString()
        ]);
        return response()->json(['error' => $e->getMessage()], 500);
    }
}

public function getRoomsIsAvailable(Request $request)
{
    $startDate = $request->input('start_date');
    $endDate = $request->input('end_date');

    if (!$startDate || !$endDate) {
        return response()->json(['error' => 'start_date and end_date are required'], 422);
    }

    $roomTypes = RoomType::with('blockDates')->get();

    $seen = [];

    foreach ($roomTypes as $room) {
        $isBlocked = false;

        // Check block dates
        foreach ($room->blockDates as $block) {
            if (
                $block->start_date < $endDate &&
                $block->end_date > $startDate
            ) {
                $isBlocked = true;
                break;
            }
        }

        // Check bookings
        if (!$isBlocked) {
            $hasBooking = Booking::where('room_id', $room->id)
                ->where('status', '!=', 'cancelled')
                ->where('status', '!=', 'completed')
                ->where(function ($query) use ($startDate, $endDate) {
                    $query->where('check_in_date', '<', $endDate)
                          ->where('check_out_date', '>', $startDate);
                })
                ->exists();

            if (!$hasBooking) {
                $key = $room->name . '-' . $room->default_price;
                if (isset($seen[$key])) {
                    $seen[$key]['count']++;
                } else {
                    $seen[$key] = $room->toArray();
                    $seen[$key]['count'] = 1;
                }
            }
        }
    }

    return response()->json(array_values($seen));
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



