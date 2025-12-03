@extends('client.layout')

@section('content')

    <!-- MAIN CONTENT -->
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">

                <!-- Back Button & Title Section -->
                <div class="mb-4" data-aos="fade-down" data-aos-duration="600">
                    <a href="/jadwal-kegiatan" class="btn btn-outline-primary rounded-pill px-4 mb-4">
                        <i class="fas fa-arrow-left me-2"></i>Kembali ke Jadwal Kegiatan
                    </a>

                    <!-- Decorative Title -->
                    <div class="text-center mb-5">
                        <div class="d-inline-block position-relative">
                            <h1 class="display-5 fw-bold mb-2" style="color: #175C9E;">
                                <i class="fas fa-calendar-day me-3" style="color: #F6C948;"></i>
                                Detail Kegiatan
                                <i class="fas fa-calendar-day ms-3" style="color: #F6C948;"></i>
                            </h1>
                            <div class="position-absolute bottom-0 start-50 translate-middle-x"
                                style="width: 60%; height: 4px; background: linear-gradient(90deg, transparent, #F6C948, transparent); border-radius: 10px;">
                            </div>
                        </div>
                        <p class="text-muted mt-3 mb-0">Informasi lengkap mengenai kegiatan ini</p>
                    </div>
                </div>

                <!-- Main Card -->
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden" data-aos="fade-up" data-aos-duration="700">

                    <!-- Poster -->
                    @if ($event->poster)
                        <div class="text-center bg-light p-4">
                            <div class="card-thumbnail-wrapper mx-auto shadow-sm rounded-3"
                                style="max-width: 100%; height: auto; min-height: 300px; background-color: transparent;">
                                <img src="{{ asset('storage/' . $event->poster) }}" alt="Poster {{ $event->event_name }}"
                                    class="img-fluid rounded-3" style="max-height: 600px; object-fit: contain;">
                                <i class="fas fa-image fallback-icon" style="font-size: 5rem;"></i>
                            </div>
                            <div class="mt-3">
                                <span class="badge bg-warning text-dark px-3 py-2 rounded-pill">
                                    <i class="fas fa-image me-1"></i>Poster Event
                                </span>
                            </div>
                        </div>
                    @endif

                    <div class="card-body p-5">
                        <!-- Title -->
                        <div class="text-center mb-4">
                            <h2 class="fw-bold mb-3" style="color: #175C9E; font-size: 2rem;">
                                {{ $event->event_name }}
                            </h2>
                        </div>

                        <!-- Tema/Deskripsi -->
                        @if ($event->theme)
                            <div class="mb-5">
                                <div class="card border-0 rounded-4"
                                    style="background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);">
                                    <div class="card-body p-4">
                                        <div class="d-flex align-items-start">
                                            <div class="me-3">
                                                <div class="bg-white rounded-circle p-3 shadow-sm">
                                                    <i class="fas fa-quote-left fa-lg" style="color: #175C9E;"></i>
                                                </div>
                                            </div>
                                            <div class="flex-grow-1">
                                                <h5 class="fw-bold mb-3" style="color: #175C9E;">
                                                    <i class="fas fa-lightbulb me-2"></i>Tema & Deskripsi
                                                </h5>
                                                <div class="theme-content"
                                                    style="color: #334155; font-size: 1rem; line-height: 1.8;">
                                                    {{ $event->theme }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Info Grid -->
                        <div class="row g-4 mb-5">
                            <!-- Tanggal Mulai -->
                            <div class="col-md-6">
                                <div class="info-card p-4 rounded-4 h-100"
                                    style="background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);">
                                    <div class="d-flex align-items-start">
                                        <div class="icon-wrapper me-3">
                                            <div class="bg-white rounded-circle p-3 shadow-sm">
                                                <i class="fas fa-calendar-check fa-lg" style="color: #175C9E;"></i>
                                            </div>
                                        </div>
                                        <div>
                                            <p class="text-muted mb-1 small fw-semibold text-uppercase">Waktu Mulai</p>
                                            <h5 class="fw-bold mb-0" style="color: #175C9E;">
                                                {{ date('l, d F Y', strtotime($event->start_time)) }}
                                            </h5>
                                            <p class="mb-0 mt-1" style="color: #175C9E; font-size: 1.1rem;">
                                                <i
                                                    class="fas fa-clock me-2"></i>{{ date('H:i', strtotime($event->start_time)) }}
                                                WIB
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Tanggal Selesai -->
                            <div class="col-md-6">
                                <div class="info-card p-4 rounded-4 h-100"
                                    style="background: linear-gradient(135deg, #f3e5f5 0%, #e1bee7 100%);">
                                    <div class="d-flex align-items-start">
                                        <div class="icon-wrapper me-3">
                                            <div class="bg-white rounded-circle p-3 shadow-sm">
                                                <i class="fas fa-calendar-times fa-lg" style="color: #7b1fa2;"></i>
                                            </div>
                                        </div>
                                        <div>
                                            <p class="text-muted mb-1 small fw-semibold text-uppercase">Waktu Selesai</p>
                                            <h5 class="fw-bold mb-0" style="color: #7b1fa2;">
                                                {{ date('l, d F Y', strtotime($event->end_time)) }}
                                            </h5>
                                            <p class="mb-0 mt-1" style="color: #7b1fa2; font-size: 1.1rem;">
                                                <i
                                                    class="fas fa-clock me-2"></i>{{ date('H:i', strtotime($event->end_time)) }}
                                                WIB
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Lokasi -->
                            <div class="col-12">
                                <div class="info-card p-4 rounded-4"
                                    style="background: linear-gradient(135deg, #fff3e0 0%, #ffe0b2 100%);">
                                    <div class="d-flex align-items-start">
                                        <div class="icon-wrapper me-3">
                                            <div class="bg-white rounded-circle p-3 shadow-sm">
                                                <i class="fas fa-map-marker-alt fa-lg" style="color: #e65100;"></i>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1">
                                            <p class="text-muted mb-1 small fw-semibold text-uppercase">Lokasi Kegiatan</p>
                                            <h5 class="fw-bold mb-0" style="color: #e65100;">
                                                <i class="fas fa-location-dot me-2"></i>{{ $event->location }}
                                            </h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Pembicara / Tamu Undangan -->
                        @if ($event->is_have_tamu_undangan && $event->tamuUndangan->count() > 0)
                            <div class="mb-4">
                                <div class="d-flex align-items-center mb-4">
                                    <div class="bg-primary bg-opacity-10 rounded-circle p-2 me-3">
                                        <i class="fas fa-microphone text-primary"></i>
                                    </div>
                                    <h4 class="fw-bold mb-0" style="color: #175C9E;">
                                        Pembicara & Tamu Undangan
                                    </h4>
                                </div>

                                <div class="row g-3">
                                    @foreach ($event->tamuUndangan as $index => $tamu)
                                        <div class="col-md-6" data-aos="fade-up" data-aos-delay="{{ $index * 100 }}">
                                            <div class="card border-0 shadow-sm rounded-4 hover-lift">
                                                <div class="card-body p-4">
                                                    <div class="d-flex align-items-center">
                                                        <div class="flex-shrink-0 me-3">
                                                            <div class="rounded-circle d-flex align-items-center justify-content-center"
                                                                style="width: 50px; height: 50px; background: linear-gradient(135deg, #175C9E, #1a4d7a);">
                                                                <i class="fas fa-user-tie text-white fa-lg"></i>
                                                            </div>
                                                        </div>
                                                        <div class="flex-grow-1">
                                                            <h6 class="fw-bold mb-1" style="color: #175C9E;">
                                                                {{ $tamu->nama_tamu }}
                                                            </h6>
                                                            <span class="badge bg-light text-primary">
                                                                <i class="fas fa-star me-1"></i>Pembicara
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Divider -->
                        <hr class="my-5" style="border-top: 2px dashed #e2e8f0;">

                        <!-- Action Buttons -->
                        <div class="text-center">
                            <a href="/jadwal-kegiatan" class="btn btn-lg btn-primary rounded-pill px-5 shadow-sm">
                                <i class="fas fa-calendar-alt me-2"></i>Lihat Jadwal Kegiatan Lainnya
                            </a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

@endsection

@push('styles')
    <style>
        /* Theme Content Styling */
        .theme-content {
            white-space: pre-wrap;
            /* Preserve line breaks */
            word-wrap: break-word;
            /* Break long words */
            overflow-wrap: break-word;
            /* Modern alternative */
            text-align: justify;
        }

        /* Card Hover Effect */
        .hover-lift {
            transition: all 0.3s ease;
        }

        .hover-lift:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(23, 92, 158, 0.15) !important;
        }

        /* Info Card Animation */
        .info-card {
            transition: all 0.3s ease;
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .info-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        }

        /* Icon Wrapper */
        .icon-wrapper .rounded-circle {
            transition: all 0.3s ease;
        }

        .info-card:hover .icon-wrapper .rounded-circle {
            transform: scale(1.1);
        }

        /* Button Hover */
        .btn-primary {
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(23, 92, 158, 0.3) !important;
        }

        .btn-outline-primary:hover {
            transform: translateY(-2px);
        }

        /* Title Decoration */
        .display-5 {
            position: relative;
            display: inline-block;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .display-5 {
                font-size: 1.8rem !important;
            }

            .card-body {
                padding: 2rem !important;
            }

            h2 {
                font-size: 1.5rem !important;
            }
        }

        /* Animation */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .card {
            animation: fadeInUp 0.6s ease-out;
        }
    </style>
@endpush

@push('scripts')
    <script>
        // Add AOS animation library initialization if needed
        document.addEventListener('DOMContentLoaded', function() {
            // Optional: Add any interactive features here
        });
    </script>
@endpush
