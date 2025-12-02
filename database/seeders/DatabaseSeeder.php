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
        $this->call([
            RoleSeeder::class,
            BankAccountSeeder::class,
            UsersSeeder::class,
            FoundItemSeeder::class,
            LostItemSeeder::class,
            PostinganSeeder::class,
            ConsultationSeeder::class,
            StaticPageSeeder::class,
            GalleryAlbumSeeder::class,
            GalleryPhotoSeeder::class,
            JadwalKegiatanSeeder::class,
        ]);
    }
}
