@extends('client.layout')
@section('title', 'Barang Hilang & Ditemukan')

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

    .hero-section {
        background-color: var(--primary-color);
        background-image: radial-gradient(circle at 1px 1px, rgba(255, 255, 255, 0.15) 1px, transparent 1px);
        background-size: 30px 30px;
        border-bottom-right-radius: 50px;
        border-bottom-left-radius: 50px;
        padding: 80px 0 60px;
        margin-bottom: 40px;
    }

    .nav-pills .nav-link {
        color: #888;
        background-color: white;
        border: 1px solid #eee;
        border-radius: 50px;
        padding: 12px 35px;
        margin: 0 10px;
        font-weight: 600;
        transition: all 0.3s;
    }

    .nav-pills .nav-link.active {
        background-color: var(--primary-color);
        color: white;
        box-shadow: 0 4px 10px rgba(23, 92, 158, 0.3);
        border-color: var(--primary-color);
    }

    .search-container {
        background: white;
        padding: 8px 20px;
        border-radius: 50px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.05);
        border: 1px solid #f0f0f0;
    }

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

    .category-pill {
        display: inline-block;
        padding: 10px 25px;
        margin: 0 5px;
        border: 1px solid #e0e0e0;
        border-radius: 50px;
        color: #555;
        font-weight: 500;
        text-decoration: none;
        transition: all 0.2s ease;
        background-color: white;
    }

    .category-pill:hover {
        border-color: var(--primary-color);
        color: var(--primary-color);
        background-color: #f8fbff;
    }

    .category-pill.active {
        background-color: var(--primary-color);
        color: white;
        border-color: var(--primary-color);
        box-shadow: 0 4px 10px rgba(23, 92, 158, 0.2);
    }

    .item-card {
        border: none;
        border-radius: 20px;
        background: white;
        transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        border: 1px solid #f5f5f5;
        overflow: hidden;
    }

    .item-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 30px rgba(0, 0, 0, 0.05);
        border-color: #eef2f5;
    }

    .badge-soft-primary { background: #e3f2fd; color: #1976d2; }
    .badge-soft-warning { background: #fff8e1; color: #f57f17; }
    .badge-soft-danger  { background: #ffebee; color: #c62828; }
    .badge-soft-success { background: #e8f5e9; color: #2e7d32; }
    .badge-soft-secondary { background: #f0f0f0; color: #666; }

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
</style>
@endpush

@section('content')

<section class="hero-section">
    <div class="container text-center">
        <h1 class="display-5 fw-bold text-white mb-3">Layanan Barang Hilang</h1>
        <p class="text-white-50 lead mb-4 mx-auto" style="max-width: 600px;">
            Laporkan barang yang hilang atau cek barang temuan di area masjid dengan mudah dan transparan.
        </p>
        
        <ul class="nav nav-pills justify-content-center" id="pills-tab" role="tablist">
            <li class="nav-item" role="presentation">
                <a class="nav-link {{ (!request()->filled('tab') || request('tab') == 'lost') ? 'active' : '' }}" 
                   href="{{ route('layanan.barang-hilang') }}?tab=lost">
                   Dicari (Barang Hilang)
                </a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link {{ request('tab') == 'found' ? 'active' : '' }}" 
                   href="{{ route('layanan.barang-hilang') }}?tab=found">
                   Barang Temuan
                </a>
            </li>
        </ul>
    </div>
</section>

<section class="pb-5">
    <div class="container">
        
        <div class="row justify-content-center mb-4" style="margin-top: -85px; position: relative; z-index: 10;">
            <div class="col-lg-8">
                <form method="GET" class="search-container d-flex align-items-center">
                    <input type="hidden" name="tab" value="{{ request('tab', 'lost') }}">
                    @if(request('category'))
                        <input type="hidden" name="category" value="{{ request('category') }}">
                    @endif
                    
                    <i class="fas fa-search text-muted ms-3 me-3 fs-5"></i>
                    <input type="text" name="search" class="form-control border-0 shadow-none bg-transparent" 
                           placeholder="Cari nama barang, ciri-ciri, atau lokasi..." 
                           value="{{ request('search') }}" style="height: 50px;">
                    <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold">Cari</button>
                </form>
            </div>
        </div>

        @if (!request()->filled('tab') || request('tab') == 'lost')
            
            <div class="d-flex justify-content-center mb-4">
                <div class="category-scroll-wrapper text-center">
                    <a href="{{ route('layanan.barang-hilang') }}?tab=lost" 
                       class="category-pill {{ !request('category') ? 'active' : '' }}">
                        Semua
                    </a>
                    @foreach ($categories as $cat)
                    <a href="{{ route('layanan.barang-hilang') }}?tab=lost&category={{ $cat->slug }}" 
                       class="category-pill {{ request('category') == $cat->slug ? 'active' : '' }}">
                        {{ $cat->name }}
                    </a>
                    @endforeach
                </div>
            </div>

            <div class="alert alert-info border-0 shadow-sm rounded-4 mb-4 d-flex align-items-center">
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
                    <div class="col-lg-6">
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
            
            <div class="alert alert-info border-0 shadow-sm rounded-4 mb-4 d-flex align-items-center">
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
                    <div class="col-lg-6">
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