@extends('client.layout')

@section('title', 'Beranda - SAMAK-Kampus')

@section('content')
<!-- Hero Section -->
<section class="py-5 py-md-6" style="background-color: #175C9E;">
    <div class="container position-relative">
        <div class="position-absolute top-0 start-0 w-100 h-100 opacity-10 bg-pattern"></div>
        <div class="position-relative z-1 text-center text-white">
            <h1 class="display-4 fw-bold mb-3">Selamat Datang di Digital Masjid</h1>
            <p class="lead mb-4">Sistem Administrasi Masjid Kampus - Membangun Generasi Qur'ani</p>
            <div class="d-flex flex-wrap justify-content-center gap-3">
                <a href="/jadwal-kegiatan" class="btn btn-amber px-4 py-2">
                    <i class="fas fa-calendar me-2"></i> Lihat Jadwal Kegiatan
                </a>
                <a href="/donasi" class="btn btn-light text-teal px-4 py-2">
                    <i class="fas fa-heart me-2"></i> Donasi Sekarang
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Layanan Kami -->
<section class="py-5 bg-light">
    <div class="container">
        <h2 class="text-center fw-bold mb-5 text-success" style="font-family: 'Playfair Display', serif;">Layanan Kami</h2>
        <div class="row g-4">
            @foreach([
                ['icon' => 'book-open', 'title' => 'Kajian Rutin', 'desc' => 'Kajian Islam berkualitas setiap hari', 'color' => 'teal'],
                ['icon' => 'hand-holding-usd', 'title' => 'Transparansi Keuangan', 'desc' => 'Laporan keuangan yang terbuka', 'color' => 'amber'],
                ['icon' => 'calendar-check', 'title' => 'Event Management', 'desc' => 'Pendaftaran event online mudah', 'color' => 'teal'],
                ['icon' => 'users', 'title' => 'Layanan Jamaah', 'desc' => 'Lost & Found, Konsultasi Online', 'color' => 'amber']
            ] as $service)
            <div class="col-md-6 col-lg-3">
                <div class="card feature-card h-100 border border-2 border-light rounded-3">
                    <div class="card-body text-center">
                        <div class="rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 64px; height: 64px; background: linear-gradient(to bottom right, {{ $service['color'] === 'teal' ? '#2dd4bf' : '#fbbf24' }}, {{ $service['color'] === 'teal' ? '#0d9488' : '#d97706' }});">
                            <i class="fas fa-{{ $service['icon'] }} text-white fs-4"></i>
                        </div>
                        <h5 class="card-title fw-bold text-dark">{{ $service['title'] }}</h5>
                        <p class="card-text text-muted">{{ $service['desc'] }}</p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Artikel Terbaru -->
<section class="py-5">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold text-success" style="font-family: 'Playfair Display', serif;">Artikel Terbaru</h2>
            <a href="/admin/posts" class="text-success fw-semibold">Lihat Semua →</a>
        </div>
        <div class="row g-4">
            @foreach([
                ['title' => 'Jadwal Shalat Jumat Bulan Ramadhan', 'category' => 'Berita', 'excerpt' => 'Berikut jadwal khatib Jumat untuk bulan Ramadhan...'],
                ['title' => 'Hikmah Puasa Ramadhan', 'category' => 'Tausiyah', 'excerpt' => 'Puasa Ramadhan memiliki banyak hikmah bagi kehidupan...']
            ] as $post)
            <div class="col-md-6">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <span class="badge bg-success mb-2">{{ $post['category'] }}</span>
                        <h5 class="card-title fw-bold">{{ $post['title'] }}</h5>
                        <p class="card-text text-muted">{{ $post['excerpt'] }}</p>
                        <small class="text-muted">Oleh: Admin Konten</small><br>
                        <a href="#" class="mt-2 d-inline-block text-success text-decoration-none">Baca Selengkapnya →</a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Kegiatan Mendatang -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold text-success" style="font-family: 'Playfair Display', serif;">Kegiatan Mendatang</h2>
            <a href="/jadwal-kegiatan" class="text-success fw-semibold">Lihat Semua →</a>
        </div>
        <div class="row g-4">
            @foreach([
                ['title' => 'Kajian Rutin Ba\'da Maghrib', 'ustadz' => 'Ustadz Ahmad Zainuddin', 'tema' => 'Fiqih Ibadah', 'lokasi' => 'Ruang Utama Masjid', 'waktu' => '15/6/2025, 18.30'],
                ['title' => 'Seminar Pra-Nikah', 'ustadz' => 'Ustadz Mahmud & Ustadzah Fatimah', 'tema' => 'Persiapan Menuju Keluarga Sakinah', 'lokasi' => 'Aula Masjid', 'waktu' => '20/7/2025, 08.00'],
                ['title' => 'Tahsin Al-Quran', 'ustadz' => 'Qori Abdullah', 'tema' => 'Perbaikan Bacaan Al-Quran', 'lokasi' => 'Ruang Kelas Masjid', 'waktu' => '10/6/2025, 16.00']
            ] as $event)
            <div class="col-md-4">
                <div class="card h-100 shadow-sm border-start border-4 border-success">
                    <div class="card-body">
                        <h5 class="card-title fw-bold">{{ $event['title'] }}</h5>
                        <p class="text-muted small mb-1"><i class="fas fa-user me-1"></i> {{ $event['ustadz'] }}</p>
                        <p class="text-muted small mb-1"><i class="fas fa-tag me-1"></i> {{ $event['tema'] }}</p>
                        <p class="text-muted small mb-1"><i class="fas fa-map-marker-alt me-1"></i> {{ $event['lokasi'] }}</p>
                        <p class="text-success fw-semibold small mb-3"><i class="fas fa-clock me-1"></i> {{ $event['waktu'] }}</p>
                        <a href="#" class="text-success text-decoration-none">Lihat Detail →</a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endsection
