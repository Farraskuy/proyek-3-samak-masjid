<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\LostItem;
use App\Models\ItemCategory;

class LostItemSeeder extends Seeder
{
    public function run(): void
    {
        $adminRole = \App\Models\Role::where('name', 'admin')->first();
        $admin = $adminRole
            ? User::where('role_id', $adminRole->id)->first()
            : User::first();
        $adminId = $admin ? $admin->id : 1;
        $kategoriDokumen = ItemCategory::where('slug', 'dokumen')->first()?->id ?? 1;
        $kategoriAksesoris = ItemCategory::where('slug', 'aksesoris')->first()?->id ?? 2;
        $kategoriElektronik = ItemCategory::where('slug', 'elektronik')->first()?->id ?? 3;
        $today = now();
        $twoDaysAgo = $today->copy()->subDays(2);
        $fiveDaysAgo = $today->copy()->subDays(5);

        LostItem::insert([
            [
                'reported_by_admin_id' => $adminId,
                'category_id' => $kategoriDokumen,
                'item_name' => 'Kartu Tanda Mahasiswa (KTM)',
                'description' => 'KTM Universitas XYZ, nama: Ahmad Fauzi, NIM: 123456789',
                'location_lost' => 'Area Shaf Depan Masjid',
                'lost_at' => $fiveDaysAgo->toDateString(),
                'expiry_date' => $fiveDaysAgo->copy()->addDays(30)->toDateString(),
                'status' => 'aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'reported_by_admin_id' => $adminId,
                'category_id' => $kategoriAksesoris,
                'item_name' => 'Gelang Tangan Perak',
                'description' => 'Gelang ukiran bunga, ukuran sedang, tidak ada pengait khusus',
                'location_lost' => 'Loker Jamaah Wanita',
                'lost_at' => $twoDaysAgo->toDateString(),
                'expiry_date' => $twoDaysAgo->copy()->addDays(30)->toDateString(),
                'status' => 'aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'reported_by_admin_id' => $adminId,
                'category_id' => $kategoriElektronik,
                'item_name' => 'Flashdisk SanDisk 64GB',
                'description' => 'Warna hitam, terdapat stiker label "Tugas Akhir"',
                'location_lost' => 'Meja Baca di Perpustakaan Masjid',
                'lost_at' => $today->toDateString(),
                'expiry_date' => $today->copy()->addDays(30)->toDateString(),
                'status' => 'aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
