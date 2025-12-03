<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Admin (Super Admin & Admin merged)
        $adminRole = \App\Models\Role::where('name', 'Admin')->first();
        if ($adminRole) {
            User::updateOrCreate(
                ['username' => 'admin'],
                [
                    'role_id' => $adminRole->id,
                    'full_name' => 'Administrator',
                    'email' => 'admin@samak.com',
                    'password' => Hash::make('password123'),
                    'phone_number' => '081234567891'
                ]
            );
        }

        // 2. Humas
        $humasRole = \App\Models\Role::where('name', 'Humas')->first();
        if ($humasRole) {
            User::updateOrCreate(
                ['username' => 'humas'],
                [
                    'role_id' => $humasRole->id,
                    'full_name' => 'Staf Humas',
                    'email' => 'humas@samak.com',
                    'password' => Hash::make('password123'),
                    'phone_number' => '081234567893'
                ]
            );
        }

        // 3. Bendahara
        $bendaharaMRole = \App\Models\Role::where('name', 'Bendahara Pemasukan')->first();
        if ($bendaharaMRole) {
            User::updateOrCreate(
                ['username' => 'bendaharaPemasukan'],
                [
                    'role_id' => $bendaharaMRole->id,
                    'full_name' => 'Bendahara Masjid',
                    'email' => 'bendaharaM@samak.com',
                    'password' => Hash::make('password123'),
                    'phone_number' => '081234567894'
                ]
            );
        }

        $bendaharaKRole = \App\Models\Role::where('name', 'Bendahara Pengeluaran')->first();
        if ($bendaharaKRole) {
            User::updateOrCreate(
                ['username' => 'bendaharaPengeluaran'],
                [
                    'role_id' => $bendaharaKRole->id,
                    'full_name' => 'Bendahara Masjid',
                    'email' => 'bendaharaK@samak.com',
                    'password' => Hash::make('password123'),
                    'phone_number' => '081234567894'
                ]
            );
        }

        // 4. Sarpras
        $sarprasRole = \App\Models\Role::where('name', 'Sarpras')->first();
        if ($sarprasRole) {
            User::updateOrCreate(
                ['username' => 'sarpras'],
                [
                    'role_id' => $sarprasRole->id,
                    'full_name' => 'Staf Sarpras',
                    'email' => 'sarpras@samak.com',
                    'password' => Hash::make('password123'),
                    'phone_number' => '081234567895'
                ]
            );
        }

        // 5. Jamaah
        $jamaahRole = \App\Models\Role::where('name', 'Jamaah')->first();
        if ($jamaahRole) {
            User::updateOrCreate(
                ['username' => 'jamaah'],
                [
                    'role_id' => $jamaahRole->id,
                    'full_name' => 'Jamaah Umum',
                    'email' => 'jamaah@samak.com',
                    'password' => Hash::make('password123'),
                    'phone_number' => '081234567896'
                ]
            );
        }

        // 6. Koordinator Humas (BARU)
        $koorHumasRole = \App\Models\Role::where('name', 'Koordinator Humas')->first();
        if ($koorHumasRole) {
            User::updateOrCreate(
                ['username' => 'koordinator_humas'],
                [
                    'role_id' => $koorHumasRole->id,
                    'full_name' => 'Koordinator Humas',
                    'email' => 'koor_humas@samak.com',
                    'password' => Hash::make('password123'),
                    'phone_number' => '081234567897'
                ]
            );
        }
    }
}