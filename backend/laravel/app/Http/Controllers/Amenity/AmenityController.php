<?php

namespace App\Http\Controllers\Amenity;

use App\Http\Controllers\Controller;
use App\Models\Amenity;
use Illuminate\Http\Request;

class AmenityController extends Controller
{
   public function index()
    {
        $amenities = Amenity::all();
        return response()->json([
            'status' => 'success',
            'data' => $amenities
        ]);
    }
}
