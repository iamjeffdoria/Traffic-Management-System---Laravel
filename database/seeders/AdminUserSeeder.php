<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@tms.local',
            'password' => Hash::make('superadmin123'),
            'role' => 'superadmin',
        ]);

        User::create([
            'name' => 'Potpot Admin',
            'email' => 'potpot@tms.local',
            'password' => Hash::make('potpot123'),
            'role' => 'potpot_admin',
        ]);

        User::create([
            'name' => 'Tricycle Admin',
            'email' => 'tricycle@tms.local',
            'password' => Hash::make('tricycle123'),
            'role' => 'tricycle_admin',
        ]);
    }
}