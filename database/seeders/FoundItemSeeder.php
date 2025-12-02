<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use App\Models\FoundItem;

class FoundItemSeeder extends Seeder
{
    public function run(): void
    {
        $sarprasRole = Role::where('name', 'Sarpras')->first();
        $userId = $sarprasRole
            ? User::where('role_id', $sarprasRole->id)->first()?->id
            : User::where('role', 'admin')->first()?->id ?? 1;

        FoundItem::insert([
            [
                'inputted_by_user_id' => $userId,
                'item_name' => 'Dompet Kulit Hitam',
                'description' => 'Berisi KTM dan kartu ATM BNI',
                'location_found' => 'Depan Musholla Wanita',
                'category' => 'dokumen',
                'status' => 'Tersedia',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'inputted_by_admin_id' => $userId,
                'item_name' => 'Powerbank Xiaomi',
                'description' => 'Kapasitas 10000mAh, warna hitam',
                'location_found' => 'Ruang Kelas Masjid',
                'category' => 'elektronik',
                'status' => 'Tersedia',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
