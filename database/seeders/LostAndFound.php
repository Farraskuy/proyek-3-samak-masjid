<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\LostAndFoundItem;

class LostAndFound extends Seeder
{
    public function run(): void
    {
        $userId = User::where('role_id', Role::where('name', 'Sarpras')->first()->id)->first()->id;

        LostAndFoundItem::insert([
            [
                'inputted_by_user_id' => $userId,
                'item_name' => 'Dompet Kulit Hitam',
                'description' => 'Berisi KTM dan kartu ATM BNI',
                'location_found' => 'Depan Musholla Wanita',
                // 'featured_image_url' => '',
                'status' => 'Tersedia',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'inputted_by_user_id' => $userId,
                'item_name' => 'Powerbank Xiaomi',
                'description' => 'Kapasitas 10000mAh, warna hitam',
                'location_found' => 'Ruang Kelas Masjid',
                // 'featured_image_url' => '',
                'status' => 'Tersedia',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'inputted_by_user_id' => $userId,
                'item_name' => 'Kacamata Minus',
                'description' => 'Frame hitam, lensa minus 3',
                'location_found' => 'Area Wudhu Pria',
                // 'featured_image_url' => '',
                'status' => 'Tersedia',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'inputted_by_user_id' => $userId,
                'item_name' => 'Botol Minum Stainless Steel',
                'description' => 'Berwarna biru dengan tutup hitam',
                'location_found' => 'Area Wudhu Wanita',
                // 'featured_image_url' => '',
                'status' => 'Tersedia',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
