@extends('client.layout')

@section('content')

<!-- HERO SECTION -->
<section class="hero-section d-flex align-items-center position-relative overflow-hidden"
    style="background: linear-gradient(135deg, #175C9E 0%, #1a4d7a 100%); min-height: 300px;">

    <div class="container text-center py-5">
        <h1 class="display-3 fw-bold mb-4 text-white"
            data-aos="fade-down" data-aos-duration="900">
            Detail <span style="color: #F6C948;">Album</span>
        </h1>
    </div>
</section>

<!-- MAIN CONTENT -->
<div class="container py-5">

    <!-- Back Button -->
    <div class="mb-3" data-aos="fade-down">
        <a href="{{ route('galeri.index') }}" class="btn btn-outline-primary rounded-pill px-4 py-2"
        style="font-weight: 600;">
            <i class="fas fa-arrow-left me-2"></i> Kembali ke Galeri
        </a>
    </div>

    <!-- Judul Album -->
    <div class="mb-5" data-aos="fade-up">

        <h2 class="fw-bold mb-2" style="color:#175C9E;">
            {{ $album->album_name }}
        </h2>
    </div>

    <!-- Foto Grid -->
    <div class="mt-4" data-aos="fade-up">
        <div class="row g-3">

            @foreach ($album->photos as $index => $photo)
                <div class="col-lg-3 col-md-4 col-6">

                    <div class="gallery-item position-relative rounded-4 overflow-hidden shadow-sm"
                         style="cursor:pointer"
                         onclick="openLightbox({{ $index }})"
                         data-aos="zoom-in" data-aos-delay="{{ $index * 80 }}">

                        <img src="{{ asset('storage/' . $photo->image_url) }}"
                             class="img-fluid w-100"
                             style="height: 200px; object-fit: cover;">

                    </div>

                </div>
            @endforeach

        </div>
    </div>

</div>

<!-- Lightbox -->
<div id="lightbox" class="lightbox" onclick="closeLightbox(event)">
    
    <!-- Close Button -->
    <span class="lightbox-close" onclick="closeLightbox(event)">&times;</span>

    <!-- Main Image + Navigation -->
    <div class="lightbox-main">

        <span class="nav-arrow left" onclick="prevPhoto(event)">&#10094;</span>

        <div style="display: flex; flex-direction: column; align-items: center;">
            <img id="lightbox-image" class="lightbox-content">
            <div id="lightbox-caption" class="lightbox-caption"></div>
        </div>

        <span class="nav-arrow right" onclick="nextPhoto(event)">&#10095;</span>

    </div>

    <!-- Thumbnails -->
    <div class="lightbox-thumbnails mt-3">
        <div class="thumb-wrapper d-flex justify-content-center gap-2">
            @foreach ($album->photos as $index => $photo)
                <img class="thumb-img"
                     src="{{ asset('storage/' . $photo->image_url) }}"
                     onclick="jumpToPhoto({{ $index }})"
                     id="thumb-{{ $index }}">
            @endforeach
        </div>
    </div>

</div>


@endsection

@push('styles')
<style>
/* Grid Foto */
.gallery-item img {
    transition: 0.3s ease;
}
.gallery-item:hover img {
    transform: scale(1.08);
}

/* Lightboc Overlay */
.lightbox {
    display: none;
    position: fixed;
    z-index: 9999;
    inset: 0;
    background: rgba(0,0,0,0.9);
    padding: 40px 0;
    overflow: hidden;
}

/* Frame FIX */
.lightbox-main {
    width: 100%;
    height: 75vh;
    display: flex;
    justify-content: center;
    align-items: center;
    position: relative;
}

/* Foto di dalam Frame */
.lightbox-content {
    max-width: 90%;
    max-height: 100%;
    object-fit: contain; /* Foto menyesuaikan bingkai */
    border-radius: 10px;
    transition: opacity 0.2s ease;
}

/* Close Button */
.lightbox-close {
    position: absolute;
    top: 20px;
    right: 40px;
    color: #fff;
    font-size: 40px;
    cursor: pointer;
    transition: 0.2s;
}
.lightbox-close:hover {
    color: #F6C948;
}

/* Caption */
.lightbox-caption {
    color: #fff;
    text-align: center;
    margin-top: 10px;
    font-size: 18px;
    opacity: 0.85;
}

/* Navigation Arrows */
.nav-arrow {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    font-size: 50px;
    color: white;
    cursor: pointer;
    padding: 10px 18px;
    transition: 0.2s;
    user-select: none;
}
.nav-arrow.left { left: 20px; }
.nav-arrow.right { right: 20px; }

.nav-arrow:hover {
    color: #F6C948;
}

/* Thumbnails */
.lightbox-thumbnails {
    width: 100%;
    padding-top: 15px;
}
.thumb-wrapper {
    display: flex;
    justify-content: center;
    gap: 10px;
    overflow-x: auto;
}
.thumb-img {
    width: 80px;
    height: 55px;
    border-radius: 6px;
    object-fit: cover;
    opacity: 0.5;
    cursor: pointer;
    transition: 0.25s;
}
.thumb-img.active,
.thumb-img:hover {
    opacity: 1;
    transform: scale(1.05);
}
</style>
@endpush


@push('scripts')
<script>
let photos = @json($album->photos->map(fn($p) => [
    'url' => $p->image_url,
    'caption' => $p->caption
]));
let currentIndex = 0;

function openLightbox(index) {
    event.stopPropagation();
    currentIndex = index;
    updateLightbox();
    document.getElementById('lightbox').style.display = 'block';
}

function updateLightbox() {
    const img = document.getElementById('lightbox-image');
    img.src = "/storage/" + photos[currentIndex].url;

    document.getElementById("lightbox-caption").innerText =
        photos[currentIndex].caption && photos[currentIndex].caption !== "Cover Album"
            ? photos[currentIndex].caption : "";

    // Highlight thumbnail aktif
    document.querySelectorAll('.thumb-img').forEach(el => el.classList.remove('active'));
    const activeThumb = document.getElementById('thumb-' + currentIndex);
    if (activeThumb) activeThumb.classList.add('active');
}

function closeLightbox(event) {
    event.stopPropagation();
    document.getElementById('lightbox').style.display = 'none';
}

function nextPhoto(event) {
    event.stopPropagation();
    currentIndex = (currentIndex + 1) % photos.length;
    updateLightbox();
}

function prevPhoto(event) {
    event.stopPropagation();
    currentIndex = (currentIndex - 1 + photos.length) % photos.length;
    updateLightbox();
}

function jumpToPhoto(index) {
    event.stopPropagation();
    currentIndex = index;
    updateLightbox();
}
</script>
@endpush