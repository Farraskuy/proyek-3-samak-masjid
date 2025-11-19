<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash; // Gunakan Facade Hash biar lebih rapi

class UsersSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Super Admin
        User::updateOrCreate(
            ['username' => 'superadmin'], 
            [
                'role' => 'super admin',
                'full_name' => 'Super Administrator',
                'email' => 'superadmin@samak.com',
                'password' => Hash::make('password123'), 
                'phone_number' => '081234567890'
            ]
        );

        // 2. Admin
        User::updateOrCreate(
            ['username' => 'admin'],
            [
                'role' => 'admin',
                'full_name' => 'Administrator',
                'email' => 'admin@samak.com',
                'password' => Hash::make('password123'),
                'phone_number' => '081234567891'
            ]
        );

        // 3. Ustadz
        User::updateOrCreate(
            ['username' => 'ustadz'],
            [
                'role' => 'ustadz',
                'full_name' => 'Ustadz Ahmad',
                'email' => 'ustadz@samak.com',
                'password' => Hash::make('password123'),
                'phone_number' => '081234567892'
            ]
        );
    }
}