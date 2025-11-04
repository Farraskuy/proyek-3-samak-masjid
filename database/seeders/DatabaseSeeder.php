<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create Super Admin
        User::create([
            'role' => 'super admin',
            'username' => 'superadmin',
            'full_name' => 'Super Administrator',
            'email' => 'superadmin@samak.com',
            'password' => password_hash('password123', PASSWORD_DEFAULT),
            'phone_number' => '081234567890'
        ]);

        // Create Admin
        User::create([
            'role' => 'admin',
            'username' => 'admin',
            'full_name' => 'Administrator',
            'email' => 'admin@samak.com',
            'password' => password_hash('password123', PASSWORD_DEFAULT),
            'phone_number' => '081234567891'
        ]);

        // Create Ustadz
        User::create([
            'role' => 'ustadz',
            'username' => 'ustadz',
            'full_name' => 'Ustadz Ahmad',
            'email' => 'ustadz@samak.com',
            'password' => password_hash('password123', PASSWORD_DEFAULT),
            'phone_number' => '081234567892'
        ]);

        $this->call([LostandFound::class,]);
    }
}
