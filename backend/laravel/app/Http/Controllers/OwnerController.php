<?php

namespace App\Http\Controllers;

use App\Models\ApplicationLocation;
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
            //OwnerApplication Model
            'property_type' => 'required|string', // property_type  page1
            //OwnerApplication Model
            'property_name' => 'required|string', // page 3
            //OwnerApplication Model
            'stars' => 'nullable|integer',
            'location.street' => 'required|string', //location from  
            'location.floor' => 'nullable|string', // page 2
            'location.country' => 'required|string',
            'location.city' => 'required|string',
            'location.zip_code' => 'required|string',

            'amenities' => 'array|exists:amenities,id', // Validate amenity IDs page 4

            'breakfast' => 'required|boolean',
            'parking' => 'required|boolean', // page 5

            'houseRules.checkin_from' => 'required|string',
            'houseRules.checkin_to' => 'required|string',
            'houseRules.checkout_from' => 'required|string',
            'houseRules.checkout_to' => 'required|string', // page 6
            'houseRules.allow_pet' => 'required|boolean',
            'houseRules.childrenAllowed' => 'required|boolean',

            'photos' => 'required|array|min:4',  //page 7
            'photos.*' => 'image|max:48128',

            'paymentOptions.at_property' => 'required|boolean',
            'paymentOptions.online' => 'required|boolean', //page 8

            'identity.full_name' => 'required|string', // last page            
            'identity.email' => ['required', 'email', function($attribute, $value, $fail) use ($user){
                    if ($value !== $user->email) {
                        $fail('The identity email must match your account email');
                    }
                }],
            'identity.phone' => 'required|string',
            'identity.country' => 'required|string',
            'identity.address1' => 'required|string',
            'identity.address2' => 'nullable|string',
            'identity.first_name_id' => 'required|string',
            'identity.last_name_id' => 'required|string',
            'identity.middle_name_id' => 'nullable|string',
        ]);

        DB::beginTransaction();

        try {
            $application = OwnerApplication::create([
                'user_id' => $user->id,
                'property_type' => $validated['property_type'],
                'property_name' => $validated['property_name'],
                'star_rating' => $validated['stars'],
                'status' => 'pending'
            ]);
          

            ApplicationLocation::create([ //
                'application_id' => $application->id,
                'street' => $validated['location']['street'],
                'apartment_floor' => $validated['location']['floor'],
                'country' => $validated['location']['country'],
                'city' => $validated['location']['city'],
                'postcode' => $validated['location']['zip_code'],
            ]);

            if (!empty($validated['amenities'])) {
              $application->amenities()->sync($validated['amenities']);
            }

            $application->services()->create([
                'breakfast' => $validated['breakfast'],
                'parking' => $validated['parking'],
            ]);

            $application->houseRules()->create([
                'checkin_from' => $validated['houseRules']['checkin_from'],
                'checkin_to' => $validated['houseRules']['checkin_to'],
                'checkout_from' => $validated['houseRules']['checkout_from'],
                'checkout_to' => $validated['houseRules']['checkout_to'],
                'allow_pet' => $validated['houseRules']['allow_pet'],
                'is_childrenAllowed' => $validated['houseRules']['childrenAllowed'], // ✅ FIXED
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
                'credit_card_at_property' => $validated['paymentOptions']['at_property'],
                'online_payment' => $validated['paymentOptions']['online'],
            ]);

            $application->personalInfo()->create([
                'full_name' => $validated['identity']['full_name'],
                'phone_number' => $validated['identity']['phone'],
                'email' => $validated['identity']['email'],
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


        $ownerRole = Role::where('name', 'owner')->firstOrFail();

    // Update user's current role
        $application->user->current_role_id = $ownerRole->id;
        $application->user->save(); 
        $application->user->roles()->syncWithoutDetaching([$ownerRole->id]);
        $application->save();
        return response()->json([
            'message' => 'Application approved. User is now an owner.',
            'user_roles' => $application->user->roles->pluck('name')
        ]);
    }
}
