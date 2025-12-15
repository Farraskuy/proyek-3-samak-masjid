@extends('client.layout')

@section('title', $post->title ?? 'Detail Post')

@push('styles')
    <style>
        * {
            font-family: "Poppins", "Lexend", sans-serif;
        }

        body {
            background: #fafafa;
        }

        .page-wrapper {
            padding-bottom: 140px;
        }

        /* HERO WRAPPER */
        .hero-wrapper {
            width: 100%;
            margin: 0 auto;
            margin-bottom: 20px;
            background: #f0f0f0;
            height: 420px;
        }

        /* HERO IMAGE */
        .hero-image {
            width: 100%;
            height: 420px;
            object-fit: cover;
            border-radius: 0;
            background: #f0f0f0;
        }

        /* SKELETON HERO */
        .hero-skeleton {
            width: 100%;
            height: 420px;
            min-height: 400px;
            border-radius: 0;
            background-color: #f0f0f0;
            background-size: 200% 100%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .hero-skeleton i {
            font-size: 4rem;
            color: #ccc;
        }

        /* TITLE */
        .article-title {
            font-size: 2.8rem;
            font-weight: 700;
            line-height: 1.18;
            max-width: 850px;
            margin-top: 25px;
            margin-bottom: 15px;
        }

        /* META */
        .article-meta {
            color: #666;
            font-size: 0.95rem;
            margin-bottom: 25px;
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
        }

        .dot::before {
            content: "•";
            margin: 0 6px;
            color: #999;
        }

        /* CONTENT */
        .article-content {
            font-size: 1.1rem;
            line-height: 1.75;
            color: #222;
        }

        .article-content img {
            max-width: 100%;
            border-radius: 10px;
            margin: 18px 0;
        }

        /* SIDEBAR */
        .sidebar-box {
            background: #fff;
            padding: 20px;
            border-radius: 14px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.05);
            position: sticky;
            top: 80px;
        }

        .sidebar-title {
            font-weight: 700;
            margin-bottom: 10px;
        }

        /* BACK BUTTON */
        .btn-back {
            padding: 6px 14px;
            background: white;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-weight: 600;
            color: #333;
            text-decoration: none;
            margin-bottom: 25px;
            display: inline-block;
        }

        /* RECOMMENDATIONS */
        .recommend-title {
            font-size: 1.5rem;
            font-weight: 600;
            margin-top: 60px;
            margin-bottom: 20px;
        }

        .recommend-card {
            background: #fff;
            border-radius: 14px;
            overflow: hidden;
            transition: 0.25s ease-in-out;
            height: 100%;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
            border: 0 !important;
            display: flex;
            flex-direction: column;
        }

        .recommend-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 28px rgba(0, 0, 0, 0.12);
        }

        .recommend-card .card-thumbnail-wrapper {
            width: 100%;
            height: 150px;
            overflow: hidden;
            border-radius: 0;
            background: #f1f1f1;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            margin-bottom: 0;
        }

        .recommend-card .card-thumbnail-wrapper img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 0;
        }

        .recommend-card .card-body {
            padding: 16px;
            display: flex;
            flex-direction: column;
            flex: 1;
        }

        .recommend-card .fallback-icon {
            font-size: 3rem;
            color: #999;
        }

        .no-data-box {
            text-align: center;
            padding: 30px 0;
            opacity: 0.7;
        }

        /* Desktop: Sidebar di kanan content */
        @media (min-width: 992px) {
            .content-column {
                order: 1;
            }

            .sidebar-column {
                order: 2;
            }
        }

        /* Mobile: Sidebar di bawah rekomendasi */
        @media (max-width: 991px) {
            .content-column {
                order: 1;
            }

            .sidebar-column {
                order: 3;
                margin-top: 30px;
            }

            .recommend-section {
                order: 2;
            }
        }
    </style>
@endpush

@section('content')
    <div class="page-wrapper">
        {{-- HERO --}}
        <div class="hero-wrapper">
            @if ($post->featured_image_url)
                <img src="{{ asset('storage/' . $post->featured_image_url) }}" class="hero-image" alt="{{ $post->title }}"
                    onerror="this.style.display='none'; document.getElementById('hero-fallback').style.display='flex';">
                <div id="hero-fallback" class="hero-skeleton" style="display: none;"><i class="fas fa-image"></i></div>
            @else
                <div class="hero-skeleton"><i class="fas fa-image"></i></div>
            @endif
        </div>

        <div class="container">
            <a href="{{ route('client.berita') }}" class="btn-back">← Back</a>

            {{-- TITLE --}}
            <h1 class="article-title">{{ $post->title }}</h1>

            {{-- META --}}
            <div class="article-meta">
                <span class="dot"></span>
                <span>{{ $post->created_at->format('M d, Y') }}</span>
                <span class="dot"></span>
                <span>{{ $post->kategori }}</span>
            </div>

            <div class="row">
                {{-- LEFT CONTENT --}}
                <div class="col-lg-8 content-column">
                    <div class="article-content">{!! $content_html !!}</div>
                </div>

                {{-- RIGHT SIDEBAR - Desktop: di kanan, Mobile: di bawah rekomendasi --}}
                <div class="col-lg-4 sidebar-column">
                    <div class="sidebar-box">
                        <h5 class="sidebar-title">Informasi Artikel</h5>
                        <p><strong>Penulis:</strong> {{ optional($post->creator)->full_name ?? 'N/A' }}</p>
                        <p><strong>Kategori:</strong> {{ $post->kategori }}</p>
                        <p><strong>Tanggal Dibuat:</strong> {{ $post->created_at->format('d M Y') }}</p>
                        <p><strong>Tanggal diupdate:</strong> {{ $post->updated_at->format('d M Y') }}</p>
                        @if ($post->published_at)
                            <p><strong>Tanggal Dipublish:</strong> {{ $post->published_at->format('d M Y') }}</p>
                        @endif
                    </div>
                </div>
            </div>

            {{-- REKOMENDASI --}}
            <div class="recommend-section">
                <h3 class="recommend-title">Rekomendasi Lainnya</h3>
                <div class="row">
                    @php
                        $recommendations = \App\Models\Postingan::where('kategori', $post->kategori)
                            ->where('id', '!=', $post->id)
                            ->where('status', '=', 'published')
                            ->orderBy('created_at', 'desc')
                            ->limit(3)
                            ->get();
                    @endphp
                    @if ($recommendations->count() == 0)
                        <div class="no-data-box">
                            <p>Tidak ada rekomendasi tersedia.</p>
                        </div>
                    @endif
                    @foreach ($recommendations as $rec)
                        <div class="col-md-4 mb-3">
                            <a href="{{ route('client.berita.detail', $rec->slug ?? $rec->id) }}"
                                class="text-decoration-none text-dark">
                                <div class="card recommend-card shadow-sm">
                                    <div class="card-thumbnail-wrapper">
                                        <img id="rec-img-{{ $rec->id }}"
                                            src="{{ asset('storage/' . $rec->featured_image_url) }}"
                                            alt="{{ $rec->title }}">
                                        <i class="fas fa-image fallback-icon" id="rec-icon-{{ $rec->id }}"></i>
                                    </div>
                                    <div class="card-body">
                                        <span class="badge mb-2"
                                            style="background-color:#CE9138;">{{ ucfirst($rec->kategori ?? 'Umum') }}</span>
                                        <h6 class="card-title fw-bold">{{ Str::limit($rec->title, 50) }}</h6>
                                        <p class="card-text text-muted small">
                                            {{ Str::limit(strip_tags($rec->content), 60) }}</p>
                                        <small class="text-muted mt-auto">{{ $rec->created_at->format('d M Y') }}</small>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection
