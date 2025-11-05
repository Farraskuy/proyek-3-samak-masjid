<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\LostAndFoundItem;

class LostAndFound extends Seeder
{
    public function run(): void
    {
        $admin = User::where('role', 'admin')->first();

        $adminId = $admin ? $admin->id : 1;

        LostAndFoundItem::insert([
            [
                'inputted_by_admin_id' => $adminId,
                'item_name' => 'Dompet Kulit Hitam',
                'description' => 'Berisi KTM dan kartu ATM BNI',
                'location_found' => 'Depan Musholla Wanita',
                'featured_image_url' => '',
                'status' => 'Tersedia',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'inputted_by_admin_id' => $adminId,
                'item_name' => 'Powerbank Xiaomi',
                'description' => 'Kapasitas 10000mAh, warna hitam',
                'location_found' => 'Ruang Kelas Masjid',
                'featured_image_url' => '',
                'status' => 'Tersedia',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'inputted_by_admin_id' => $adminId,
                'item_name' => 'Kacamata Minus',
                'description' => 'Frame hitam, lensa minus 3',
                'location_found' => 'Area Wudhu Pria',
                'featured_image_url' => '',
                'status' => 'Tersedia',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}