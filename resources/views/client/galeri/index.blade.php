@extends('client.layout')

@section('content')

<!-- HERO SECTION -->
<section class="hero-section d-flex align-items-center position-relative overflow-hidden"
    style="background: linear-gradient(135deg, #175C9E 0%, #1a4d7a 100%); min-height: 400px;">

    <div class="container position-relative" style="z-index: 2;">
        <div class="row align-items-center justify-content-center text-center py-5">
            <div class="col-lg-8">
                <h1 class="display-3 fw-bold mb-4 text-white"
                    data-aos="fade-down" data-aos-duration="900">
                    Galeri <span style="color: #F6C948;">Kita</span>
                </h1>
            </div>
        </div>
    </div>
</section>

<!-- MAIN CONTENT -->
<div class="container py-5">

    <!-- Section Title -->
    @if($albums->count() > 0)
    <div class="text-center mb-5" data-aos="fade-up">
        <h2 class="fw-bold mb-2" style="color: #175C9E;">
            Koleksi Album Kami
        </h2>
        <p class="text-muted">
            Jelajahi momen-momen berharga yang telah kami dokumentasikan
        </p>
    </div>
    @endif

    <!-- Albums Grid -->
    <div class="row g-4">

        @forelse ($albums as $index => $album)

            <div class="col-lg-4 col-md-6">
                <a href="{{ route('galeri.show', $album->album_id) }}" class="text-decoration-none">
                    <div class="album-card" data-aos="fade-up" data-aos-delay="{{ $index * 80 }}">

                        <!-- Album Cover -->
                        <div class="album-cover">
                            @php
                                $cover = $album->cover->image_url ?? null;
                            @endphp

                            @if ($cover)
                                <img src="{{ asset('storage/' . $cover) }}"
                                     class="album-image"
                                     alt="{{ $album->album_name }}">
                            @else
                                <div class="album-placeholder">
                                    <i class="fas fa-image"></i>
                                    <span>No Cover</span>
                                </div>
                            @endif

                            <!-- Overlay -->
                            <div class="album-overlay"></div>
                        </div>

                        <!-- Album Info -->
                        <div class="album-info">
                            <h5 class="album-title">
                                {{ $album->album_name }}
                            </h5>
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
/* Album Card */
.album-card {
    background: white;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    transition: all 0.4s ease;
    height: 100%;
    display: flex;
    flex-direction: column;
}

.album-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 12px 40px rgba(23, 92, 158, 0.2);
}

/* Album Cover */
.album-cover {
    position: relative;
    width: 100%;
    height: 280px;
    overflow: hidden;
    background: #f8f9fa;
}

.album-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.5s ease;
}

.album-card:hover .album-image {
    transform: scale(1.1);
}

.album-placeholder {
    width: 100%;
    height: 100%;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #175C9E 0%, #1a4d7a 100%);
    color: white;
}

.album-placeholder i {
    font-size: 48px;
    opacity: 0.5;
    margin-bottom: 12px;
}

.album-placeholder span {
    font-size: 14px;
    opacity: 0.7;
}

/* Album Overlay */
.album-overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(to bottom, transparent 0%, rgba(23, 92, 158, 0.95) 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: opacity 0.4s ease;
}

.album-card:hover .album-overlay {
    opacity: 1;
}

/* Album Info */
.album-info {
    padding: 24px;
    flex: 1;
    display: flex;
    flex-direction: column;
    justify-content: center;
}

.album-title {
    font-size: 20px;
    font-weight: 700;
    color: #175C9E;
    margin: 0;
    line-height: 1.4;
    text-align: center;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
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