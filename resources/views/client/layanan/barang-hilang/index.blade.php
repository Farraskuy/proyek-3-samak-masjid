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
                        <div class="card h-100 shadow-sm border border-light rounded-3">
                            @if($item->featured_image_url)
                                <img src="{{ asset('storage/' . $item->featured_image_url) }}"
                                     class="card-img-top"
                                     alt="{{ $item->item_name }}"
                                     style="height: 200px; object-fit: cover;">
                            @else
                                <div class="bg-light text-center py-5">
                                    <i class="fas fa-box-open text-muted fs-1"></i>
                                    <p class="mt-2 text-muted">Tidak ada gambar</p>
                                </div>
                            @endif
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title fw-bold">{{ $item->item_name }}</h5>
                                <p class="card-text text-muted flex-grow-1">{{ Str::limit($item->description, 100) }}</p>
                                <div class="mt-auto">
                                    <small class="text-muted d-block">
                                        <i class="fas fa-map-marker-alt me-1"></i> {{ $item->location_found }}
                                    </small>
                                    <small class="text-success d-block mt-1">
                                        <i class="fas fa-clock me-1"></i> Ditemukan: {{ $item->created_at->format('d M Y') }}
                                    </small>
                                </div>
                            </div>
                        </div>
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