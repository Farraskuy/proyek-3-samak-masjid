@extends('client.layout')

@section('title', 'Barang Hilang & Ditemukan - SAMAK-Kampus')

@push('styles')
<style>
    * {
        font-family: 'Poppins', "Lexend", sans-serif;
    }

    /* Pattern soft elegan */
    .bg-pattern {
        background-image: radial-gradient(circle at 1px 1px, rgba(255, 255, 255, 0.15) 1px, transparent 1px);
        background-size: 25px 25px;
    }

    /* Hover card */
    .feature-card {
        transition: all 0.3s ease-in-out;
        border: 0;
    }

    .feature-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 1rem 3rem rgba(0, 0, 0, 0.15) !important;
    }

    /* Thumbnail wrapper */
    .card-thumbnail-wrapper {
        position: relative;
        height: 180px;
        border-radius: 12px;
        overflow: hidden;
        background-color: #f8f9fa;
    }

    .card-thumbnail-wrapper img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .fallback-icon {
        display: none;
        font-size: 3rem;
        color: #adb5bd;
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
    }

    /* Modal carousel */
    .carousel-inner img {
        max-height: 400px;
        object-fit: contain;
        background: #f8f9fa;
    }
</style>
@endpush

@section('content')

{{-- HERO SECTION --}}
<section class="py-5 hero-animate bg-pattern"
    style="background-color: #175C9E; height: 320px; display:flex; align-items:center;">
    <div class="container text-center">
        <h1 class="display-5 fw-bold text-white mb-3"
            data-aos="fade-down" data-aos-duration="800">
            Barang Hilang & Ditemukan
        </h1>
        <p class="text-white-50 lead"
            data-aos="fade-up" data-aos-delay="200">
            Temukan barang yang hilang atau laporkan temuan di area masjid kampus.
        </p>
    </div>
</section>


<section class="py-5">
    <div class="container">

        {{-- PENCARIAN --}}
        <div class="d-flex justify-content-end mb-4" data-aos="fade-left">
            <form method="GET" class="d-flex shadow-sm rounded-3 overflow-hidden" style="max-width: 320px;">
                <input type="text" name="search" class="form-control border-0"
                       placeholder="Cari barang..."
                       value="{{ request('search') }}">
                <button type="submit" class="btn btn-primary px-3" style="background:#175C9E;border-color:#175C9E;">
                    <i class="fas fa-search"></i>
                </button>
            </form>
        </div>

        {{-- KOSONG --}}
        @if($items->isEmpty())
            <div class="alert alert-info text-center shadow-sm rounded-4 py-4" data-aos="fade-up">
                <i class="fas fa-info-circle me-2 fa-lg"></i>
                Belum ada barang yang dilaporkan ditemukan.
            </div>

        {{-- LIST BARANG --}}
        @else
        <div class="row g-4">
            @foreach($items as $item)
            <div class="col-md-4" data-aos="zoom-in" data-aos-duration="700">

                <div class="card h-100 shadow-sm feature-card rounded-4">
                    <div class="card-body d-flex flex-column">

                        {{-- Thumbnail --}}
                        <div class="card-thumbnail-wrapper mb-3">
                            @php $firstPhoto = $item->photos->first(); @endphp

                            @if($firstPhoto)
                                <img src="{{ asset('storage/' . $firstPhoto->image_url) }}"
                                     alt="{{ $item->item_name }}"
                                     onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                                <i class="fas fa-box-open fallback-icon"></i>
                            @else
                                <i class="fas fa-box-open fallback-icon" style="display:block;"></i>
                            @endif
                        </div>

                        {{-- Content --}}
                        <h5 class="fw-bold">{{ $item->item_name }}</h5>

                        <p class="text-muted small mb-1">
                            <i class="fas fa-map-marker-alt me-1"></i>
                            {{ $item->location_found }}
                        </p>

                        <p class="text-muted small mb-2 text-truncate">
                            <i class="fas fa-align-left me-1"></i>
                            {{ $item->description }}
                        </p>

                        {{-- Status --}}
                        <div class="mb-2">
                            @if($item->status === 'Tersedia')
                                <span class="badge bg-success px-2 py-1">
                                    <i class="fas fa-check-circle me-1"></i> Tersedia
                                </span>
                            @elseif($item->status === 'Diambil')
                                <span class="badge bg-secondary px-2 py-1">
                                    <i class="fas fa-box me-1"></i> Diambil
                                </span>
                            @else
                                <span class="badge bg-warning px-2 py-1">
                                    <i class="fas fa-exclamation-circle me-1"></i> {{ $item->status }}
                                </span>
                            @endif
                        </div>

                        {{-- Button --}}
                        <button class="btn w-100 mt-auto rounded-3 btn-outline-primary"
                                style="border-color:#175C9E;color:#175C9E"
                                data-bs-toggle="modal"
                                data-bs-target="#detailModal{{ $item->item_id }}">
                            Lihat Detail
                        </button>

                    </div>
                </div>
            </div>
            @endforeach
        </div>

        {{-- PAGINATION --}}
        <div class="d-flex justify-content-center mt-4">
            {{ $items->links() }}
        </div>
        @endif
    </div>
</section>


{{-- MODAL DETAIL --}}
@foreach($items as $item)
<div class="modal fade" id="detailModal{{ $item->item_id }}" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content rounded-4 shadow">

            <div class="modal-header border-0">
                <h5 class="fw-bold">{{ $item->item_name }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                {{-- Foto --}}
                <div class="text-center mb-3">
                    @if($item->photos->isEmpty())
                        <div class="bg-light rounded d-flex align-items-center justify-content-center"
                             style="height:300px;">
                             <i class="fas fa-box-open fs-1 text-muted"></i>
                        </div>
                    @elseif($item->photos->count() === 1)
                        <img src="{{ asset('storage/' . $item->photos[0]->image_url) }}" class="img-fluid rounded">
                    @else
                    <div id="carousel{{ $item->item_id }}" class="carousel slide rounded" data-bs-ride="carousel">
                        <div class="carousel-inner">
                            @foreach($item->photos as $index => $photo)
                                <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                                    <img src="{{ asset('storage/' . $photo->image_url) }}"
                                         class="d-block w-100">
                                </div>
                            @endforeach
                        </div>
                        <button class="carousel-control-prev" type="button"
                                data-bs-target="#carousel{{ $item->item_id }}" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon"></span>
                        </button>
                        <button class="carousel-control-next" type="button"
                                data-bs-target="#carousel{{ $item->item_id }}" data-bs-slide="next">
                            <span class="carousel-control-next-icon"></span>
                        </button>
                    </div>
                    @endif
                </div>

                {{-- Detail --}}
                <p class="text-muted">{{ $item->description }}</p>

                <ul class="list-group list-group-flush">
                    <li class="list-group-item px-0 d-flex justify-content-between">
                        <span><i class="fas fa-map-marker-alt text-primary me-2"></i> Lokasi</span>
                        <strong>{{ $item->location_found }}</strong>
                    </li>
                    <li class="list-group-item px-0 d-flex justify-content-between">
                        <span><i class="fas fa-calendar text-primary me-2"></i> Ditemukan</span>
                        <strong>{{ $item->created_at->format('d M Y H:i') }}</strong>
                    </li>

                    @if($item->retrieved_by_name)
                    <li class="list-group-item px-0 d-flex justify-content-between">
                        <span><i class="fas fa-user-check text-success me-2"></i> Diambil Oleh</span>
                        <strong>{{ $item->retrieved_by_name }}</strong>
                    </li>
                    @endif
                </ul>

            </div>

            <div class="modal-footer border-0">
                <button class="btn btn-secondary px-4" data-bs-dismiss="modal">Tutup</button>
            </div>

        </div>
    </div>
</div>
@endforeach

@endsection
