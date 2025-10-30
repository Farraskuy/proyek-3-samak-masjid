@extends('client.layout')

@section('title', 'Barang Hilang & Ditemukan - SAMAK-Kampus')

@section('content')
<section class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold text-success" style="font-family: 'Playfair Display', serif;">Barang Hilang & Ditemukan</h2>
            <p class="text-muted">Temukan barang yang hilang atau laporkan temuan di area masjid kampus.</p>
        </div>

        @if($items->isEmpty())
            <div class="alert alert-info text-center">
                <i class="fas fa-info-circle me-2"></i>
                Belum ada barang yang dilaporkan ditemukan.
            </div>
        @else
            <div class="row g-4">
                @foreach($items as $item)
                    <div class="col-md-6 col-lg-4">
                        <a href="#" class="text-decoration-none text-dark card-link">
                            <div class="card h-100 shadow-sm event-card">
                                <div class="card-body d-flex flex-column">
                                    <!-- Thumbnail Gambar -->
                                    <div class="card-thumbnail-wrapper mb-3">
                                        @if($item->featured_image_url)
                                            <img src="{{ asset('storage/' . $item->featured_image_url) }}"
                                                 alt="{{ $item->item_name }}"
                                                 class="img-fluid rounded"
                                                 style="height: 180px; object-fit: cover; width: 100%;"
                                                 onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                                        @endif
                                        <!-- Fallback ikon jika tidak ada gambar -->
                                        <i class="fas fa-box-open fallback-icon text-muted fs-1" 
                                           style="display: {{ $item->featured_image_url ? 'none' : 'block' }};"></i>
                                    </div>

                                    <!-- Detail Barang -->
                                    <div class="d-flex">
                                        <!-- Tanggal Ditemukan (mirip event) -->
                                        <div class="text-center me-3" style="min-width: 60px;">
                                            <div class="p-2 rounded-top" style="background-color: #175C9E;">
                                                <span class="d-block fs-6 text-white fw-bold">
                                                    {{ $item->created_at->format('M') }}
                                                </span>
                                            </div>
                                            <div class="bg-white p-2 rounded-bottom border border-top-0">
                                                <span class="d-block fs-4 fw-bold" style="color: #175C9E;">
                                                    {{ $item->created_at->format('d') }}
                                                </span>
                                            </div>
                                        </div>

                                        <!-- Info Barang -->
                                        <div>
                                            <h5 class="card-title fw-bold mb-2">{{ $item->item_name }}</h5>
                                            <p class="text-muted small mb-1">
                                                <i class="fas fa-map-marker-alt fa-fw me-1"></i> {{ $item->location_found }}
                                            </p>
                                            <p class="text-muted small mb-1">
                                                <i class="fas fa-align-left fa-fw me-1"></i> {{ Str::limit($item->description, 40) }}
                                            </p>
                                            <p class="fw-semibold small mb-0 text-success">
                                                <i class="fas fa-info-circle fa-fw me-1"></i> Status: {{ $item->status }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        @endif

        <div class="text-center mt-4">
            <a href="{{ url('/') }}" class="btn btn-outline-success px-4">
                <i class="fas fa-arrow-left me-2"></i> Kembali ke Beranda
            </a>
        </div>
    </div>
</section>
@endsection

@section('styles')
<style>
.card-thumbnail-wrapper {
    position: relative;
    height: 180px;
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: #f8f9fa;
    border-radius: 0.5rem;
    overflow: hidden;
}
.card-thumbnail-wrapper img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}
.fallback-icon {
    font-size: 3rem;
    color: #6c757d;
}
.event-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
}
</style>
@endsection