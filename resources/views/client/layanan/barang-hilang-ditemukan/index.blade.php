@extends('client.layout')
@section('title', 'Barang Hilang & Ditemukan')

@push('styles')
    <style>
        * {
            font-family: 'Poppins', "Lexend", sans-serif;
        }

        .bg-pattern {
            background-image: radial-gradient(circle at 1px 1px, rgba(255, 255, 255, 0.15) 1px, transparent 1px);
            background-size: 25px 25px;
        }

        .item-card {
            transition: all 0.3s ease-in-out;
            border: 1px solid #eaeaea !important;
        }

        .item-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 12px 28px rgba(0, 0, 0, 0.08) !important;
        }
    </style>
@endpush

@section('content')
    <section class="py-5 bg-pattern" style="background-color: #175C9E; height: 320px; display:flex; align-items:center;">
        <div class="container text-center">
            <h1 class="display-5 fw-bold text-white mb-3">Barang Hilang & <span style="color: #F6C948;">Ditemukan</span></h1>
            <p class="text-white-50 lead mb-0 col-lg-8 mx-auto">
                Temukan barang yang hilang atau laporkan temuan di area masjid kampus.
            </p>
        </div>
    </section>

    <section class="py-5">
        <div class="container">
            <!-- Tabs -->
            <ul class="nav nav-tabs justify-content-center mb-4">
                <li class="nav-item">
                    <a class="nav-link {{ !request()->filled('tab') || request('tab') == 'lost' ? 'active' : '' }}"
                        href="{{ route('layanan.barang-hilang') }}?tab=lost">
                        Dicari Barang Hilang
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request('tab') == 'found' ? 'active' : '' }}"
                        href="{{ route('layanan.barang-hilang') }}?tab=found">
                        Barang Temuan
                    </a>
                </li>
            </ul>

            <!-- Tab Content -->
            @if (!request()->filled('tab') || request('tab') == 'lost')
                <!-- Tab 1: Barang Hilang (Katalog) -->
                <div class="d-flex justify-content-center mb-4">
                    <form method="GET" class="w-100 d-flex shadow-sm rounded-pill px-3 py-2" style="max-width: 800px;">
                        <input type="hidden" name="tab" value="lost">
                        <input type="text" name="search" class="form-control border-0 shadow-0"
                            placeholder="Cari barang..." value="{{ request('search') }}" style="background:none; flex: 1;">
                        <button type="submit" class="btn btn-link text-success"><i class="fas fa-search fs-5"></i></button>
                    </form>
                </div>

                <div class="d-flex justify-content-center mb-4">
                    <div class="row g-3 w-100" style="max-width: 800px;">
                        <div class="col-md-2 text-center">
                            <a href="{{ route('layanan.barang-hilang') }}?tab=lost" class="text-decoration-none text-dark">
                                <div class="bg-light p-3 rounded-4 mb-2" style="color: #175C9E"><i
                                        class="fas fa-th-large fs-2"></i></div>
                                <h6 class="fw-bold">Semua</h6>
                            </a>
                        </div>
                        @foreach ($categories as $cat)
                            <div class="col-md-2 text-center">
                                <a href="{{ route('layanan.barang-hilang') }}?tab=lost&category={{ $cat->slug }}"
                                    class="text-decoration-none text-dark">
                                    <div class="bg-light p-3 rounded-4 mb-2" style="color: #175C9E">
                                        <i class="fas {{ $cat->icon ?? 'fa-box' }} fs-2"></i>
                                    </div>
                                    <h6 class="fw-bold">{{ $cat->name }}</h6>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>

                @if ($lostItems->isEmpty())
                    <div class="alert alert-light shadow-sm border text-center py-4">
                        <i class="fas fa-info-circle me-2"></i> Belum ada laporan barang hilang.
                    </div>
                @else
                    <div class="row g-3">
                        @foreach ($lostItems as $item)
                            <div class="col-12">
                                <div class="card rounded-4 shadow-sm border-0 item-card p-3">
                                    <div class="row align-items-center">
                                        <div class="col-md-9">
                                            <h5 class="fw-bold text-dark mb-2">{{ $item->item_name }}</h5>
                                            <p class="text-muted mb-2">{{ $item->description }}</p>
                                            <div class="d-flex flex-wrap gap-3 text-muted small">
                                                <span><i class="fas fa-map-marker-alt text-success me-1"></i>
                                                    {{ $item->location_lost }}</span>
                                                <span><i class="fas fa-calendar-alt text-primary me-1"></i>
                                                    {{ $item->lost_at->format('d M Y') }}</span>
                                                <span><i class="fas fa-tag text-warning me-1"></i>
                                                    {{ $item->category->name }}</span>
                                            </div>
                                        </div>
                                        <div class="col-md-3 text-md-end mt-3 mt-md-0">
                                            <button class="btn btn-outline-primary rounded-pill px-4" data-bs-toggle="modal"
                                                data-bs-target="#lostModal{{ $item->id }}">
                                                Lihat Detail
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="d-flex justify-content-center mt-4">
                        {{ $lostItems->appends(['tab' => 'lost'])->links() }}
                    </div>
                @endif

                <!-- Modals for Lost Items -->
                @foreach ($lostItems as $item)
                    <div class="modal fade" id="lostModal{{ $item->id }}" tabindex="-1">
                        <div class="modal-dialog modal-dialog-centered modal-lg">
                            <div class="modal-content rounded-4 shadow-lg border-0">
                                <div class="modal-header border-0">
                                    <h5 class="modal-title fw-bold">Detail Barang Hilang</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body p-4">
                                    @if ($item->photos->isEmpty())
                                        <div class="bg-light rounded-4 d-flex align-items-center justify-content-center"
                                            style="height: 300px;">
                                            <i class="fas fa-box-open fs-1 text-muted"></i>
                                        </div>
                                    @else
                                        <img src="{{ asset('storage/' . $item->photos->first()->image_url) }}"
                                            class="img-fluid rounded-4 mb-3" style="max-height:400px; object-fit:contain;">
                                    @endif
                                    <h4 class="fw-bold">{{ $item->item_name }}</h4>
                                    <p class="text-muted">{{ $item->description }}</p>
                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item px-0">
                                            <span><i class="fas fa-map-marker-alt text-success me-2"></i> Lokasi Perkiraan
                                                Hilang</span>
                                            <strong class="float-end">{{ $item->location_lost }}</strong>
                                        </li>
                                        <li class="list-group-item px-0">
                                            <span><i class="fas fa-calendar-alt text-success me-2"></i> Tanggal
                                                Hilang</span>
                                            <strong class="float-end">{{ $item->lost_at->format('d M Y') }}</strong>
                                        </li>
                                    </ul>
                                </div>
                                <div class="modal-footer border-0">
                                    <button class="btn btn-secondary rounded-pill" data-bs-dismiss="modal">Tutup</button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <!-- Tab 2: Barang Ditemukan (Laporan) - TANPA GAMBAR DI DAFTAR -->
                <div class="d-flex justify-content-center mb-4">
                    <form method="GET" class="w-100 d-flex shadow-sm rounded-pill px-3 py-2" style="max-width: 800px;">
                        <input type="hidden" name="tab" value="found">
                        <input type="text" name="search" class="form-control border-0 shadow-0"
                            placeholder="Cari barang..." value="{{ request('search') }}"
                            style="background:none; flex: 1;">
                        <button type="submit" class="btn btn-link text-success"><i
                                class="fas fa-search fs-5"></i></button>
                    </form>
                </div>

                @if ($foundItems->isEmpty())
                    <div class="alert alert-light shadow-sm border text-center py-4">
                        <i class="fas fa-info-circle me-2"></i> Belum ada barang yang ditemukan.
                    </div>
                @else
                    <div class="row g-3">
                        @foreach ($foundItems as $item)
                            <div class="col-12">
                                <div class="card rounded-4 shadow-sm border-0 item-card p-3">
                                    <div class="row align-items-center">
                                        <div class="col-md-9">
                                            <h5 class="fw-bold text-dark mb-2">{{ $item->item_name }}</h5>
                                            <p class="text-muted mb-2">{{ $item->description }}</p>
                                            <div class="d-flex flex-wrap gap-3 text-muted small">
                                                <span><i class="fas fa-map-marker-alt text-success me-1"></i>
                                                    {{ $item->location_found }}</span>
                                                <span><i class="fas fa-calendar-alt text-primary me-1"></i>
                                                    {{ $item->created_at->format('d M Y') }}</span>
                                                <span><i class="fas fa-tag text-warning me-1"></i>
                                                    {{ $item->category }}</span>
                                                <span>
                                                    @if ($item->status === 'Tersedia')
                                                        <span class="badge bg-success">Tersedia</span>
                                                    @else
                                                        <span class="badge bg-secondary">Diambil</span>
                                                    @endif
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-md-3 text-md-end mt-3 mt-md-0">
                                            <button class="btn btn-outline-primary rounded-pill px-4"
                                                data-bs-toggle="modal" data-bs-target="#foundModal{{ $item->item_id }}">
                                                Lihat Detail
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="d-flex justify-content-center mt-4">
                        {{ $foundItems->appends(['tab' => 'found'])->links() }}
                    </div>
                @endif

                <!-- Modals for Found Items (FOTO HANYA DI MODAL) -->
                @foreach ($foundItems as $item)
                    <div class="modal fade" id="foundModal{{ $item->item_id }}" tabindex="-1">
                        <div class="modal-dialog modal-dialog-centered modal-lg">
                            <div class="modal-content rounded-4 shadow-lg border-0">
                                <div class="modal-header border-0">
                                    <h5 class="modal-title fw-bold">Detail Barang Ditemukan</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body p-4">
                                    @if ($item->photos->isEmpty())
                                        <div class="bg-light rounded-4 d-flex align-items-center justify-content-center"
                                            style="height: 300px;">
                                            <i class="fas fa-box-open fs-1 text-muted"></i>
                                        </div>
                                    @elseif($item->photos->count() === 1)
                                        <img src="{{ asset('storage/' . $item->photos[0]->image_url) }}"
                                            class="img-fluid rounded-4 mb-3"
                                            style="max-height:400px; object-fit:contain;">
                                    @else
                                        <div id="foundCarousel{{ $item->item_id }}" class="carousel slide">
                                            <div class="carousel-inner rounded-4 shadow-sm">
                                                @foreach ($item->photos as $index => $photo)
                                                    <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                                        <img src="{{ asset('storage/' . $photo->image_url) }}"
                                                            class="d-block w-100"
                                                            style="max-height:400px; object-fit:contain;">
                                                    </div>
                                                @endforeach
                                            </div>
                                            <button class="carousel-control-prev" type="button"
                                                data-bs-target="#foundCarousel{{ $item->item_id }}" data-bs-slide="prev">
                                                <span class="carousel-control-prev-icon"></span>
                                            </button>
                                            <button class="carousel-control-next" type="button"
                                                data-bs-target="#foundCarousel{{ $item->item_id }}" data-bs-slide="next">
                                                <span class="carousel-control-next-icon"></span>
                                            </button>
                                        </div>
                                    @endif
                                    <h4 class="fw-bold">{{ $item->item_name }}</h4>
                                    <p class="text-muted">{{ $item->description }}</p>
                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item px-0">
                                            <span><i class="fas fa-map-marker-alt text-success me-2"></i> Lokasi
                                                Ditemukan</span>
                                            <strong class="float-end">{{ $item->location_found }}</strong>
                                        </li>
                                        <li class="list-group-item px-0">
                                            <span><i class="fas fa-calendar-alt text-success me-2"></i> Tanggal
                                                Ditemukan</span>
                                            <strong
                                                class="float-end">{{ $item->created_at->format('d M Y H:i') }}</strong>
                                        </li>
                                        <li class="list-group-item px-0">
                                            <span><i class="fas fa-info-circle text-success me-2"></i> Status</span>
                                            <strong class="float-end">
                                                @if ($item->status === 'Tersedia')
                                                    <span class="badge bg-success px-3 py-2 rounded-pill">Tersedia</span>
                                                @else
                                                    <span class="badge bg-secondary px-3 py-2 rounded-pill">Diambil</span>
                                                @endif
                                            </strong>
                                        </li>
                                    </ul>
                                </div>
                                <div class="modal-footer border-0">
                                    <button class="btn btn-secondary rounded-pill" data-bs-dismiss="modal">Tutup</button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </section>
@endsection
