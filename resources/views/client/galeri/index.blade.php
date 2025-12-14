@extends('client.layout')

@section('title', 'Galeri Kita')

@push('styles')
    <style>
        * {
            font-family: 'Poppins', "Lexend", sans-serif;
        }

        .bg-pattern {
            background-image: radial-gradient(circle at 1px 1px, rgba(255, 255, 255, 0.15) 1px, transparent 1px);
            background-size: 25px 25px;
        }

        /* Article Card - Same as Postingan */
        .article-card {
            transition: .25s ease-in-out;
            border: 0 !important;
            border-radius: 14px;
            overflow: hidden;
            background: white;
            box-shadow: 0 2px 12px rgba(0, 0, 0, .08);
        }

        .article-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 28px rgba(0, 0, 0, .12) !important;
        }

        .card-thumbnail-wrapper {
            width: 100%;
            height: 180px;
            overflow: hidden;
            border-radius: 8px;
            background: #f1f1f1;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }

        .card-thumbnail-wrapper img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 8px;
        }

        .fallback-icon {
            font-size: 3.5rem;
            color: #999;
            display: none;
            text-align: center;
            position: absolute;
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

@section('content')
    <!-- HERO SECTION with Dot Pattern -->
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
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="{{ $index * 80 }}">
                    <a href="{{ route('galeri.show', $album->album_id) }}" class="text-decoration-none text-dark card-link">
                        <div class="card h-100 article-card">
                            <div class="card-body d-flex flex-column">
                                <!-- Album Cover -->
                                <div class="card-thumbnail-wrapper mb-3">
                                    @php
                                        $cover = $album->cover->image_url ?? null;
                                    @endphp

                                    @if ($cover)
                                        <img src="{{ asset('storage/' . $cover) }}" alt="{{ $album->album_name }}">
                                        <i class="fas fa-image fallback-icon"></i>
                                    @else
                                        <div class="text-center">
                                            <i class="fas fa-images fa-3x mb-2 opacity-50 text-muted"></i>
                                            <p class="small m-0 text-muted">No Cover</p>
                                        </div>
                                    @endif
                                </div>

                                <!-- Album Info - Text Left like Postingan -->
                                <div>
                                    <span class="badge mb-2" style="background-color:#CE9138;">
                                        <small class="text-white">
                                            <i class="fas fa-image me-1"></i>{{ $album->photos->count() ?? 0 }} Foto
                                        </small>
                                    </span>
                                    <h5 class="m-0 card-title fw-bold text-truncate">{{ $album->album_name }}</h5>
                                    @if ($album->description)
                                        <p class="card-text text-muted small">{{ Str::limit($album->description, 80) }}</p>
                                    @endif
                                </div>

                                <!-- Footer - Same style as Postingan -->
                                <div class="mt-auto pt-3">
                                    <p class="text-muted">
                                        <span class="fas fa-calendar me-1"></span> {{ $album->created_at->format('d M Y') }}
                                    </p>
                                    <span class="fw-semibold" style="color:#175C9E;">
                                        Lihat Album <i class="fas fa-arrow-right ms-1"></i>
                                    </span>
                                </div>
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
