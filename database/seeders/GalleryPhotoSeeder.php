<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\GalleryPhoto;

class GalleryPhotoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        /* COVER & FOTO ALBUM 1 : Kegiatan Mentoring Gabungan */

        // COVER
        GalleryPhoto::create([
            'album_id' => 1,
            'image_url' => 'gallery/covers/MentoringGabunganCover.jpeg',
            'caption' => 'Cover Album',
            'uploaded_by' => 1,
            'uploaded_at' => now(),
        ]);

        // FOTO ISI
        GalleryPhoto::create([
            'album_id' => 1,
            'image_url' => 'gallery/photos/MentoringGabungan1.jpeg',
            'caption' => 'Sesi Materi',
            'uploaded_by' => 1,
            'uploaded_at' => now(),
        ]);

        GalleryPhoto::create([
            'album_id' => 1,
            'image_url' => 'gallery/photos/MentoringGabungan2.jpeg',
            'caption' => 'Sesi Pembacaan Al-Quran',
            'uploaded_by' => 1,
            'uploaded_at' => now(),
        ]);

        GalleryPhoto::create([
            'album_id' => 1,
            'image_url' => 'gallery/photos/MentoringGabungan3.jpeg',
            'caption' => '',
            'uploaded_by' => 1,
            'uploaded_at' => now(),
        ]);


        /* COVER & FOTO ALBUM 2 : Kegiatan Selama Ramadhan 1444 H */

        // COVER
        GalleryPhoto::create([
            'album_id' => 2,
            'image_url' => 'gallery/covers/RamadhanCover.jpeg',
            'caption' => 'Cover Album',
            'uploaded_by' => 1,
            'uploaded_at' => now(),
        ]);


        // FOTO ISI
        GalleryPhoto::create([
            'album_id' => 2,
            'image_url' => 'gallery/photos/Ramadhan1.jpeg',
            'caption' => 'Mengaji Tafsir',
            'uploaded_by' => 1,
            'uploaded_at' => now(),
        ]);

        GalleryPhoto::create([
            'album_id' => 2,
            'image_url' => 'gallery/photos/Ramadhan2.jpeg',
            'caption' => '',
            'uploaded_by' => 1,
            'uploaded_at' => now(),
        ]);

        GalleryPhoto::create([
            'album_id' => 2,
            'image_url' => 'gallery/photos/Ramadhan3.jpeg',
            'caption' => 'Mengaji Tafsir Bersama Ustadz Iwan Sanusi',
            'uploaded_by' => 1,
            'uploaded_at' => now(),
        ]);

        GalleryPhoto::create([
            'album_id' => 2,
            'image_url' => 'gallery/photos/Ramadhan4.jpeg',
            'caption' => 'Buka Bersama',
            'uploaded_by' => 1,
            'uploaded_at' => now(),
        ]);

        GalleryPhoto::create([
            'album_id' => 2,
            'image_url' => 'gallery/photos/Ramadhan5.jpeg',
            'caption' => '',
            'uploaded_by' => 1,
            'uploaded_at' => now(),
        ]);

        GalleryPhoto::create([
            'album_id' => 2,
            'image_url' => 'gallery/photos/Ramadhan6.jpeg',
            'caption' => '',
            'uploaded_by' => 1,
            'uploaded_at' => now(),
        ]);

        GalleryPhoto::create([
            'album_id' => 2,
            'image_url' => 'gallery/photos/Ramadhan7.jpeg',
            'caption' => '',
            'uploaded_by' => 1,
            'uploaded_at' => now(),
        ]);


        /* COVER & FOTO ALBUM 3 : Kegiatan Belajar Tartilil Quran Bersama Ustadz Syaepul Manan */

        // COVER
        GalleryPhoto::create([
            'album_id' => 3,
            'image_url' => 'gallery/covers/BelajarTartililQuranBersamaUstadzCover.jpeg',
            'caption' => 'Cover Album',
            'uploaded_by' => 1,
            'uploaded_at' => now(),
        ]);

        // FOTO ISI
        GalleryPhoto::create([
            'album_id' => 3,
            'image_url' => 'gallery/photos/BelajarTartililQuranBersamaUstadz1.jpeg',
            'caption' => 'Sesi materi',
            'uploaded_by' => 1,
            'uploaded_at' => now(),
        ]);

        GalleryPhoto::create([
            'album_id' => 3,
            'image_url' => 'gallery/photos/BelajarTartililQuranBersamaUstadz2.jpeg',
            'caption' => '',
            'uploaded_by' => 1,
            'uploaded_at' => now(),
        ]);

        GalleryPhoto::create([
            'album_id' => 3,
            'image_url' => 'gallery/photos/BelajarTartililQuranBersamaUstadz3.jpeg',
            'caption' => '',
            'uploaded_by' => 1,
            'uploaded_at' => now(),
        ]);
    }
}
