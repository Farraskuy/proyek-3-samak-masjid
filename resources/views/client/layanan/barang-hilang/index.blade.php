@extends('client.layout')

@section('title', 'Barang Hilang & Ditemukan - SAMAK-Kampus')

@section('content')
<section class="py-5">
    <div class="container">

        {{-- HEAD TITLE --}}
        <div class="text-center mb-5">
            <h2 class="fw-bold" style="font-family: 'Playfair Display', serif; color:#1B6B43; letter-spacing:0.5px;">
                Barang Hilang & Ditemukan
            </h2>
            <p class="text-muted mt-2" style="font-size: 15px;">
                Temukan barang hilang atau laporkan temuan di area masjid kampus.
            </p>
        </div>

        {{-- SEARCH BAR PANJANG DI TENGAH --}}
        <div class="d-flex justify-content-center mb-4">
            <form method="GET" class="w-100 d-flex shadow-sm rounded-pill px-3 py-2"
                style="max-width: 800px; background: #ffffff; border:1px solid #e5e5e5;">
                <input type="text" name="search" class="form-control border-0 shadow-0"
                    placeholder="Cari barang disini..."
                    value="{{ request('search') }}" style="background:none; flex: 1;">
                <button type="submit" class="btn btn-link text-success">
                    <i class="fas fa-search fs-5"></i>
                </button>
            </form>
        </div>

        {{-- KATEGORI IKON --}}
        <div class="d-flex justify-content-center mb-4">
            <div class="row g-3 w-100" style="max-width: 800px;">
                <div class="col-md-2 text-center">
                    <a href="{{ route('layanan.barang-hilang') }}"
                        class="text-decoration-none text-dark">
                        <div class="bg-light p-3 rounded-4 mb-2">
                            <i class="fas fa-th-large fs-2 text-success"></i>
                        </div>
                        <h6 class="fw-bold">Semua</h6>
                    </a>
                </div>
                @foreach([
                ['name' => 'Kendaraan', 'icon' => 'fa-car', 'value' => 'kendaraan'],
                ['name' => 'Elektronik', 'icon' => 'fa-mobile-alt', 'value' => 'elektronik'],
                ['name' => 'Aksesoris', 'icon' => 'fa-glasses', 'value' => 'aksesoris'],
                ['name' => 'Dokumen', 'icon' => 'fa-file-alt', 'value' => 'dokumen'],
                ['name' => 'Lain-lain', 'icon' => 'fa-boxes', 'value' => 'lain-lain']
                ] as $cat)
                <div class="col-md-2 text-center">
                    <a href="{{ route('layanan.barang-hilang') }}?category={{ $cat['value'] }}"
                        class="text-decoration-none text-dark">
                        <div class="bg-light p-3 rounded-4 mb-2">
                            <i class="fas {{ $cat['icon'] }} fs-2 text-success"></i>
                        </div>
                        <h6 class="fw-bold">{{ $cat['name'] }}</h6>
                    </a>
                </div>
                @endforeach
            </div>
        </div>

        {{-- EMPTY LIST --}}
        @if($items->isEmpty())
        <div class="alert alert-light shadow-sm border text-center py-4">
            <i class="fas fa-info-circle me-2"></i>
            Belum ada barang yang dilaporkan ditemukan.
        </div>

        @else

        {{-- LIST OF CARDS --}}
        <div class="row g-4">
            @foreach($items as $item)
            <div class="col-md-4">
                <div class="card h-100 rounded-4 shadow-sm border-0 item-card">

                    {{-- IMAGE --}}
                    <div class="p-3">
                        @php $firstPhoto = $item->photos->first(); @endphp

                        @if($firstPhoto)
                        <img src="{{ asset('storage/' . $firstPhoto->image_url) }}"
                            class="img-fluid rounded-4"
                            alt="{{ $item->item_name }}"
                            style="height: 190px; width:100%; object-fit: cover;">
                        @else
                        <div class="bg-light d-flex align-items-center justify-content-center rounded-4"
                            style="height:190px;">
                            <i class="fas fa-box-open fs-1 text-muted"></i>
                        </div>
                        @endif
                    </div>

                    {{-- BODY --}}
                    <div class="card-body pt-0 d-flex flex-column">

                        <h5 class="fw-semibold mb-1 text-dark">
                            {{ $item->item_name }}
                        </h5>

                        <p class="text-muted small mb-1">
                            <i class="fas fa-map-marker-alt me-1 text-success"></i>
                            {{ $item->location_found }}
                        </p>

                        <p class="text-muted small mb-2 text-truncate">
                            <i class="fas fa-align-left me-1 text-success"></i>
                            {{ $item->description }}
                        </p>

                        {{-- BADGE STATUS STYLE MODERN --}}
                        <div class="mt-auto">
                            @if($item->status === 'Tersedia')
                            <span class="badge bg-success bg-opacity-75 text-white rounded-pill px-3 py-2">
                                <i class="fas fa-check-circle me-1"></i> Tersedia
                            </span>
                            @elseif($item->status === 'Diambil')
                            <span class="badge bg-secondary bg-opacity-75 rounded-pill px-3 py-2">
                                <i class="fas fa-box me-1"></i> Diambil
                            </span>
                            @else
                            <span class="badge bg-warning text-dark rounded-pill px-3 py-2">
                                <i class="fas fa-exclamation-circle me-1"></i> {{ $item->status }}
                            </span>
                            @endif
                        </div>

                        {{-- BUTTON --}}
                        <button class="btn btn-outline-success w-100 mt-3 rounded-pill"
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

{{-- MODALS --}}
@foreach($items as $item)
<div class="modal fade" id="detailModal{{ $item->item_id }}" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content rounded-4 shadow-lg border-0">

            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold" style="font-family: 'Playfair Display', serif;">
                    Detail Barang
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body p-4">

                {{-- PHOTO --}}
                <div class="text-center mb-3">
                    @if($item->photos->isEmpty())
                    <div class="bg-light rounded-4 d-flex align-items-center justify-content-center"
                        style="height: 300px;">
                        <i class="fas fa-box-open fs-1 text-muted"></i>
                    </div>

                    @elseif($item->photos->count() === 1)
                    <img src="{{ asset('storage/' . $item->photos[0]->image_url) }}"
                        class="img-fluid rounded-4 shadow-sm"
                        style="max-height:400px; object-fit:contain;">

                    @else
                    <div id="photoCarousel{{ $item->item_id }}" class="carousel slide">
                        <div class="carousel-inner rounded-4 shadow-sm">
                            @foreach($item->photos as $index => $photo)
                            <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                <img src="{{ asset('storage/' . $photo->image_url) }}"
                                    class="d-block w-100"
                                    style="max-height:400px; object-fit:contain;">
                            </div>
                            @endforeach
                        </div>

                        <button class="carousel-control-prev" type="button"
                            data-bs-target="#photoCarousel{{ $item->item_id }}" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon"></span>
                        </button>

                        <button class="carousel-control-next" type="button"
                            data-bs-target="#photoCarousel{{ $item->item_id }}" data-bs-slide="next">
                            <span class="carousel-control-next-icon"></span>
                        </button>
                    </div>
                    @endif
                </div>

                {{-- TEXT DETAILS --}}
                <h4 class="fw-bold">{{ $item->item_name }}</h4>
                <p class="text-muted">{{ $item->description }}</p>

                {{-- DETAIL LIST --}}
                <h6 class="text-uppercase text-muted fw-bold small mt-4 mb-2">Detail Penemuan</h6>
                <ul class="list-group list-group-flush">

                    <li class="list-group-item px-0 d-flex justify-content-between align-items-center">
                        <span><i class="fas fa-map-marker-alt text-success me-2"></i> Lokasi Ditemukan</span>
                        <strong>{{ $item->location_found }}</strong>
                    </li>

                    <li class="list-group-item px-0 d-flex justify-content-between align-items-center">
                        <span><i class="fas fa-calendar-alt text-success me-2"></i> Tanggal Ditemukan</span>
                        <strong>{{ $item->created_at->format('d M Y H:i') }}</strong>
                    </li>

                    <li class="list-group-item px-0 d-flex justify-content-between align-items-center">
                        <span><i class="fas fa-info-circle text-success me-2"></i> Status</span>
                        <strong>
                            @if($item->status === 'Tersedia')
                            <span class="badge bg-success px-3 py-2 rounded-pill">Tersedia</span>
                            @elseif($item->status === 'Diambil')
                            <span class="badge bg-secondary px-3 py-2 rounded-pill">Diambil</span>
                            @else
                            <span class="badge bg-warning px-3 py-2 rounded-pill">{{ $item->status }}</span>
                            @endif
                        </strong>
                    </li>
                </ul>

                {{-- IF RETRIEVED --}}
                @if($item->retrieved_by_name)
                <h6 class="text-uppercase text-muted fw-bold small mt-4 mb-2">Detail Pengambilan</h6>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item px-0 d-flex justify-content-between">
                        <span><i class="fas fa-user-check text-success me-2"></i> Diambil Oleh</span>
                        <strong>{{ $item->retrieved_by_name }}</strong>
                    </li>
                    <li class="list-group-item px-0 d-flex justify-content-between">
                        <span><i class="fas fa-calendar-check text-success me-2"></i> Tanggal Diambil</span>
                        <strong>{{ $item->retrieved_at?->format('d M Y H:i') ?? '—' }}</strong>
                    </li>
                </ul>
                @endif

            </div>

            <div class="modal-footer border-0">
                <button class="btn btn-secondary rounded-pill" data-bs-dismiss="modal">Tutup</button>
            </div>

        </div>
    </div>
</div>
@endforeach

@endsection

@section('styles')
<style>
    .item-card {
        transition: .25s ease;
        border: 1px solid #eaeaea !important;
    }

    .item-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 12px 28px rgba(0, 0, 0, 0.08) !important;
    }

    .carousel-inner img {
        border-radius: 16px !important;
    }

    .hero-bg {
        background-image: url('{{ asset(' assets/images/bg-barang-hilang.png') }}');
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        padding: 80px 0;
        margin-bottom: 30px;
    }

    .hero-bg .container {
        position: relative;
        z-index: 1;
    }

    .hero-bg::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(255, 255, 255, 0.9);
        z-index: 0;
    }
</style>
@endsection