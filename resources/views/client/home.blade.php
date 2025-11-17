@extends('client.layout')

@section('title', 'Beranda - SAMAK-Kampus')

@push('styles')
    <style>
        * {
            font-family: 'Poppins', "Lexend", Geneva, Verdana, sans-serif;
        }

        /* Pattern modern, lebih halus */
        .bg-pattern {
            background-image: radial-gradient(circle at 1px 1px, rgba(255, 255, 255, 0.15) 1px, transparent 1px);
            background-size: 25px 25px;
        }

        /* Smooth AOS */
        [data-aos] {
            transition-property: transform, opacity !important;
        }

        .hero-animate {
            position: relative;
            overflow: hidden;
        }

        .feature-card:hover {
            border-color: #2dd4bf !important;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
        }

        .btn-amber {
            background-color: #f59e0b;
            border-color: #f59e0b;
            color: white;
        }

        .btn-amber:hover {
            background-color: #d97706;
            border-color: #d97706;
        }

        .feature-icon {
            width: 3rem;
            height: 3rem;
        }

        .feature-card {
            transition: all 0.3s ease-in-out;
        }

        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 1rem 3rem rgba(0, 0, 0, 0.175) !important;
        }
    </style>
@endpush

@section('content')
    <!-- Hero Section -->
    <section class="d-flex align-items-center py-5 py-md-6 hero-animate bg-pattern"
        style="background-color: #175C9E; height: 400px;">
        <div class="container position-relative">
            <div class="position-absolute top-0 start-0 w-100 h-100 opacity-10 bg-pattern"></div>

            <div class="row align-items-center text-center text-md-start">

                <div class="col-md-7">
                    <h1 class="display-4 fw-bold text-white mb-3"
                        data-aos="fade-right" data-aos-duration="900">
                        Selamat Datang di <span class="text-warning">SAMAK Masjid</span>
                    </h1>

                    <p class="lead text-white-50"
                        data-aos="fade-right" data-aos-duration="1100" data-aos-delay="200">
                        Sistem Aplikasi Manajemen Aktivitas dan Keuangan Masjid Kampus (SAMAK)
                    </p>
                </div>

                <div class="col-md-5 text-center">
                    <img src="{{ asset('assets/images/auth-bg.png') }}" alt="Hero"
                        class="img-fluid rounded shadow fade"
                        data-aos="zoom-in" data-aos-duration="1000" data-aos-delay="300">
                </div>

            </div>
        </div>
    </section>


    {{-- FITUR LAYANAN --}}
    <section class="py-5 px-4 bg-white">
        <div class="container-xl">
            <div class="text-center mb-0" data-aos="fade-up">
                <h2 class="fw-bold text-dark mb-2">Layanan Kami</h2>
                <p class="text-muted col-lg-8 mx-auto">Akses berbagai layanan masjid kampus dengan mudah dan praktis</p>
            </div>

            <div class="row g-4 justify-content-center">

                {{-- CARD 1 --}}
                <div class="col-12 col-md-6 col-lg-3"
                    data-aos="zoom-in" data-aos-delay="100" data-aos-duration="900" data-aos-easing="ease-out-cubic">
                    <a href="/jadwal-kegiatan" class="text-decoration-none">
                        <div class="card h-100 feature-card rounded-4 shadow-sm border-0">
                            <div class="card-body p-4">
                                <div class="feature-icon d-flex align-items-center justify-content-center rounded-3 mb-4"
                                    style="background-image: linear-gradient(to bottom right, #0d6efd, #0dcaf0);">
                                    <i class="fas fa-calendar-alt fs-4 text-white" aria-hidden="true"></i>
                                </div>
                                <h3 class="h5 fw-semibold text-dark mb-2">Jadwal Kegiatan</h3>
                                <p class="small text-muted">Kalender interaktif kajian dan kegiatan masjid</p>
                            </div>
                        </div>
                    </a>
                </div>

                {{-- CARD 2 --}}
                <div class="col-12 col-md-6 col-lg-3"
                    data-aos="zoom-in" data-aos-delay="200" data-aos-duration="900" data-aos-easing="ease-out-cubic">
                    <a href="/donasi" class="text-decoration-none">
                        <div class="card h-100 feature-card rounded-4 shadow-sm border-0">
                            <div class="card-body p-4">
                                <div class="feature-icon d-flex align-items-center justify-content-center rounded-3 mb-4"
                                    style="background-image: linear-gradient(to bottom right, #198754, #20c997);">
                                    <i class="fas fa-heart fs-4 text-white"></i>
                                </div>
                                <h3 class="h5 fw-semibold text-dark mb-2">Donasi & ZIS</h3>
                                <p class="small text-muted">Salurkan donasi Anda dengan mudah dan transparan</p>
                            </div>
                        </div>
                    </a>
                </div>

                {{-- CARD 3 --}}
                <div class="col-12 col-md-6 col-lg-3"
                    data-aos="zoom-in" data-aos-delay="300" data-aos-duration="900" data-aos-easing="ease-out-cubic">
                    <a href="/laporan-keuangan" class="text-decoration-none">
                        <div class="card h-100 feature-card rounded-4 shadow-sm border-0">
                            <div class="card-body p-4">
                                <div class="feature-icon d-flex align-items-center justify-content-center rounded-3 mb-4"
                                    style="background-image: linear-gradient(to bottom right, #ffc107, #fd7e14);">
                                    <i class="fas fa-chart-line fs-4 text-white"></i>
                                </div>
                                <h3 class="h5 fw-semibold text-dark mb-2">Laporan Keuangan</h3>
                                <p class="small text-muted">Transparansi pengelolaan keuangan masjid</p>
                            </div>
                        </div>
                    </a>
                </div>

                {{-- CARD 4 --}}
                <div class="col-12 col-md-6 col-lg-3"
                    data-aos="zoom-in" data-aos-delay="400" data-aos-duration="900" data-aos-easing="ease-out-cubic">
                    <a href="/layanan/lost-and-found" class="text-decoration-none">
                        <div class="card h-100 feature-card rounded-4 shadow-sm border-0">
                            <div class="card-body p-4">
                                <div class="feature-icon d-flex align-items-center justify-content-center rounded-3 mb-4"
                                    style="background-image: linear-gradient(to bottom right, #6f42c1, #d63384);">
                                    <i class="fas fa-search fs-4 text-white"></i>
                                </div>
                                <h3 class="h5 fw-semibold text-dark mb-2">Lost & Found</h3>
                                <p class="small text-muted">Temukan atau laporkan barang hilang di masjid</p>
                            </div>
                        </div>
                    </a>
                </div>

            </div>
        </div>
    </section>


    {{-- KEGIATAN MENDATANG --}}
    <section class="py-5 bg-light">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center mb-4" data-aos="fade-up">
                <h2 class="fw-bold">Kegiatan Mendatang</h2>
                <a href="/jadwal-kegiatan" class="fw-semibold text-decoration-none" style="color: #175C9E">Lihat Semua <i
                        class="fas fa-arrow-right-long ms-1"></i></a>
            </div>
            <div class="row g-4">

                @foreach ([
            [
                'title' => 'Kajian Rutin Ba\'da Maghrib',
                'ustadz' => 'Ustadz Ahmad Zainuddin',
                'lokasi' => 'Ruang Utama Masjid',
                'waktu' => '15 Nov, 18.30 WIB',
                'day' => '15',
                'month' => 'Nov',
                'image_url' => 'https://via.placeholder.com/720x405?text=Kegiatan+1',
            ],
            [
                'title' => 'Seminar Pra-Nikah',
                'ustadz' => 'Ustadz Mahmud & Ustadzah Fatimah',
                'lokasi' => 'Aula Masjid',
                'waktu' => '20 Nov, 08.00 WIB',
                'day' => '20',
                'month' => 'Nov',
                'image_url' => 'https://via.placeholder.com/720x405?text=Kegiatan+2',
            ],
            [
                'title' => 'Tahsin Al-Quran',
                'ustadz' => 'Qori Abdullah',
                'lokasi' => 'Ruang Kelas Masjid',
                'waktu' => '22 Nov, 16.00 WIB',
                'day' => '22',
                'month' => 'Nov',
                'image_url' => 'link-rusak-kegiatan.jpg',
            ],
        ] as $event)
                    <div class="col-md-4"
                        data-aos="fade-up" data-aos-delay="100" data-aos-duration="700" data-aos-easing="ease-out-quart">
                        <a href="#" class="text-decoration-none text-dark card-link">
                            <div class="card h-100 shadow-sm event-card">
                                <div class="card-body d-flex flex-column">

                                    <div class="card-thumbnail-wrapper mb-3">
                                        <img src="{{ $event['image_url'] }}" alt="{{ $event['title'] }}"
                                            onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                                        <i class="fas fa-calendar-alt fallback-icon"></i>
                                    </div>

                                    <div class="d-flex">
                                        <div class="text-center me-3" style="min-width: 60px;">
                                            <div class="p-2 rounded-top" style="background-color: #175C9E;">
                                                <span class="d-block fs-6 text-white fw-bold">{{ $event['month'] }}</span>
                                            </div>
                                            <div class="bg-white p-2 rounded-bottom border border-top-0">
                                                <span class="d-block fs-4 fw-bold" style="color: #175C9E;">
                                                    {{ $event['day'] }}
                                                </span>
                                            </div>
                                        </div>

                                        <div>
                                            <h5 class="card-title fw-bold mb-2">{{ $event['title'] }}</h5>
                                            <p class="text-muted small mb-1">
                                                <i class="fas fa-user me-1"></i> {{ $event['ustadz'] }}
                                            </p>
                                            <p class="text-muted small mb-1">
                                                <i class="fas fa-map-marker-alt me-1"></i> {{ $event['lokasi'] }}
                                            </p>
                                            <p class="fw-semibold small mb-0">
                                                <i class="fas fa-clock me-1"></i> {{ $event['waktu'] }}
                                            </p>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach

            </div>
        </div>
    </section>



    {{-- ARTIKEL --}}
    <section class="py-5">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center mb-4" data-aos="fade-up">
                <h2 class="fw-bold">Kabar Terbaru</h2>
                <a href="/admin/posts" class="fw-semibold text-decoration-none" style="color: #175C9E">Lihat Semua <i
                        class="fas fa-arrow-right-long ms-1"></i></a>
            </div>
            <div class="row g-4">

                @foreach ([
            [
                'title' => 'Jadwal Shalat Jumat Bulan Ramadhan',
                'category' => 'Berita',
                'excerpt' => 'Berikut jadwal khatib Jumat untuk bulan Ramadhan...',
                'image_url' => 'https://via.placeholder.com/720x405?text=Artikel+Masjid+1',
            ],
            [
                'title' => 'Hikmah Puasa Ramadhan',
                'category' => 'Tausiyah',
                'excerpt' => 'Puasa Ramadhan memiliki banyak hikmah bagi kehidupan...',
                'image_url' => 'link-gambar-rusak.jpg',
            ],
        ] as $post)
                    <div class="col-md-6"
                        data-aos="fade-up" data-aos-delay="150" data-aos-duration="800" data-aos-easing="ease-out-cubic">
                        <a href="#" class="text-decoration-none text-dark card-link">
                            <div class="card h-100 shadow-sm article-card">
                                <div class="card-body d-flex flex-column">

                                    <div class="card-thumbnail-wrapper mb-3">
                                        <img src="{{ $post['image_url'] }}"
                                            onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                                        <i class="fas fa-image fallback-icon"></i>
                                    </div>

                                    <div>
                                        <span class="badge mb-2" style="background-color: #CE9138">
                                            {{ $post['category'] }}
                                        </span>
                                        <h5 class="card-title fw-bold">{{ $post['title'] }}</h5>
                                        <p class="card-text text-muted small">{{ $post['excerpt'] }}</p>
                                    </div>

                                    <div class="mt-auto pt-3">
                                        <small class="text-muted">Oleh: Admin Konten • 30 Okt 2025</small><br>
                                        <span class="fw-semibold" style="color: #175C9E;">
                                            Baca Selengkapnya <i class="fas fa-arrow-right ms-1"></i>
                                        </span>
                                    </div>

                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach

            </div>
        </div>
    </section>
@endsection
