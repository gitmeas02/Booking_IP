<?php

namespace App\Http\Controllers\Images;

use App\Http\Controllers\Controller;
use App\Models\ApplicationPhoto;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ImageController extends Controller
{
public function getUserApplicationImages($applicationId)
    {
        $MINIO_ENDPOINT = 'http://localhost:9000/ownerimages/';

        $user = Auth::user();

        $application = $user->ownerApplication()
            ->where('id', $applicationId)
            ->with('photos') // eager load photos
            ->first();

        if (!$application) {
            return response()->json(['message' => 'Application not found'], 404);
        }

        // Build full image URLs
        $images = $application->photos->map(function ($photo) use ($MINIO_ENDPOINT) {
            return $MINIO_ENDPOINT . $photo->url; // assuming `url` is stored as `/ownerimages/owner_applications_images/683d261743093.png`
        });

        return response()->json([
            'application_id' => $applicationId,
            'images' => $images,
        ]);
    }
}
