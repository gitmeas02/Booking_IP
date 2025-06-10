<?php

namespace Database\Seeders;

use App\Models\Amenity;
use App\Models\ApplicationLocation;
use App\Models\OwnerApplication;
use App\Models\Role;
use App\Models\User;
use DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Storage;

class OwnerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
     public function run(): void
    {
        DB::transaction(function () {
            // Create or get role
            $ownerRole = Role::firstOrCreate(['name' => 'owner']);

            // Create test user
            $user = User::factory()->create([
                'name' => 'Owner Test User',
                'email' => 'owner@example.com',
                'password' => bcrypt('password'),
            ]);

            // Create application
            $application = OwnerApplication::create([
                'user_id' => $user->id,
                'property_type' => 'hotel',
                'property_name' => 'Seaside Paradise Hotel',
                'star_rating' => 4,
                'status' => 'approved',
                'expires_at' => now()->addYear(),
            ]);

            // Location
            ApplicationLocation::create([
                'application_id' => $application->id,
                'street' => 'Beachfront Ave',
                'apartment_floor' => '3rd Floor',
                'country' => 'Greece',
                'city' => 'Santorini',
                'postcode' => '84700',
            ]);

            // Attach amenities (create if none exist)
            $amenities = Amenity::inRandomOrder()->limit(3)->pluck('id')->toArray();
            if (empty($amenities)) {
                $amenities = [
                    Amenity::create(['name' => 'Wi-Fi'])->id,
                    Amenity::create(['name' => 'Air Conditioning'])->id,
                    Amenity::create(['name' => 'Pool'])->id,
                ];
            }
            $application->amenities()->sync($amenities);

            // Services
            $application->services()->create([
                'breakfast' => true,
                'parking' => true,
            ]);

            // House Rules
            $application->houseRules()->create([
                'checkin_from' => '14:00',
                'checkin_to' => '18:00',
                'checkout_from' => '08:00',
                'checkout_to' => '12:00',
                'allow_pet' => true,
                'is_childrenAllowed' => true,
            ]);

            // Photos (use placeholder URLs)
            for ($i = 1; $i <= 4; $i++) {
                $application->photos()->create([
                    'url' => "owner_applications_images/dummy{$i}.jpg",
                ]);
                // Optionally upload dummy image to MinIO if required
                Storage::disk('minio')->put("owner_applications_images/dummy{$i}.jpg", 'placeholder content');
            }

            // Payment Methods
            $application->paymentMethods()->create([
                'at_property' => true,
                'online_payment' => true,
            ]);

            // Personal Info
            $application->personalInfo()->create([
                'full_name' => 'Test Owner',
                'phone_number' => '+302101234567',
                'email' => 'owner@example.com',
                'country_region' => 'Greece',
                'address_line1' => '123 Cycladic St',
                'address_line2' => 'Suite 202',
                'id_first_name' => 'Test',
                'id_last_name' => 'Owner',
                'id_middle_name' => 'Middle',
            ]);

            // Assign owner role to user
            $user->roles()->syncWithoutDetaching([$ownerRole->id]);
        });
    }
}
