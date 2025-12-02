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
    height: auto;            
    max-height: 85vh;        
    display: flex;
    justify-content: center;
    align-items: center;
    position: relative;
}

/* Lightbox Content */
.lightbox-content {
    max-width: 90vw;
    max-height: 80vh;  
    object-fit: contain;
    border-radius: 10px;
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
    background: rgba(0, 0, 0, 0.6); 
    padding: 10px 15px; 
    border-radius: 8px; 
    display: inline-block; 
    font-family: 'Poppins', sans-serif; 
    animation: fadeIn 0.5s ease; 
}

/* Animasi Fade-In */
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
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