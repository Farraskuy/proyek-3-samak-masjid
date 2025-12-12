@extends('client.layout')

@section('title', 'Berita & Postingan')

@push('styles')
    <style>
        * {
            font-family: 'Poppins', "Lexend", sans-serif;
        }

        .bg-pattern {
            background-image: radial-gradient(circle at 1px 1px, rgba(255, 255, 255, 0.15) 1px, transparent 1px);
            background-size: 25px 25px;
        }

        /* Hero Thumbnail */
        .hero-thumb {
            width: 100%;
            height: 320px;
            object-fit: cover;
            border-radius: 16px;
            background: #f5f5f5;
        }

        /* Skeleton jika hero tidak ada */
        .hero-skeleton {
            height: 320px;
            border-radius: 16px;
            background: linear-gradient(90deg, #e9ecef 0%, #f8f9fa 50%, #e9ecef 100%);
            background-size: 200%;
        }

        /* Article Card */
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

        /* Filter pills */
        .filter-pill {
            padding: 9px 18px;
            border-radius: 30px;
            border: 1px solid #ddd;
            background: white;
            cursor: pointer;
            transition: .2s;
            font-size: 14px;
        }

        .filter-pill.active {
            background: #175C9E;
            border-color: #175C9E;
            color: white;
            font-weight: 600;
        }

        /* Empty illustration */
        .empty-illus {
            max-width: 220px;
            opacity: .9;
        }
    </style>
@endpush


@section('content')

    {{-- HERO --}}
    <section class="py-5 bg-pattern" style="background-color:#175C9E; height:320px; display:flex; align-items:center;">
        <div class="container text-center">
            <h1 class="display-5 fw-bold text-white mb-3" data-aos="fade-up" data-aos-duration="900">Berita & Postingan</h1>
            <p class="text-white-50 lead mb-0 col-lg-8 mx-auto" data-aos="fade-up" data-aos-duration="1100"
                data-aos-delay="200">Temukan kabar terbaru dan artikel dakwah masjid kampus.</p>
        </div>
    </section>


    <section class="py-5" style="padding-bottom:120px !important;">
        <div class="container">

            {{-- Search Bar --}}
            <div class="d-flex justify-content-center mb-4">
                <form method="GET" class="w-100 d-flex shadow-sm rounded-pill px-3 py-2"
                    style="max-width:800px; border:1px solid #e5e5e5;">
                    <input type="text" name="keyword" class="form-control border-0 shadow-0"
                        placeholder="Cari berita atau kata kunci..." value="{{ request('keyword') }}">
                    <button class="btn btn-link text-success">
                        <i class="fas fa-search fs-5"></i>
                    </button>
                </form>
            </div>

            {{-- Filter --}}
            <div class="mb-4 text-center">
                <div class="d-flex justify-content-center gap-2 flex-wrap">

                    <a href="{{ route('client.berita') }}" class="text-decoration-none">
                        <span class="filter-pill {{ !request('filter') ? 'active' : '' }}">Semua</span>
                    </a>

                    <a href="?filter=artikel" class="text-decoration-none">
                        <span class="filter-pill {{ request('filter') == 'artikel' ? 'active' : '' }}">Artikel Dakwah</span>
                    </a>

                    <a href="?filter=berita" class="text-decoration-none">
                        <span class="filter-pill {{ request('filter') == 'berita' ? 'active' : '' }}">Berita</span>
                    </a>

                    <a href="?filter=tausiyah" class="text-decoration-none">
                        <span class="filter-pill {{ request('filter') == 'tausiyah' ? 'active' : '' }}">Tausiyah
                            Singkat</span>
                    </a>
                </div>
            </div>

            {{-- Empty --}}
            @if ($data_posts->count() == 0)

                <div class="text-center py-5">
                    <img src="{{ asset('assets/images/no-data.png') }}" class="empty-illus mb-3" alt="No data available">
                    <p class="text-muted">Belum ada postingan ditemukan.</p>
                </div>
            @else
                {{-- Loop --}}
                <div class="row g-4">

                    @foreach ($data_posts as $post)
                        <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-duration="700">
                            <a href="{{ route('client.berita.detail', $post->slug ?? $post->id) }}"
                                class="text-decoration-none text-dark card-link">
                                <div class="card h-100 shadow-sm article-card">
                                    <div class="card-body d-flex flex-column">

                                        {{-- Thumbnail --}}
                                        <div class="card-thumbnail-wrapper mb-3">
                                            <img id="img-{{ $post->id }}"
                                                src="{{ asset('storage/' . $post->featured_image_url) }}"
                                                alt="{{ $post->title }}">
                                            <i class="fas fa-image fallback-icon" id="icon-{{ $post->id }}"></i>
                                        </div>

                                        {{-- Content --}}
                                        <div>
                                            <span class="badge mb-2" style="background-color:#CE9138;">
                                                {{ ucfirst($post->kategori ?? 'Umum') }}
                                            </span>
                                            <h5 class="card-title fw-bold">{{ $post->title }}</h5>
                                            <p class="card-text text-muted small">
                                                {{ Str::limit($post->keterangan ?? strip_tags($post->content), 100) }}</p>
                                        </div>

                                        {{-- Footer --}}
                                        <div class="mt-auto pt-3">
                                              
                                            <small class="text-muted">{{ optional($post->creator)->full_name ?? 'N/a' }} •
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

                {{-- Pagination --}}
                <div class="d-flex justify-content-center mt-4">
                    {{ $data_posts->links() }}
                </div>

            @endif

        </div>
    </section>

@endsection
