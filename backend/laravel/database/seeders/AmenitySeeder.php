<?php

namespace Database\Seeders;

use App\Models\Amenity;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AmenitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $amenities = [
            ['amenity_name' => 'Free WiFi', 'icon_url' => '/icons/wifi.svg'],
            ['amenity_name' => 'Air Conditioning', 'icon_url' => '/icons/ac.svg'],
            ['amenity_name' => 'Swimming Pool', 'icon_url' => '/icons/pool.svg'],
            ['amenity_name' => 'Fitness Center', 'icon_url' => '/icons/gym.svg'],
            ['amenity_name' => 'Restaurant', 'icon_url' => '/icons/restaurant.svg'],
            ['amenity_name' => 'Parking', 'icon_url' => '/icons/parking.svg'],
            ['amenity_name' => 'Pet Friendly', 'icon_url' => '/icons/pet.svg'],
            ['amenity_name' => 'Room Service', 'icon_url' => '/icons/room_service.svg'],
            ['amenity_name' => 'Spa', 'icon_url' => '/icons/spa.svg'],
            ['amenity_name' => '24/7 Reception', 'icon_url' => '/icons/reception.svg'],
        ];

        foreach ($amenities as $amenity) {
            Amenity::firstOrCreate(['amenity_name' => $amenity['amenity_name']], $amenity);
        }
    }
}
