@extends('client.layout')

@section('title', 'Barang Hilang & Ditemukan - SAMAK-Kampus')

@section('content')
<section class="py-5">
    <div class="container">
        <!-- Header -->
        <div class="text-center mb-4">
            <h2 class="fw-bold text-success" style="font-family: 'Playfair Display', serif;">Barang Hilang & Ditemukan</h2>
            <p class="text-muted">Temukan barang yang hilang atau laporkan temuan di area masjid kampus.</p>
        </div>

        <!-- Search Bar -->
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
            <!-- Kartu Barang -->
            <div class="row g-4">
                @foreach($items as $item)
                    <div class="col-md-4">
                        <div class="card h-100 shadow-sm border border-light rounded-3">
                            <!-- Gambar -->
                            <div class="card-body d-flex flex-column">
                                <div class="text-center mb-3">
                                    @if($item->featured_image_url)
                                        <img src="{{ asset('storage/' . $item->featured_image_url) }}"
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

                                <!-- Informasi Barang -->
                                <div class="flex-grow-1">
                                    <h5 class="card-title fw-bold">{{ $item->item_name }}</h5>
                                    <p class="text-muted small mb-1">
                                        <i class="fas fa-map-marker-alt me-1"></i> {{ $item->location_found }}
                                    </p>
                                    <p class="text-muted small mb-1">
                                        <i class="fas fa-align-left me-1"></i> {{ Str::limit($item->description, 40) }}
                                    </p>
                                    <p class="fw-semibold small mb-0 text-success">
                                        <i class="fas fa-info-circle me-1"></i> Status: {{ $item->status }}
                                    </p>
                                </div>

                                <!-- Tombol Lihat Detail -->
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

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4">
                {{ $items->links() }}
            </div>
        @endif
    </div>
</section>

<!-- Modal Detail untuk Setiap Barang -->
@foreach($items as $item)
<div class="modal fade" id="detailModal{{ $item->item_id }}" tabindex="-1" aria-labelledby="detailModalLabel{{ $item->item_id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="detailModalLabel{{ $item->item_id }}">Detail Barang: {{ $item->item_name }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        @if($item->featured_image_url)
                            <img src="{{ asset('storage/' . $item->featured_image_url) }}"
                                 alt="{{ $item->item_name }}"
                                 class="img-fluid rounded"
                                 style="max-height: 300px; object-fit: contain; width: 100%;">
                        @else
                            <div class="bg-light d-flex align-items-center justify-content-center rounded"
                                 style="height: 300px;">
                                <i class="fas fa-box-open fs-1 text-muted"></i>
                            </div>
                        @endif
                    </div>
                    <div class="col-md-6">
                        <h6 class="fw-bold">Nama Barang:</h6>
                        <p>{{ $item->item_name }}</p>

                        <h6 class="fw-bold">Lokasi Ditemukan:</h6>
                        <p>{{ $item->location_found }}</p>

                        <h6 class="fw-bold">Deskripsi:</h6>
                        <p>{{ $item->description }}</p>

                        <h6 class="fw-bold">Status:</h6>
                        <p>
                            @if($item->status === 'Tersedia')
                                <span class="badge bg-success">Tersedia</span>
                            @elseif($item->status === 'Diambil')
                                <span class="badge bg-secondary">Diambil</span>
                            @else
                                <span class="badge bg-warning">{{ $item->status }}</span>
                            @endif
                        </p>

                        <h6 class="fw-bold">Tanggal Ditemukan:</h6>
                        <p>{{ $item->created_at->format('d M Y H:i') }}</p>

                        @if($item->retrieved_by_name)
                            <h6 class="fw-bold">Diambil Oleh:</h6>
                            <p>{{ $item->retrieved_by_name }}</p>
                            <h6 class="fw-bold">Tanggal Diambil:</h6>
                            <p>{{ $item->retrieved_at?->format('d M Y H:i') ?? '—' }}</p>
                        @endif
                    </div>
                </div>
            </div>
            <div class="modal-footer">
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
    box-shadow: 0 10px 20px rgba(0,0,0,0.1);
}
</style>
@endsection