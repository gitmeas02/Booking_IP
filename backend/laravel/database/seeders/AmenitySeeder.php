<?php

namespace Database\Seeders;

use App\Models\Amenity;
use App\Models\AmenityGroup;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
class AmenitySeeder extends Seeder
{
    public function run(): void
    {
        $facilitiesGrouped = [
            [
                'category' => 'Room',
                'items' => [
                    'Air conditioning', 'Heating', 'Free Wi-Fi', 'Flat-screen TV', 'Safe',
                    'Mini fridge', 'Microwave', 'Coffee/tea maker', 'Balcony or terrace',
                ],
            ],
            [
                'category' => 'Bathroom',
                'items' => [
                    'Private bathroom', 'Shower', 'Bathtub', 'Towels', 'Hairdryer',
                    'Toiletries', 'Hot water',
                ],
            ],
            [
                'category' => 'Kitchen',
                'items' => [
                    'Kitchen', 'Kitchenette', 'Stove', 'Oven', 'Toaster', 'Dishwasher',
                ],
            ],
            [
                'category' => 'Leisure & Wellness',
                'items' => [
                    'Swimming pool', 'Hot tub', 'Spa', 'Sauna', 'Fitness center', 'Beachfront',
                ],
            ],
            [
                'category' => 'Property',
                'items' => [
                    '24-hour front desk', 'Daily housekeeping', 'Elevator', 'Free parking', 'Pet-friendly',
                ],
            ],
            [
                'category' => 'Business',
                'items' => [
                    'Business center', 'Meeting room', 'High-speed internet',
                ],
            ],
            [
                'category' => 'Food & Beverage',
                'items' => [
                    'Restaurant', 'Bar', 'Room service', 'Breakfast included', 'Mini-market',
                ],
            ],
            [
                'category' => 'Accessibility',
                'items' => [
                    'Wheelchair accessible', 'Accessible bathroom',
                ],
            ],
            [
                'category' => 'Transportation',
                'items' => [
                    'Airport shuttle', 'Car rental', 'EV charging station',
                ],
            ],
            [
                'category' => 'Family & Kids',
                'items' => [
                    'Baby cot', 'Kids’ play area', 'Board games',
                ],
            ],
            [
                'category' => 'Pet',
                'items' => [
                    'Pet bowls', 'Pet bed',
                ],
            ],
            [
                'category' => 'Safety',
                'items' => [
                    'Smoke alarms', 'Fire extinguishers', 'CCTV',
                ],
            ],
        ];

        foreach ($facilitiesGrouped as $group) {
            $amenityGroup = AmenityGroup::create(['name' => $group['category']]);

            foreach ($group['items'] as $item) {
                Amenity::create([
                    'amenity_name' => $item,
                    'group_id' => $amenityGroup->id,
                ]);
            }
        }
    }
}