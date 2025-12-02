<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\GalleryAlbum;

class GalleryAlbumSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Album 1
        GalleryAlbum::create([
            'album_name' => 'Kegiatan Mentoring Gabungan',
            'description' => '',   
            'created_by' => 1,
            'created_at' => now(),
        ]);

        // Album 2
        GalleryAlbum::create([
            'album_name' => 'Kegiatan Selama Ramadhan 1444 H',
            'description' => '',
            'created_by' => 1,
            'created_at' => now(),
        ]);

        // Album 3
        GalleryAlbum::create([
            'album_name' => 'Kegiatan Belajar Tartilil Quran Bersama Ustadz Syaepul Manan',
            'description' => '',
            'created_by' => 1,
            'created_at' => now(),
        ]);
    }
}
