<?php

namespace App\Http\Controllers;

use App\Models\OwnerApplication;
use App\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class OwnerController extends Controller
{
    public function apply(Request $request)
    {
        $request->validate([
            'property_type'   => 'required|string|max:255',
            'property_name'   => 'required|string|max:255',
            'description'     => 'nullable|string',
            'location'        => 'required|string|max:255',
            'star_rating'     => 'nullable|integer|min:1|max:5',
            'is_pet_allowed'  => 'required|boolean',
        ]);

        $user = Auth::user();

        // Prevent duplicate pending/approved applications
        $existing = OwnerApplication::where('user_id', $user->id)
            ->where('property_name', $request->property_name)
            ->where('location', $request->location)
            ->whereIn('status', ['pending', 'approved'])
            ->first();

        if ($existing) {
            return response()->json(['message' => 'You already have a pending or approved and the same location onapplication.Please different info'], 409);
        }

        // Create owner application
        $application = OwnerApplication::create([
            'user_id'         => auth()->id(),
            'property_type'   => $request->property_type,
            'property_name'   => $request->property_name,
            'description'     => $request->description,
            'location'        => $request->location,
            'star_rating'     => $request->star_rating,
            'is_pet_allowed'  => $request->is_pet_allowed,
            'status'          => 'pending',
        ]);

        return response()->json([
            'message' => 'Owner application submitted successfully.',
            'application' => $application
        ], 201);
    }

    // Optional: approve and assign role
    public function approve($id)
    {
        $application = OwnerApplication::findOrFail($id);
        $application->status = 'approved';
        $application->expires_at = now()->addYear();
        $application->save();

        // Assign owner role
        $ownerRole = Role::where('name', 'owner')->first();
        $application->user->roles()->syncWithoutDetaching([$ownerRole->id]);

        return response()->json([
            'message' => 'Application approved. User is now an owner.',
            'user_roles' => $application->user->roles->pluck('name')
        ]);
    }
}
