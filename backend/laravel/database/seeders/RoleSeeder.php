<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //Roles
        foreach (['user','company','owner','worker'] as $role) {
            Role::firstOrCreate(['name' => $role]);
        }
    }
}
