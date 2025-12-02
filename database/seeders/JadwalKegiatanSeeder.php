<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\JadwalKegiatan;
use App\Models\EventTamu;
use Illuminate\Support\Carbon;

class JadwalKegiatanSeeder extends Seeder
{
    public function run(): void
    {
        /* EVENT KEMARIN (Past Event) */

        $yesterday = Carbon::yesterday();

        $eventYesterday = JadwalKegiatan::create([
            'event_name' => 'Kajian Inspiratif',
            'theme' => 'Jika ini Ramadhan Terakhir',
            'poster' => 'posters/KajianSenin.png',
            'location' => 'Masjid Luqmanul Hakim POLBAN',
            'start_time' => $yesterday->copy()->setTime(16, 00),
            'end_time'   => $yesterday->copy()->setTime(17, 30),
            'is_recurring' => false,
            'requires_registration' => false,
            'is_have_tamu_undangan' => true,
            'created_by' => 1,
            'created_at' => now(),
        ]);

        EventTamu::create([
            'event_id' => $eventYesterday->event_id,
            'nama_tamu' => 'Ustadz Muhammad'
        ]);


        /* EVENT HARI INI */

        $today = Carbon::today();

        $eventToday = JadwalKegiatan::create([
            'event_name' => 'Tartil Al-Quran',
            'theme' => 'Belajar Tartil Al-Quran "Hukum Mad"',
            'poster' => 'posters/KajianSelasa.png',
            'location' => 'Masjid Luqmanul Hakim POLBAN',
            'start_time' => $today->copy()->setTime(18, 00),
            'end_time'   => $today->copy()->setTime(19, 00),
            'is_recurring' => false,
            'requires_registration' => false,
            'is_have_tamu_undangan' => true,
            'created_by' => 1,
            'created_at' => now(),
        ]);

        EventTamu::create([
            'event_id' => $eventToday->event_id,
            'nama_tamu' => 'Ustadz Syaepul Manan'
        ]);


        /* EVENT BESOK */

        $tomorrow = Carbon::tomorrow();

        $eventTomorrow = JadwalKegiatan::create([
            'event_name' => 'Pembenahan Diri Menuju Pribadi Qurani',
            'theme' => 'Lantern of Hope: Raih Cita Raih Pahala di Masjid LH',
            'poster' => 'posters/KajianRabu.png',
            'location' => 'Masjid Luqmanul Hakim POLBAN',
            'start_time' => $tomorrow->copy()->setTime(15, 30),
            'end_time'   => $tomorrow->copy()->setTime(17, 30),
            'is_recurring' => false,
            'requires_registration' => false,
            'is_have_tamu_undangan' => true,
            'created_by' => 1,
            'created_at' => now(),
        ]);

        EventTamu::create([
            'event_id' => $eventTomorrow->event_id,
            'nama_tamu' => 'Muhammad Alfi Saeful Basyari'
        ]);


        /* EVENT LUSA*/

        $dayAfterTomorrow = Carbon::today()->addDays(2);

        $eventDAT = JadwalKegiatan::create([
            'event_name' => 'Ramadhan Makin Produktif',
            'theme' => 'Membuat Bulan Ramadhan Lebih Bermakna',
            'poster' => 'posters/KajianKamis.png',
            'location' => 'Masjid Luqmanul Hakim POLBAN',
            'start_time' => $dayAfterTomorrow->copy()->setTime(15, 30),
            'end_time'   => $dayAfterTomorrow->copy()->setTime(17, 00),
            'is_recurring' => false,
            'requires_registration' => false,
            'is_have_tamu_undangan' => true,
            'created_by' => 1,
            'created_at' => now(),
        ]);

        EventTamu::create([
            'event_id' => $eventDAT->event_id,
            'nama_tamu' => 'Ustadz Rieky Agung Laksono, M.Pd.I'
        ]);
    }
}
