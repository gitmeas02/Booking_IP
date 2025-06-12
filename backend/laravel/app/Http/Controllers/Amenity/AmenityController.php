<?php

namespace App\Http\Controllers\Amenity;

use App\Http\Controllers\Controller;
use App\Models\Amenity;
use App\Models\AmenityGroup;
use Illuminate\Http\Request;

class AmenityController extends Controller
{
   public function index()
    {
        $amenities = AmenityGroup::with('amenities')->get();

        //  $amenities=AmenityGroup::all();
        return response()->json([
            'status' => 'success',
            'data' => $amenities
        ]);
    }
}
