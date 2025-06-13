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


 
