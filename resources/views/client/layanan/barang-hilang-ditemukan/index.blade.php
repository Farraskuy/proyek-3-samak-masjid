@extends('client.layout')

@section('title', 'Barang Hilang & Barang Temuan')

@push('styles')
<style>
    :root {
        --primary-color: #175C9E;
        --secondary-color: #f4f7f6;
        --text-dark: #333;
    }

    body {
        background-color: #fdfdfd;
        font-family: 'Poppins', sans-serif;
    }

    .bg-pattern {
        background-image: radial-gradient(circle at 1px 1px, rgba(255, 255, 255, 0.15) 1px, transparent 1px);
        background-size: 25px 25px;
    }

    /* Search Bar Style like News */
    .search-container {
        width: 100%;
        display: flex;
        box-shadow: 0 .125rem .25rem rgba(0, 0, 0, .075);
        border-radius: 50rem;
        padding: 0.5rem 1rem;
        max-width: 800px;
        border: 1px solid #e5e5e5;
        background: white;
        margin: 0 auto;
    }

    /* Filter Pills Style like News */
    .filter-pill {
        padding: 9px 18px;
        border-radius: 30px;
        border: 1px solid #ddd;
        background: white;
        cursor: pointer;
        transition: .2s;
        font-size: 14px;
        text-decoration: none;
        color: #333;
        display: inline-block;
    }

    .filter-pill:hover {
        background-color: #f8fbff;
        color: var(--primary-color);
        border-color: var(--primary-color);
    }

    .filter-pill.active {
        background: #175C9E;
        border-color: #175C9E;
        color: white;
        font-weight: 600;
        box-shadow: 0 4px 10px rgba(23, 92, 158, 0.2);
    }

    /* Category Scroll */
    .category-scroll-wrapper {
        overflow-x: auto;
        white-space: nowrap;
        padding-bottom: 10px;
        -ms-overflow-style: none;
        scrollbar-width: none;
    }

    .category-scroll-wrapper::-webkit-scrollbar {
        display: none;
    }

    /* Item Card */
    .item-card {
        border: none;
        border-radius: 14px;
        background: white;
        transition: .25s ease-in-out;
        border: 0 !important;
        box-shadow: 0 2px 12px rgba(0, 0, 0, .08);
        overflow: hidden;
    }

    .item-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 28px rgba(0, 0, 0, .12) !important;
    }

    .badge-soft-primary {
        background: #e3f2fd;
        color: #1976d2;
    }

    .badge-soft-warning {
        background: #fff8e1;
        color: #f57f17;
    }

    .badge-soft-danger {
        background: #ffebee;
        color: #c62828;
    }

    .badge-soft-success {
        background: #e8f5e9;
        color: #2e7d32;
    }

    .badge-soft-secondary {
        background: #f0f0f0;
        color: #666;
    }

    .modal-content {
        border-radius: 25px;
        border: none;
    }

    .modal-header {
        border-bottom: 1px solid #f0f0f0;
        padding: 20px 30px;
    }

    .modal-body {
        padding: 30px;
    }

    .empty-illus {
        max-width: 220px;
        opacity: .9;
    }
</style>
@endpush

@section('content')

{{-- HERO --}}
<section class="py-5 bg-pattern" style="background-color:#175C9E; height:320px; display:flex; align-items:center;">
    <div class="container text-center">
        <h1 class="display-5 fw-bold text-white mb-3" data-aos="fade-up" data-aos-duration="900">
            Barang Hilang & <span style="color: #F6C948;">Barang Temuan</span>
        </h1>
        <p class="text-white-50 lead mb-0 col-lg-8 mx-auto" data-aos="fade-up" data-aos-duration="1100" data-aos-delay="200">
            Laporkan barang yang hilang atau cek barang temuan di area masjid dengan mudah dan transparan.
        </p>
    </div>
</section>

<section class="py-5" style="padding-bottom:120px !important;">
    <div class="container">

        {{-- Main Tabs (Lost / Found) --}}
        <div class="mb-4 text-center">
            <div class="d-flex justify-content-center gap-2 flex-wrap">
                <a href="{{ route('layanan.barang-hilang') }}?tab=lost"
                    class="text-decoration-none">
                    <span class="filter-pill {{ (!request()->filled('tab') || request('tab') == 'lost') ? 'active' : '' }}">
                        Barang Hilang
                    </span>
                </a>
                <a href="{{ route('layanan.barang-hilang') }}?tab=found"
                    class="text-decoration-none">
                    <span class="filter-pill {{ request('tab') == 'found' ? 'active' : '' }}">
                        Barang Temuan
                    </span>
                </a>
            </div>
        </div>

        
        @if (!request()->filled('tab') || request('tab') == 'lost')

        {{-- Category Filters for Lost Items --}}
        <div class="d-flex justify-content-center mb-4">
            <div class="category-scroll-wrapper text-center">
                <a href="{{ route('layanan.barang-hilang') }}?tab=lost"
                    class="filter-pill {{ !request('category') ? 'active' : '' }}" style="padding: 6px 16px; font-size: 13px;">
                    Semua Kategori
                </a>
                @foreach ($categories as $cat)
                <a href="{{ route('layanan.barang-hilang') }}?tab=lost&category={{ $cat->slug }}"
                    class="filter-pill {{ request('category') == $cat->slug ? 'active' : '' }}" style="padding: 6px 16px; font-size: 13px;">
                    {{ $cat->name }}
                </a>
                @endforeach
            </div>
        </div>

        <div class="alert alert-info border-0 shadow-sm rounded-4 mb-4 d-flex align-items-center" data-aos="fade-up">
            <i class="fas fa-info-circle fs-4 me-3"></i>
            <div>
                <strong>Info Jamaah:</strong> Berikut adalah daftar barang yang hilang. Jika Anda menemukannya, silakan hubungi Divisi Sarpras atau hubungi no <strong>08988120558</strong>.
            </div>
        </div>

        @if ($lostItems->isEmpty())
        <div class="text-center py-5">
            <div class="mb-3 text-muted" style="opacity: 0.5;">
                <i class="fas fa-box-open fa-3x"></i>
            </div>
            <h5 class="text-muted mt-3">Tidak ada data barang hilang.</h5>
        </div>
        @else
        <div class="row g-4">
            @foreach ($lostItems as $item)
            <div class="col-lg-6" data-aos="fade-up" data-aos-duration="700">
                <div class="item-card p-4 h-100 d-flex flex-column justify-content-center position-relative">
                    <span class="badge badge-soft-danger position-absolute top-0 end-0 m-3">
                        Hilang
                    </span>

                    <div class="d-flex align-items-start">
                        <div class="flex-grow-1">
                            <h5 class="fw-bold mb-1 text-dark">{{ $item->item_name }}</h5>
                            <p class="text-muted small mb-2 text-truncate" style="max-width: 350px;">{{ $item->description }}</p>

                            <div class="d-flex flex-wrap gap-2 mb-3">
                                <span class="badge badge-soft-primary"><i class="fas fa-tag me-1"></i> {{ $item->category->name }}</span>
                                <span class="badge badge-soft-warning"><i class="fas fa-calendar me-1"></i> {{ $item->lost_at->format('d M Y') }}</span>
                            </div>

                            <div class="d-flex justify-content-between align-items-center mt-2">
                                <small class="text-muted"><i class="fas fa-map-marker-alt text-danger me-1"></i> {{ $item->location_lost }}</small>
                                <button class="btn btn-sm btn-outline-primary rounded-pill px-3"
                                    data-bs-toggle="modal" data-bs-target="#lostModal{{ $item->id }}">
                                    Lihat Detail
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="lostModal{{ $item->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content shadow-lg">
                        <div class="modal-header">
                            <h5 class="modal-title fw-bold">Detail Barang Hilang</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <h3 class="fw-bold text-primary mb-1">{{ $item->item_name }}</h3>
                            <span class="badge badge-soft-danger mb-4">Hilang</span>

                            <div class="mb-3">
                                <label class="small text-muted fw-bold text-uppercase">Deskripsi / Ciri-ciri</label>
                                <p class="text-dark">{{ $item->description }}</p>
                            </div>

                            <div class="bg-light p-3 rounded-4 mt-3">
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted small"><i class="fas fa-tag me-2"></i>Kategori</span>
                                    <span class="fw-bold">{{ $item->category->name }}</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted small"><i class="fas fa-map-marker-alt me-2"></i>Lokasi Hilang</span>
                                    <span class="fw-bold">{{ $item->location_lost }}</span>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span class="text-muted small"><i class="fas fa-calendar me-2"></i>Tanggal Hilang</span>
                                    <span class="fw-bold">{{ $item->lost_at->format('d M Y') }}</span>
                                </div>
                            </div>

                            <div class="alert alert-warning mt-3 mb-0 d-flex align-items-center">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                <small class="lh-sm">Jika Anda menemukan barang ini, hubungi bagian SarPras atau hubungi no ini <strong>08988120558</strong>.</small>
                            </div>
                        </div>
                        <div class="modal-footer border-0">
                            <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Tutup</button>
                        </div>
                    </div>
                </div>
            </div>

            @endforeach
        </div>

        <div class="mt-5 d-flex justify-content-center">
            {{ $lostItems->appends(['tab' => 'lost'])->links() }}
        </div>
        @endif

        @else

        <div class="alert alert-info border-0 shadow-sm rounded-4 mb-4 d-flex align-items-center" data-aos="fade-up">
            <i class="fas fa-info-circle fs-4 me-3"></i>
            <div>
                <strong>Info Jamaah:</strong> Berikut adalah daftar barang yang ditemukan. Silakan hubungi Divisi Sarpras jika merasa pemiliknya atau hubungi no <strong>08988120558</strong>.
            </div>
        </div>

        @if ($foundItems->isEmpty())
        <div class="text-center py-5">
            <div class="mb-3 text-muted" style="opacity: 0.5;">
                <i class="fas fa-search-minus fa-3x"></i>
            </div>
            <h5 class="text-muted mt-3">Belum ada barang temuan tercatat.</h5>
        </div>
        @else
        <div class="row g-4">
            @foreach ($foundItems as $item)
            <div class="col-lg-6" data-aos="fade-up" data-aos-duration="700">
                <div class="item-card p-4 h-100 d-flex flex-column position-relative">

                    <div class="d-flex align-items-start">
                        <div class="flex-grow-1">
                            <h5 class="fw-bold mb-1 text-dark">{{ $item->item_name }}</h5>
                            <p class="text-muted small mb-2">{{ Str::limit($item->description, 60) }}</p>

                            <div class="d-flex flex-wrap gap-2 mb-3">
                                <span class="badge badge-soft-primary"><i class="fas fa-tag me-1"></i> {{ $item->category }}</span>
                                <span class="badge badge-soft-warning"><i class="fas fa-clock me-1"></i> {{ $item->created_at->diffForHumans() }}</span>
                            </div>

                            <div class="d-flex justify-content-between align-items-center mt-3 pt-3 border-top">
                                <small class="text-muted"><i class="fas fa-map-pin text-success me-1"></i> {{ $item->location_found }}</small>
                                <button class="btn btn-sm btn-primary rounded-pill px-3"
                                    data-bs-toggle="modal" data-bs-target="#foundModal{{ $item->item_id }}">
                                    Lihat Detail
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="foundModal{{ $item->item_id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content shadow-lg">
                        <div class="modal-header">
                            <h5 class="modal-title fw-bold">Detail Barang Ditemukan</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <h3 class="fw-bold text-dark mb-4">{{ $item->item_name }}</h3>

                            <div class="mb-3">
                                <label class="small text-muted fw-bold text-uppercase">Deskripsi</label>
                                <p class="text-dark">{{ $item->description }}</p>
                            </div>

                            <div class="row g-2 mt-2">
                                <div class="col-sm-12">
                                    <div class="p-3 border rounded-4 bg-light">
                                        <span class="d-block text-muted small mb-1">Lokasi Ditemukan</span>
                                        <strong class="text-success"><i class="fas fa-map-marker-alt me-1"></i> {{ $item->location_found }}</strong>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="p-3 border rounded-4 bg-light">
                                        <span class="d-block text-muted small mb-1">Kategori</span>
                                        <strong class="text-primary"><i class="fas fa-tag me-1"></i> {{ $item->category }}</strong>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="p-3 border rounded-4 bg-light">
                                        <span class="d-block text-muted small mb-1">Waktu Ditemukan</span>
                                        <strong class="text-dark"><i class="fas fa-calendar-alt me-1"></i> {{ $item->created_at->format('d M Y, H:i') }}</strong>
                                    </div>
                                </div>
                            </div>

                            <div class="alert alert-warning mt-3 mb-0 d-flex align-items-center">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                <small class="lh-sm">Hubungi bagian SarPras untuk pengambilan atau hubungi no ini <strong>08988120558</strong>.</small>
                            </div>
                        </div>
                        <div class="modal-footer border-0">
                            <button class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Tutup</button>
                        </div>
                    </div>
                </div>
            </div>

            @endforeach
        </div>

        <div class="mt-5 d-flex justify-content-center">
            {{ $foundItems->appends(['tab' => 'found'])->links() }}
        </div>
        @endif

        @endif
    </div>
</section>
@endsection