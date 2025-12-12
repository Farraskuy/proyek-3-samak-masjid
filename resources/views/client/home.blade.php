@extends('client.layout')

@section('title', 'Beranda')

@push('styles')
    <style>
        /* Pattern modern */
        .bg-pattern {
            background-image: radial-gradient(circle at 1px 1px, rgba(255, 255, 255, 0.15) 1px, transparent 1px);
            background-size: 25px 25px;
        }

        /* Smooth AOS */
        [data-aos] {
            transition-property: transform, opacity !important;
        }

        .feature-card {
            transition: .25s ease-in-out;
        }

        .feature-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 1.2rem 3rem rgba(0, 0, 0, .15) !important;
        }

        /* Fallback icon */
        .fallback-icon {
            font-size: 4rem;
            color: #999;
            display: none;
            text-align: center;
            width: 100%;
        }

        .card-thumbnail-wrapper img {
            width: 100%;
            border-radius: .5rem;
            object-fit: cover;
        }

        /* UnDraw empty illustration */
        .empty-illustration {
            max-width: 260px;
            opacity: .85;
        }

        /* Hero Section Responsive */
        .hero-section {
            background-color: #175C9E;
            min-height: 400px;
            position: relative;
            overflow: hidden;
        }

        .hero-image-col {
            display: block;
        }

        @media (max-width: 768px) {
            .hero-section {
                background-image: linear-gradient(rgba(23, 92, 158, 0.85), rgba(23, 92, 158, 0.95)), url('{{ asset('assets/images/lukmanulhakim.png') }}');
                background-size: cover;
                background-position: center;
                height: auto;
                padding-top: 4rem;
                padding-bottom: 4rem;
            }

            .hero-image-col {
                display: none;
            }
        }
    </style>
@endpush

@section('content')

    {{-- HERO --}}
    <section class="d-flex align-items-center py-5 py-md-6 hero-animate bg-pattern hero-section">
        <div class="container position-relative">
            <div class="row align-items-center text-start">

                <div class="col-md-7">
                    <h1 class="display-4 fw-bold text-white mb-3" data-aos="fade-right" data-aos-duration="900">
                        Selamat Datang di <span class="text-warning">SAMAK Masjid</span>
                    </h1>

                    <p class="lead text-white-50" data-aos="fade-right" data-aos-duration="1100" data-aos-delay="200">
                        Sistem Aplikasi Manajemen Aktivitas dan Keuangan Masjid (SAMAK Masjid)
                    </p>
                </div>

                <div class="col-md-5 text-center hero-image-col">
                    <img src="{{ asset('assets/images/lukmanulhakim.png') }}" alt="Hero"
                        class="img-fluid rounded shadow fade" data-aos="zoom-in" data-aos-duration="1000"
                        data-aos-delay="300">
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
                <div class="col-12 col-md-6 col-lg-3" data-aos="zoom-in" data-aos-delay="100" data-aos-duration="900">
                    <a href="/jadwal-kegiatan" class="text-decoration-none">
                        <div class="card h-100 feature-card rounded-4 shadow-sm border-0">
                            <div class="card-body p-4">
                                <div class="feature-icon d-flex align-items-center justify-content-center rounded-3 mb-4"
                                    style="background-image: linear-gradient(to bottom right, #0d6efd, #0dcaf0); width:3rem; height:3rem;">
                                    <i class="fas fa-calendar-alt fs-4 text-white"></i>
                                </div>
                                <h3 class="h5 fw-semibold text-dark mb-2">Jadwal Kegiatan</h3>
                                <p class="small text-muted">Kalender interaktif kajian dan kegiatan masjid</p>
                            </div>
                        </div>
                    </a>
                </div>

                {{-- CARD 2 --}}
                <div class="col-12 col-md-6 col-lg-3" data-aos="zoom-in" data-aos-delay="200" data-aos-duration="900">
                    <a href="/donasi" class="text-decoration-none">
                        <div class="card h-100 feature-card rounded-4 shadow-sm border-0">
                            <div class="card-body p-4">
                                <div class="feature-icon d-flex align-items-center justify-content-center rounded-3 mb-4"
                                    style="background-image: linear-gradient(to bottom right, #198754, #20c997); width:3rem; height:3rem;">
                                    <i class="fas fa-heart fs-4 text-white"></i>
                                </div>
                                <h3 class="h5 fw-semibold text-dark mb-2">Donasi & ZIS</h3>
                                <p class="small text-muted">Salurkan donasi Anda dengan mudah dan transparan</p>
                            </div>
                        </div>
                    </a>
                </div>

                {{-- CARD 3 --}}
                <div class="col-12 col-md-6 col-lg-3" data-aos="zoom-in" data-aos-delay="300" data-aos-duration="900">
                    <a href="/laporan-keuangan" class="text-decoration-none">
                        <div class="card h-100 feature-card rounded-4 shadow-sm border-0">
                            <div class="card-body p-4">
                                <div class="feature-icon d-flex align-items-center justify-content-center rounded-3 mb-4"
                                    style="background-image: linear-gradient(to bottom right, #ffc107, #fd7e14); width:3rem; height:3rem;">
                                    <i class="fas fa-chart-line fs-4 text-white"></i>
                                </div>
                                <h3 class="h5 fw-semibold text-dark mb-2">Laporan Keuangan</h3>
                                <p class="small text-muted">Transparansi pengelolaan keuangan masjid</p>
                            </div>
                        </div>
                    </a>
                </div>

                {{-- CARD 4 --}}
                <div class="col-12 col-md-6 col-lg-3" data-aos="zoom-in" data-aos-delay="400" data-aos-duration="900">
                    <a href="/layanan/lost-and-found" class="text-decoration-none">
                        <div class="card h-100 feature-card rounded-4 shadow-sm border-0">
                            <div class="card-body p-4">
                                <div class="feature-icon d-flex align-items-center justify-content-center rounded-3 mb-4"
                                    style="background-image: linear-gradient(to bottom right, #6f42c1, #d63384); width:3rem; height:3rem;">
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
                <a href="/jadwal-kegiatan" class="fw-semibold text-decoration-none" style="color: #175C9E">
                    Lihat Semua <i class="fas fa-arrow-right-long ms-1"></i>
                </a>
            </div>
            <div class="row g-4">

                {{-- EMPTY STATE --}}
                @if ($events->isEmpty())
                    <div class="text-center py-5">
                        <img src="{{ asset('assets/images/no-data.png') }}" class="empty-illustration" alt="No events">
                        <p class="text-muted mt-3">Belum ada kegiatan untuk saat ini.</p>
                    </div>
                @endif

                {{-- LOOP --}}
                @foreach ($events as $event)
                    <div class="col-md-4" data-aos="fade-up" data-aos-delay="100" data-aos-duration="700">
                        <a href="{{ route('jadwal.detail', $event->event_id) }}"
                            class="text-decoration-none text-dark card-link">
                            <div class="card h-100 shadow-sm event-card">
                                <div class="card-body d-flex flex-column">

                                    <div class="card-thumbnail-wrapper mb-3">
                                        @if ($event->poster)
                                            <img src="{{ asset('storage/' . $event->poster) }}"
                                                alt="{{ $event->event_name }}">
                                        @else
                                            <div
                                                class="bg-light d-flex align-items-center justify-content-center h-100 rounded">
                                                <i class="fas fa-calendar-alt fa-3x text-muted"></i>
                                            </div>
                                        @endif
                                    </div>

                                    <div class="d-flex">
                                        <div class="text-center me-3" style="min-width:60px;">
                                            <div class="p-2 rounded-top" style="background-color:#175C9E;">
                                                <span
                                                    class="d-block fs-6 text-white fw-bold">{{ \Carbon\Carbon::parse($event->start_time)->translatedFormat('M') }}</span>
                                            </div>
                                            <div class="bg-white p-2 rounded-bottom border border-top-0">
                                                <span class="d-block fs-4 fw-bold" style="color:#175C9E;">
                                                    {{ \Carbon\Carbon::parse($event->start_time)->format('d') }}
                                                </span>
                                            </div>
                                        </div>

                                        <div>
                                            <h5 class="card-title fw-bold mb-2">{{ $event->event_name }}</h5>

                                            @if ($event->tamuUndangan->count() > 0)
                                                <p class="text-muted small mb-1">
                                                    <i class="fas fa-user me-1"></i>
                                                    {{ $event->tamuUndangan->implode('nama_tamu', ', ') }}
                                                </p>
                                            @endif

                                            <p class="text-muted small mb-1">
                                                <i class="fas fa-map-marker-alt me-1"></i> {{ $event->location }}
                                            </p>
                                            <p class="fw-semibold small mb-0">
                                                <i class="fas fa-clock me-1"></i>
                                                {{ \Carbon\Carbon::parse($event->start_time)->format('H:i') }} WIB
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
                <a href="/postingan" class="fw-semibold text-decoration-none" style="color:#175C9E">
                    Lihat Semua <i class="fas fa-arrow-right-long ms-1"></i>
                </a>
            </div>

            {{-- DB-backed posts (passed from HomeController) --}}
            @if (!isset($posts) || $posts->isEmpty())
                <div class="text-center py-5">
                    <img src="{{ asset('assets/images/no-data.png') }}" class="empty-illustration" alt="No articles">
                    <p class="text-muted mt-3">Belum ada artikel saat ini.</p>
                </div>
            @else
                <div class="row g-4">
                    @foreach ($posts as $post)
                        <div class="col-md-6" data-aos="fade-up" data-aos-delay="150" data-aos-duration="800">
                            <a href="{{ route('client.berita.detail', $post->slug ?? $post->id) }}"
                                class="text-decoration-none text-dark card-link">
                                <div class="card h-100 shadow-sm article-card">
                                    <div class="card-body d-flex flex-column">

                                        <div class="card-thumbnail-wrapper mb-3">
                                            <img src="{{ $post->featured_image_url ? asset('storage/' . $post->featured_image_url) : '' }}"
                                                alt="{{ $post->title }}">
                                            <i class="fas fa-image fallback-icon"></i>
                                        </div>

                                        <div>
                                            <span class="badge mb-2" style="background-color:#CE9138;">
                                                {{ ucfirst($post->kategori ?? 'Umum') }}
                                            </span>
                                            <h5 class="card-title fw-bold">{{ $post->title }}</h5>
                                            <p class="card-text text-muted small">
                                                {{ Str::limit($post->keterangan ?? strip_tags($post->content), 110) }}</p>
                                        </div>

                                        <div class="mt-auto pt-3">
                                            <small class="text-muted">{{ optional($post->creator)->name ?? 'Admin' }} •
                                                {{ $post->created_at->format('d M Y') }}</small><br>
                                            <span class="fw-semibold" style="color:#175C9E;">
                                                Baca Selengkapnya <i class="fas fa-arrow-right ms-1"></i>
                                            </span>
                                        </div>

                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            @endif

        </div>
    </section>

@endsection
