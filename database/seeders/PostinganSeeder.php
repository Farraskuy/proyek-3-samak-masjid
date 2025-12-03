<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Postingan;
use App\Models\User;
use Illuminate\Support\Str;
use Carbon\Carbon;

class PostinganSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil User ID (Admin) pertama untuk author & approver
        // Pastikan tabel users sudah di-seed duluan
        $user = User::first() ?? User::factory()->create();
        $userId = $user->id;

        // Daftar 20 Data Real (Topik Masjid & Keislaman)
        $dataReal = [
            [
                'title' => 'Peringatan Maulid Nabi Muhammad SAW 1446 H',
                'kategori' => 'Berita',
                'status' => 'published',
                'keterangan' => 'Dokumentasi kegiatan peringatan Maulid Nabi yang dihadiri oleh ratusan jamaah.',
                'content' => '<p>Alhamdulillah, acara peringatan Maulid Nabi Muhammad SAW 1446 H di Masjid Besar berjalan dengan khidmat dan lancar. Acara dimulai dengan pembacaan ayat suci Al-Quran, dilanjutkan dengan shalawat nabi bersama grup hadroh pemuda masjid.</p><p>Penceramah KH. Abdullah menyampaikan tausiyah mengenai pentingnya meneladani akhlak Rasulullah dalam kehidupan sehari-hari, terutama dalam menjaga kerukunan antar tetangga.</p>',
            ],
            [
                'title' => 'Keutamaan Sholat Berjamaah di Masjid',
                'kategori' => 'Tausiyah',
                'status' => 'published',
                'keterangan' => 'Penjelasan mengenai pahala 27 derajat sholat berjamaah.',
                'content' => '<p>Sholat berjamaah memiliki keutamaan yang luar biasa dibandingkan sholat sendirian. Rasulullah SAW bersabda bahwa sholat berjamaah lebih utama 27 derajat daripada sholat munfarid (sendirian).</p><p>Selain mendapatkan pahala yang berlipat, sholat berjamaah juga menjadi ajang silaturahmi antar warga dan memperkuat ukhuwah Islamiyah di lingkungan kita.</p>',
            ],
            [
                'title' => 'Laporan Keuangan Masjid Bulan Oktober',
                'kategori' => 'Artikel',
                'status' => 'published',
                'keterangan' => 'Transparansi dana infaq dan shodaqoh jamaah.',
                'content' => '<p>Berikut kami sampaikan laporan keuangan kas masjid periode bulan Oktober. Total pemasukan dari kotak amal Jumat dan donatur tetap mencapai Rp 15.000.000.</p><p>Dana tersebut telah dialokasikan untuk pembayaran listrik, kebersihan, insentif marbot, dan biaya operasional pengajian rutin. Terima kasih kepada seluruh donatur, semoga menjadi amal jariyah.</p>',
            ],
            [
                'title' => 'Jadwal Petugas Imam dan Khotib Jumat',
                'kategori' => 'Berita',
                'status' => 'published',
                'keterangan' => 'Jadwal petugas sholat Jumat untuk bulan depan.',
                'content' => '<p>Demi kelancaran ibadah Sholat Jumat, DKM telah menyusun jadwal petugas Imam, Khotib, dan Muadzin untuk bulan depan. Kami harap para petugas dapat hadir 15 menit sebelum adzan berkumandang.</p><p>Bagi jamaah yang berhalangan hadir sesuai jadwal, dimohon segera mengkonfirmasi kepada sekretariat DKM agar dapat dicarikan penggantinya.</p>',
            ],
            [
                'title' => 'Pentingnya Menjaga Kebersihan Hati',
                'kategori' => 'Tausiyah',
                'status' => 'revisi', // Contoh Revisi
                'keterangan' => 'Kajian singkat manajemen qolbu.',
                'content' => '<p>Hati adalah raja di dalam tubuh manusia. Jika hati itu baik, maka baiklah seluruh amalnya. Namun jika hati itu rusak (penuh iri, dengki, dan sombong), maka rusaklah seluruh amalnya.</p><p>Mari kita senantiasa beristighfar dan membersihkan hati kita dari penyakit-penyakit rohani yang dapat menghapus pahala kebaikan kita.</p>',
            ],
            [
                'title' => 'Gotong Royong Membersihkan Area Parkir',
                'kategori' => 'Berita',
                'status' => 'pending', // Contoh Pending
                'keterangan' => 'Aksi bersih-bersih remaja masjid minggu pagi.',
                'content' => '<p>Remaja Masjid (RISMA) akan mengadakan kegiatan kerja bakti membersihkan area parkir dan selokan depan masjid pada hari Minggu besok. Diharapkan partisipasi seluruh pemuda untuk membawa alat kebersihan masing-masing.</p><p>Kegiatan ini bertujuan agar jamaah merasa lebih nyaman saat memarkirkan kendaraannya dan mencegah banjir saat musim hujan tiba.</p>',
            ],
            [
                'title' => 'Sejarah Pembangunan Masjid Kita',
                'kategori' => 'Artikel',
                'status' => 'published',
                'keterangan' => 'Mengenang perjuangan para pendiri masjid tahun 1990.',
                'content' => '<p>Masjid ini didirikan pada tahun 1990 atas wakaf tanah dari H. Soleh (Alm). Awalnya hanya berupa musholla kecil semi permanen. Berkat gotong royong warga, kini masjid telah menjadi bangunan dua lantai yang megah.</p><p>Kita sebagai generasi penerus memiliki tanggung jawab untuk tidak hanya memakmurkan fisiknya, tetapi juga memakmurkan kegiatannya.</p>',
            ],
            [
                'title' => 'Penerimaan Hewan Qurban Idul Adha',
                'kategori' => 'Berita',
                'status' => 'published',
                'keterangan' => 'Informasi pendaftaran peserta qurban tahun ini.',
                'content' => '<p>Panitia Qurban Masjid membuka pendaftaran bagi jamaah yang ingin berkurban Sapi atau Kambing. Pendaftaran dibuka hingga H-3 Idul Adha di sekretariat masjid setiap ba’da Maghrib.</p><p>Tahun ini, biaya patungan Sapi ditetapkan sebesar Rp 3.500.000 per orang (untuk 7 orang), sedangkan Kambing mulai dari Rp 3.000.000.</p>',
            ],
            [
                'title' => 'Makna Ikhlas dalam Beramal',
                'kategori' => 'Tausiyah',
                'status' => 'published',
                'keterangan' => 'Renungan Jumat tentang niat yang lurus.',
                'content' => '<p>Ikhlas adalah ruh dari setiap amal. Tanpa keikhlasan, amal sebesar apapun akan sia-sia di mata Allah SWT. Ikhlas berarti memurnikan niat hanya untuk mencari ridho Allah, bukan pujian manusia.</p><p>Seperti surat Al-Ikhlas yang tidak ada kata "Ikhlas" di dalamnya, begitu pula orang yang ikhlas tidak perlu menyebut-nyebut kebaikannya.</p>',
            ],
            [
                'title' => 'Kajian Rutin Kitab Riyadhus Shalihin',
                'kategori' => 'Berita',
                'status' => 'published',
                'keterangan' => 'Undangan menghadiri majelis ilmu setiap Sabtu malam.',
                'content' => '<p>Mengundang seluruh jamaah bapak-bapak dan ibu-ibu untuk menghadiri kajian rutin pembahasan Kitab Riyadhus Shalihin bersama Ustadz Hanan. Kajian dilaksanakan setiap Sabtu, Ba’da Isya sampai selesai.</p><p>Mari kita luangkan waktu sejenak untuk menuntut ilmu agama agar ibadah kita semakin berkualitas sesuai tuntunan Nabi.</p>',
            ],
            [
                'title' => 'Tips Mendidik Anak Secara Islami',
                'kategori' => 'Artikel',
                'status' => 'published',
                'keterangan' => 'Panduan parenting bagi orang tua muda.',
                'content' => '<p>Anak adalah amanah terbesar dari Allah. Mendidik mereka di zaman digital ini memiliki tantangan tersendiri. Salah satu kuncinya adalah keteladanan orang tua dalam beribadah di rumah.</p><p>Jangan hanya menyuruh anak sholat, tapi ajaklah mereka sholat bersama. Batasi penggunaan gadget dan perbanyak interaksi dengan kisah-kisah Nabi.</p>',
            ],
            [
                'title' => 'Galang Dana untuk Korban Bencana',
                'kategori' => 'Berita',
                'status' => 'draft', // Contoh Draft
                'keterangan' => 'Pengumpulan donasi kemanusiaan pasca gempa.',
                'content' => '<p>DKM Masjid mengajak jamaah untuk menyisihkan sebagian rezekinya guna membantu saudara kita yang terdampak bencana gempa bumi. Donasi dapat disalurkan melalui kotak khusus di pintu masuk utama.</p><p>Bantuan akan disalurkan dalam bentuk sembako, selimut, dan obat-obatan bekerja sama dengan lembaga amil zakat setempat.</p>',
            ],
            [
                'title' => 'Adab Masuk dan Keluar Masjid',
                'kategori' => 'Tausiyah',
                'status' => 'published',
                'keterangan' => 'Mengingatkan kembali sunnah-sunnah saat di masjid.',
                'content' => '<p>Masjid adalah rumah Allah, maka ada adab yang harus dijaga. Masuklah dengan kaki kanan sambil membaca doa, dan keluarlah dengan kaki kiri.</p><p>Jangan lupa untuk melakukan sholat tahiyatul masjid dua rakaat sebelum duduk, serta menjaga ketenangan dengan tidak berbicara keras atau mengaktifkan nada dering HP.</p>',
            ],
            [
                'title' => 'Program Tahfidz Quran Anak-Anak',
                'kategori' => 'Berita',
                'status' => 'published',
                'keterangan' => 'Pembukaan kelas baru TPA sore hari.',
                'content' => '<p>Taman Pendidikan Al-Quran (TPA) Masjid membuka kelas khusus Tahfidz Juz 30 untuk anak usia 6-12 tahun. Kelas akan dimulai bulan depan setiap hari Senin, Rabu, dan Jumat sore.</p><p>Segera daftarkan putra-putri Ayah Bunda karena kuota terbatas hanya untuk 20 santri agar pembelajaran lebih efektif.</p>',
            ],
            [
                'title' => 'Hukum Jual Beli di Area Masjid',
                'kategori' => 'Artikel',
                'status' => 'revisi', // Contoh Revisi
                'keterangan' => 'Fiqih muamalah terkait area masjid.',
                'content' => '<p>Para ulama sepakat bahwa jual beli di dalam ruang utama masjid (tempat sholat) hukumnya terlarang. Namun, jika dilakukan di halaman luar atau area yang memang dikhususkan untuk bazar, hal tersebut diperbolehkan.</p><p>Mari kita jaga kesucian masjid dari hiruk pikuk transaksi duniawi agar kekhusyukan ibadah tetap terjaga.</p>',
            ],
            [
                'title' => 'Buka Puasa Bersama (Iftar) Senin Kamis',
                'kategori' => 'Berita',
                'status' => 'published',
                'keterangan' => 'Fasilitas takjil gratis bagi jamaah yang berpuasa.',
                'content' => '<p>Mulai pekan ini, Masjid menyediakan hidangan buka puasa sederhana bagi jamaah yang menjalankan puasa sunnah Senin dan Kamis. Silakan merapat ke serambi masjid menjelang waktu Maghrib.</p><p>Bagi jamaah yang ingin bersedekah makanan (takjil), bisa menghubungi marbot masjid sebelum pukul 17.00 WIB.</p>',
            ],
            [
                'title' => 'Persiapan Menyambut Bulan Suci Ramadhan',
                'kategori' => 'Tausiyah',
                'status' => 'published',
                'keterangan' => 'Bekal ilmu sebelum masuk bulan puasa.',
                'content' => '<p>Ramadhan tinggal menghitung hari. Persiapan terbaik bukan hanya stok makanan, melainkan persiapan iman dan ilmu. Pelajarilah kembali fiqih puasa agar ibadah kita sah.</p><p>Selain itu, mulailah melatih diri dengan memperbanyak bacaan Al-Quran dan qiyamul lail agar tidak kaget saat Ramadhan tiba nanti.</p>',
            ],
            [
                'title' => 'Renovasi Tempat Wudhu Wanita',
                'kategori' => 'Berita',
                'status' => 'pending', // Contoh Pending
                'keterangan' => 'Update progres pembangunan fasilitas masjid.',
                'content' => '<p>Alhamdulillah, renovasi tempat wudhu khusus akhwat (wanita) sudah mencapai 80%. Pemasangan keramik dan kran air baru telah selesai dilakukan.</p><p>Mohon maaf atas ketidaknyamanan selama proses pengerjaan. Insya Allah minggu depan fasilitas ini sudah bisa digunakan kembali dengan lebih nyaman dan bersih.</p>',
            ],
            [
                'title' => 'Keajaiban Sedekah Subuh',
                'kategori' => 'Tausiyah',
                'status' => 'published',
                'keterangan' => 'Motivasi berinfak di waktu pagi.',
                'content' => '<p>Setiap pagi, dua malaikat turun mendoakan manusia. Malaikat pertama berdoa: "Ya Allah, berikanlah ganti bagi orang yang berinfak." Malaikat kedua berdoa: "Ya Allah, berikanlah kehancuran bagi orang yang pelit."</p><p>Maka dari itu, rutinkanlah sedekah subuh, meskipun hanya sedikit, karena doa malaikat di waktu tersebut sangat mustajab.</p>',
            ],
            [
                'title' => 'Profil Ketua DKM Periode 2024-2027',
                'kategori' => 'Artikel',
                'status' => 'published',
                'keterangan' => 'Mengenal sosok pemimpin baru masjid kita.',
                'content' => '<p>Bapak H. Rahmat Hidayat terpilih sebagai ketua DKM yang baru. Beliau dikenal sebagai tokoh masyarakat yang aktif dan dermawan. Visi beliau adalah menjadikan masjid sebagai pusat peradaban umat.</p><p>Mari kita dukung program-program beliau untuk kemakmuran masjid kita tercinta. Semoga Allah memberikan kekuatan dan amanah dalam mengemban tugas mulia ini.</p>',
            ]
        ];

        foreach ($dataReal as $index => $item) {
            $num = $index + 1;
            
            // Logic Status & Approval
            $approvedBy = null;
            $approvedAt = null;
            $publishedAt = null;
            $approvalNote = null;

            if ($item['status'] === 'published') {
                $approvedBy = $userId;
                $approvedAt = Carbon::now()->subDays(rand(1, 30));
                $publishedAt = $approvedAt; // Publish saat di-approve
            } elseif ($item['status'] === 'revisi') {
                $approvalNote = 'Mohon perbaiki tulisan, perbanyak referensi ayat, dan perbaiki tata bahasa.';
            }

            Postingan::create([
                'user_id' => $userId,
                'title' => $item['title'],
                'slug' => Str::slug($item['title']),
                'keterangan' => $item['keterangan'],
                'content' => $item['content'],
                // Set gambar urut postingan1.jpg s/d postingan20.jpg
                'featured_image_url' => "news/images/postingan{$num}.jpg",
                'status' => $item['status'],
                'kategori' => $item['kategori'],
                'published_at' => $publishedAt,
                
                // Field Approval Baru
                'approval_note' => $approvalNote,
                'approved_by' => $approvedBy,
                'approved_at' => $approvedAt,
                
                'created_at' => Carbon::now()->subDays(rand(31, 60)),
                'updated_at' => Carbon::now(),
            ]);
        }
    }
}