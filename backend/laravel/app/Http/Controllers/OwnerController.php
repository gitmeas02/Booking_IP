<?php

namespace App\Http\Controllers;

use App\Models\OwnerApplication;
use App\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class OwnerController extends Controller
{
    public function apply(Request $request)
    {
        $user = auth()->user();

        // Prevent multiple applications
        // No restriction: users can submit multiple applications regardless of status

        
        $validated = $request->validate([
            'property_type' => 'required|string',
            'fit_category' => 'required|string',
            'property_name' => 'required|string',
            'stars' => 'nullable|integer',
            'location.street' => 'required|string',
            'location.floor' => 'nullable|string',
            'location.country' => 'required|string',
            'location.city' => 'required|string',
            'location.zip_code' => 'required|string',
            'amenities' => 'array|exists:amenities,id', // Validate amenity IDs
            'services.breakfast' => 'required|boolean',
            'services.parking' => 'required|boolean',
            'rules.checkin_from' => 'required|string',
            'rules.checkin_to' => 'required|string',
            'rules.checkout_from' => 'required|string',
            'rules.checkout_to' => 'required|string',
            'rules.allow_pet' => 'required|boolean',
            'photos' => 'required|array|min:4',
            'photos.*' => 'image|max:2048',
            'payment.at_property' => 'required|boolean',
            'payment.online' => 'required|boolean',
            'payment.pteas_khmer' => 'required|boolean',
            'identity.first_name' => 'required|string',
            'identity.last_name' => 'required|string',
            'identity.middle_name' => 'nullable|string',
            'identity.first_name_id' => 'required|string',
            'identity.last_name_id' => 'required|string',
            'identity.middle_name_id' => 'nullable|string',
            'identity.email' => 'required|email',
            'identity.phone' => 'required|string',
            'identity.country' => 'required|string',
            'identity.address1' => 'required|string',
            'identity.address2' => 'nullable|string',
        ]);

        DB::beginTransaction();

        try {
            $application = OwnerApplication::create([
                'user_id' => $user->id,
                'property_type' => $validated['property_type'],
                'description' => $validated['fit_category'], // Map fit_category to description
                'property_name' => $validated['property_name'],
                'star_rating' => $validated['stars'],
                'is_pet_allowed' => $validated['rules']['allow_pet'], 
                'status' => 'pending'
            ]);

            $application->location()->create([
                'street' => $validated['location']['street'],
                'apartment_floor' => $validated['location']['floor'],
                'country' => $validated['location']['country'],
                'city' => $validated['location']['city'],
                'postcode' => $validated['location']['zip_code'],
            ]);

            if (!empty($validated['amenities'])) {
                $application->amenities()->sync($validated['amenities']); // Works with belongsToMany
            }

            $application->services()->create([
                'breakfast' => $validated['services']['breakfast'],
                'parking' => $validated['services']['parking'],
                'allow_pet' => $validated['rules']['allow_pet'],
            ]);

            $application->houseRules()->create([
                'checkin_from' => $validated['rules']['checkin_from'],
                'checkin_to' => $validated['rules']['checkin_to'],
                'checkout_from' => $validated['rules']['checkout_from'],
                'checkout_to' => $validated['rules']['checkout_to'],
            ]);
             
            if ($request->hasFile('photos')) {
                foreach ($request->file('photos') as $photo) {
                    $fileName = uniqid() . '.' . $photo->getClientOriginalExtension();
                    $fileContents = file_get_contents($photo->getRealPath());
                    Storage::disk('minio')->put('owner_applications_images/' . $fileName, $fileContents);
                    $application->photos()->create(['url' => 'owner_applications_images/' . $fileName]);
                }
            }
            $application->paymentMethods()->create([
                'credit_card_at_property' => $validated['payment']['at_property'],
                'online_payment' => $validated['payment']['online'],
                'use_platform_payments' => $validated['payment']['pteas_khmer'],
            ]);

            $application->personalInfo()->create([
                'first_name' => $validated['identity']['first_name'],
                'last_name' => $validated['identity']['last_name'],
                'middle_name' => $validated['identity']['middle_name'] ?? null,
                'email' => $validated['identity']['email'],
                'phone_number' => $validated['identity']['phone'],
                'country_region' => $validated['identity']['country'],
                'address_line1' => $validated['identity']['address1'],
                'address_line2' => $validated['identity']['address2'] ?? null,
                'id_first_name' => $validated['identity']['first_name_id'],
                'id_last_name' => $validated['identity']['last_name_id'],
                'id_middle_name' => $validated['identity']['middle_name_id'] ?? null,
            ]);

            DB::commit();

            return response()->json(['message' => 'Application submitted successfully.'], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Application failed', 'details' => $e->getMessage()], 500);
        }
    }

    public function logout(Request $request)
    {
        if (auth('sanctum')->check()) {
            $request->user()->currentAccessToken()->delete();
        }
        Auth::logout();
        return response()->json(['message' => 'Logged out successfully'], 200);
    }

    public function approve($id)
    {
        $application = OwnerApplication::findOrFail($id);
        $application->status = 'approved';
        $application->expires_at = now()->addYear();
        $application->save();

        $ownerRole = Role::where('name', 'owner')->first();
        $application->user->roles()->syncWithoutDetaching([$ownerRole->id]);

        return response()->json([
            'message' => 'Application approved. User is now an owner.',
            'user_roles' => $application->user->roles->pluck('name')
        ]);
    }
}
