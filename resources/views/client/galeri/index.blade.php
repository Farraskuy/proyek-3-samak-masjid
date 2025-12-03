@extends('client.layout')

@section('content')
    <!-- HERO SECTION -->
    <section class="py-5 bg-pattern" style="background-color: #175C9E; height: 320px; display: flex; align-items: center;">
        <div class="container text-center">
            <h1 class="display-5 fw-bold text-white mb-3" data-aos="fade-up" data-aos-duration="900">
                Galeri <span style="color: #F6C948;">Kita</span>
            </h1>
            <p class="lead text-white-50 mb-0 col-lg-8 mx-auto" data-aos="fade-up" data-aos-duration="1100"
                data-aos-delay="200">
                Jelajahi momen-momen berharga yang telah kami dokumentasikan
            </p>
        </div>
    </section>

    <!-- MAIN CONTENT -->
    <div class="container py-5">

        <!-- Albums Grid -->
        <div class="row g-4">

            @forelse ($albums as $index => $album)
                <div class="col-lg-4 col-md-6">
                    <a href="{{ route('galeri.show', $album->album_id) }}" class="text-decoration-none">
                        <div class="card h-100 border-0 shadow-sm rounded-4 hover-lift" data-aos="fade-up"
                            data-aos-delay="{{ $index * 80 }}">
                            <!-- Album Cover -->
                            <div class="card-thumbnail-wrapper position-relative" style="height: 240px; overflow: hidden;">
                                @php
                                    $cover = $album->cover->image_url ?? null;
                                @endphp

                                @if ($cover)
                                    <img src="{{ asset('storage/' . $cover) }}" class="w-100 h-100 object-fit-cover"
                                        alt="{{ $album->album_name }}">
                                    <i class="fas fa-image fallback-icon"></i>
                                @else
                                    <div
                                        class="d-flex align-items-center justify-content-center w-100 h-100 bg-light text-muted">
                                        <div class="text-center">
                                            <i class="fas fa-images fa-3x mb-2 opacity-50"></i>
                                            <p class="small m-0">No Cover</p>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <!-- Album Info -->
                            <div class="card-body text-center p-4">
                                <h5 class="fw-bold text-dark mb-1 text-truncate">
                                    {{ $album->album_name }}
                                </h5>
                                <p class="text-muted small mb-0">
                                    <i class="fas fa-images me-1"></i> Lihat Galeri
                                </p>
                            </div>
                        </div>
                    </a>
                </div>

            @empty
                <div class="col-12">
                    <div class="empty-state" data-aos="fade-up">
                        <div class="empty-icon">
                            <i class="fas fa-folder-open"></i>
                        </div>
                        <h3 class="empty-title">Belum Ada Album</h3>
                        <p class="empty-text">
                            Album kegiatan akan muncul di sini setelah ditambahkan oleh admin.
                        </p>
                    </div>
                </div>
            @endforelse

        </div>

    </div>
@endsection

@push('styles')
    <style>
        /* Card Hover Effect */
        .hover-lift {
            transition: all 0.3s ease;
        }

        .hover-lift:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(23, 92, 158, 0.15) !important;
        }

        .object-fit-cover {
            object-fit: cover;
        }

        /* Empty State */
        .empty-state {
            background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
            border-radius: 24px;
            padding: 80px 40px;
            text-align: center;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        }

        .empty-icon {
            width: 120px;
            height: 120px;
            margin: 0 auto 30px;
            background: linear-gradient(135deg, #175C9E 0%, #1a4d7a 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .empty-icon i {
            font-size: 48px;
            color: white;
            opacity: 0.9;
        }

        .empty-title {
            font-size: 28px;
            font-weight: 700;
            color: #175C9E;
            margin-bottom: 12px;
        }

        .empty-text {
            font-size: 16px;
            color: #6c757d;
            max-width: 500px;
            margin: 0 auto;
            line-height: 1.6;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .hero-section {
                min-height: 300px !important;
            }

            .display-3 {
                font-size: 2.5rem;
            }

            .album-cover {
                height: 220px;
            }

            .album-title {
                font-size: 18px;
            }

            .overlay-content i {
                font-size: 40px;
            }

            .overlay-content span {
                font-size: 16px;
            }

            .overlay-content i {
                font-size: 40px;
            }

            .overlay-content span {
                font-size: 16px;
            }

            .empty-state {
                padding: 60px 30px;
            }

            .empty-icon {
                width: 100px;
                height: 100px;
            }

            .empty-icon i {
                font-size: 40px;
            }

            .empty-title {
                font-size: 24px;
            }
        }
    </style>
@endpush
