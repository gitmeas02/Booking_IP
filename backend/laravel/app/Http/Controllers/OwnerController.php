<?php

namespace App\Http\Controllers;

use App\Models\OwnerApplication;
use App\Models\Role;
use DB;
use Illuminate\Http\Request;

class OwnerController extends Controller
{
    public function apply(Request $request)
    {
        $user = auth()->user();

        if (!$user->roles()->where('name', 'user')->exists()) {
            return response()->json(['message' => 'Only users with the "user" role can apply to become owners.'], 403);
        }

        // Validate only fields that exist in your DB schema or that you need for relationships
        $validated = $request->validate([
            'property_name' => 'sometimes|string',
            'description' => 'sometimes|string|nullable',
            'stars' => 'nullable|integer',
            'is_pet_allowed' => 'sometimes|boolean',

            // Optional identity fields - add if you want to store related data later
            'identity.is_individual_owner' => 'sometimes|boolean',
            'identity.first_name' => 'sometimes|string',
            'identity.last_name' => 'sometimes|string',
            'identity.email' => 'sometimes|email',
            'identity.phone' => 'sometimes|string',
        ]);

        DB::beginTransaction();

        try {
            // Insert only columns that exist in DB
            $application = OwnerApplication::create([
                'user_id' => $user->id,
                'property_name' => $validated['property_name'] ?? null,
                'description' => $validated['description'] ?? null,
                'star_rating' => $validated['stars'] ?? null,
                'is_pet_allowed' => $validated['is_pet_allowed'] ?? false,
                'status' => 'pending',
            ]);

            // Optionally assign 'owner' role immediately after submission
            $ownerRole = Role::where('name', 'owner')->first();
            if ($ownerRole) {
                $user->roles()->syncWithoutDetaching([$ownerRole->id]);
            }

            DB::commit();

            return response()->json(['message' => 'Application submitted and owner role granted successfully.'], 201);
        } catch (\Exception $e) {
            DB::rollBack();

            \Log::error('Owner application failed: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);

            return response()->json(['error' => 'Application failed', 'details' => $e->getMessage()], 500);
        }
    }
}
