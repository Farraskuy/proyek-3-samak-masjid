@extends('client.layout')

@section('title', 'Barang Hilang & Ditemukan - SAMAK-Kampus')

@section('content')
<section class="py-5">
    <div class="container">
        <div class="text-center mb-4">
            <h2 class="fw-bold text-success" style="font-family: 'Playfair Display', serif;">Barang Hilang & Ditemukan</h2>
            <p class="text-muted">Temukan barang yang hilang atau laporkan temuan di area masjid kampus.</p>
        </div>

        <div class="d-flex justify-content-end mb-4">
            <form method="GET" class="d-flex" style="max-width: 300px;">
                <input type="text" name="search" class="form-control me-2" placeholder="Cari barang..." value="{{ request('search') }}">
                <button type="submit" class="btn btn-outline-secondary">
                    <i class="fas fa-search"></i>
                </button>
            </form>
        </div>

        @if($items->isEmpty())
        <div class="alert alert-info text-center">
            <i class="fas fa-info-circle me-2"></i>
            Belum ada barang yang dilaporkan ditemukan.
        </div>
        @else
        <div class="row g-4">
            @foreach($items as $item)
            <div class="col-md-4">
                <div class="card h-100 shadow-sm border-0 rounded-3">
                    <div class="card-body d-flex flex-column">
                        <div class="text-center mb-3">
                            @php
                            $firstPhoto = $item->photos->first();
                            @endphp

                            @if($firstPhoto)
                            <img src="{{ asset('storage/' . $firstPhoto->image_url) }}"
                                alt="{{ $item->item_name }}"
                                class="img-fluid rounded"
                                style="height: 180px; object-fit: cover; width: 100%;">
                            @else
                            <div class="bg-light d-flex align-items-center justify-content-center rounded"
                                style="height: 180px;">
                                <i class="fas fa-box-open fs-1 text-muted"></i>
                            </div>
                            @endif
                        </div>

                        <div class="flex-grow-1">
                            <h5 class="card-title fw-bold mb-2">{{ $item->item_name }}</h5>
                            
                            <p class="text-muted small mb-1">
                                <i class="fas fa-map-marker-alt fa-fw me-1"></i> {{ $item->location_found }}
                            </p>
                            
                            <p class="text-muted small mb-3 text-truncate">
                                <i class="fas fa-align-left fa-fw me-1"></i> {{ $item->description }}
                            </p>
                            
                            <div class="mb-0">
                                @if($item->status === 'Tersedia')
                                <span class="badge bg-success rounded-pill small px-2 py-1">
                                    <i class="fas fa-check-circle me-1"></i> Tersedia
                                </span>
                                @elseif($item->status === 'Diambil')
                                <span class="badge bg-secondary rounded-pill small px-2 py-1">
                                    <i class="fas fa-box me-1"></i> Diambil
                                </span>
                                @else
                                <span class="badge bg-warning rounded-pill small px-2 py-1">
                                    <i class="fas fa-exclamation-circle me-1"></i> {{ $item->status }}
                                </span>
                                @endif
                            </div>

                        </div>

                        <div class="mt-3">
                            <button type="button" class="btn btn-sm btn-outline-success w-100" data-bs-toggle="modal" data-bs-target="#detailModal{{ $item->item_id }}">
                                Lihat Detail
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="d-flex justify-content-center mt-4">
            {{ $items->links() }}
        </div>
        @endif
    </div>
</section>

@foreach($items as $item)
<div class="modal fade" id="detailModal{{ $item->item_id }}" tabindex="-1" aria-labelledby="detailModalLabel{{ $item->item_id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header border-bottom-0">
                <h5 class="modal-title" id="detailModalLabel{{ $item->item_id }}">Detail Barang</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">

                <div class="text-center mb-4">
                    @if($item->photos->isEmpty())
                    <div class="bg-light d-flex align-items-center justify-content-center rounded" style="height: 300px; margin: 0 auto;">
                        <i class="fas fa-box-open fs-1 text-muted"></i>
                    </div>
                    @elseif($item->photos->count() === 1)
                    <img src="{{ asset('storage/' . $item->photos[0]->image_url) }}"
                        alt="{{ $item->item_name }}"
                        class="img-fluid rounded"
                        style="max-height: 400px; object-fit: contain; width: 100%;">
                    @else
                    <div id="photoCarousel{{ $item->item_id }}" class="carousel slide shadow-sm" data-bs-ride="carousel">
                        <div class="carousel-inner rounded">
                            @foreach($item->photos as $index => $photo)
                            <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                <img src="{{ asset('storage/' . $photo->image_url) }}"
                                    class="d-block w-100"
                                    style="max-height: 400px; object-fit: contain;">
                            </div>
                            @endforeach
                        </div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#photoCarousel{{ $item->item_id }}" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#photoCarousel{{ $item->item_id }}" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                    </div>
                    @endif
                </div>

                <h4 class="fw-bold mb-2">{{ $item->item_name }}</h4>
                <p class="text-muted mb-4">{{ $item->description }}</p>

                <h6 class="text-uppercase text-muted fw-bold small mb-3">Detail Penemuan</h6>
                <ul class="list-group list-group-flush mb-3">
                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-map-marker-alt text-primary me-3 fa-fw"></i>
                            <span class="fw-bold">Lokasi Ditemukan</span>
                        </div>
                        <span class="text-end">{{ $item->location_found }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-calendar-alt text-primary me-3 fa-fw"></i>
                            <span class="fw-bold">Tanggal Ditemukan</span>
                        </div>
                        <span class="text-end">{{ $item->created_at->format('d M Y H:i') }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-info-circle text-primary me-3 fa-fw"></i>
                            <span class="fw-bold">Status</span>
                        </div>
                        <span class="text-end">
                            @if($item->status === 'Tersedia')
                            <span class="badge bg-success rounded-pill px-3 py-2">Tersedia</span>
                            @elseif($item->status === 'Diambil')
                            <span class="badge bg-secondary rounded-pill px-3 py-2">Diambil</span>
                            @else
                            <span class="badge bg-warning rounded-pill px-3 py-2">{{ $item->status }}</span>
                            @endif
                        </span>
                    </li>
                </ul>

                @if($item->retrieved_by_name)
                <h6 class="text-uppercase text-muted fw-bold small mt-4 mb-3">Detail Pengambilan</h6>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-user-check text-success me-3 fa-fw"></i>
                            <span class="fw-bold">Diambil Oleh</span>
                        </div>
                        <span class="text-end">{{ $item->retrieved_by_name }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-calendar-check text-success me-3 fa-fw"></i>
                            <span class="fw-bold">Tanggal Diambil</span>
                        </div>
                        <span class="text-end">{{ $item->retrieved_at?->format('d M Y H:i') ?? '—' }}</span>
                    </li>
                </ul>
                @endif

            </div>
            <div class="modal-footer border-top-0">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endforeach

@endsection

@section('styles')
<style>
    .card {
        transition: transform 0.2s;
    }

    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
    }
</style>
@endsection