@extends('client.layout')
@section('title', 'Barang Hilang & Ditemukan - SAMAK-Kampus')

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

        .card-thumbnail-wrapper {
            position: relative;
            height: 190px;
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

        .report-table .table td,
        .report-table .table th {
            vertical-align: middle;
        }

        .report-table img {
            width: 40px;
            height: 40px;
            object-fit: cover;
        }
    </style>
@endpush

@section('content')
    <section class="py-5 bg-pattern" style="background-color: #175C9E; height: 320px; display:flex; align-items:center;">
        <div class="container text-center">
            <h1 class="display-5 fw-bold text-white mb-3">
                Barang Hilang & Ditemukan
            </h1>
            <p class="text-white-50 lead mb-0 col-lg-8 mx-auto">
                Temukan barang yang hilang atau laporkan temuan di area masjid kampus.
            </p>
        </div>
    </section>

    <section class="py-5">
        <div class="container">
            <!-- Tabs -->
            <div class="d-flex justify-content-center mb-4">
                <div class="bg-light p-1 rounded-pill d-inline-flex">
                    <a class="btn rounded-pill px-4 fw-semibold {{ (request()->routeIs('layanan.barang-hilang') && !request()->filled('tab')) || request('tab') == 'lost' ? 'btn-primary' : 'btn-light text-muted' }}"
                        href="{{ route('layanan.barang-hilang') }}?tab=lost"
                        style="{{ (request()->routeIs('layanan.barang-hilang') && !request()->filled('tab')) || request('tab') == 'lost' ? 'background-color: #175C9E; border-color: #175C9E;' : 'background: transparent; border: none;' }}">
                        Dicari Barang Hilang
                    </a>
                    <a class="btn rounded-pill px-4 fw-semibold {{ request('tab') == 'found' ? 'btn-primary' : 'btn-light text-muted' }}"
                        href="{{ route('layanan.barang-hilang') }}?tab=found"
                        style="{{ request('tab') == 'found' ? 'background-color: #175C9E; border-color: #175C9E;' : 'background: transparent; border: none;' }}">
                        Barang Temuan
                    </a>
                </div>
            </div>

            <!-- Tab Content -->
            @if (!request()->filled('tab') || request('tab') == 'lost')
                <!-- Tab 1: Barang Hilang (Katalog) -->
                <div class="d-flex justify-content-center mb-4">
                    <form method="GET" class="w-100 d-flex shadow-sm rounded-pill px-3 py-2"
                        style="max-width: 800px; background: #ffffff; border:1px solid #e5e5e5;">
                        <input type="hidden" name="tab" value="lost">
                        <input type="text" name="search" class="form-control border-0 shadow-0"
                            placeholder="Cari barang disini..." value="{{ request('search') }}"
                            style="background:none; flex: 1;">
                        <button type="submit" class="btn btn-link text-success">
                            <i class="fas fa-search fs-5"></i>
                        </button>
                    </form>
                </div>

                <div class="d-flex justify-content-center mb-4">
                    <div class="row g-3 w-100" style="max-width: 800px;">
                        <div class="col-md-2 text-center">
                            <a href="{{ route('layanan.barang-hilang') }}?tab=lost" class="text-decoration-none text-dark">
                                <div class="bg-light p-3 rounded-4 mb-2" style="color: #175C9E">
                                    <i class="fas fa-th-large fs-2"></i>
                                </div>
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
                        <i class="fas fa-info-circle me-2"></i>
                        Belum ada laporan barang hilang.
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
                                        <div class="card-thumbnail-wrapper mb-3"
                                            style="height: 400px; background: #f8f9fa;">
                                            <img src="{{ asset('storage/' . $item->photos->first()->image_url) }}"
                                                class="img-fluid rounded-4"
                                                style="height: 100%; width: 100%; object-fit: contain;">
                                            <i class="fas fa-image fallback-icon"></i>
                                        </div>
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
                <!-- Tab 2: Barang Ditemukan (Laporan) -->
                <div class="d-flex justify-content-center mb-4">
                    <form method="GET" class="w-100 d-flex shadow-sm rounded-pill px-3 py-2"
                        style="max-width: 800px; background: #ffffff; border:1px solid #e5e5e5;">
                        <input type="hidden" name="tab" value="found">
                        <input type="text" name="search" class="form-control border-0 shadow-0"
                            placeholder="Cari barang disini..." value="{{ request('search') }}"
                            style="background:none; flex: 1;">
                        <button type="submit" class="btn btn-link text-success">
                            <i class="fas fa-search fs-5"></i>
                        </button>
                    </form>
                </div>

                @if ($foundItems->isEmpty())
                    <div class="alert alert-light shadow-sm border text-center py-4">
                        <i class="fas fa-info-circle me-2"></i>
                        Belum ada barang yang ditemukan.
                    </div>
                @else
                    <div class="row g-4">
                        @foreach ($foundItems as $item)
                            <div class="col-md-4">
                                <div class="card h-100 rounded-4 shadow-sm border-0 item-card">
                                    <div class="p-3">
                                        @if ($item->photos->first())
                                            <div class="card-thumbnail-wrapper" style="height: 190px;">
                                                <img src="{{ asset('storage/' . $item->photos->first()->image_url) }}"
                                                    class="img-fluid rounded-4" alt="{{ $item->item_name }}"
                                                    style="height: 100%; width:100%; object-fit: cover;">
                                                <i class="fas fa-image fallback-icon"></i>
                                            </div>
                                        @else
                                            <div class="bg-light d-flex align-items-center justify-content-center rounded-4"
                                                style="height:190px;">
                                                <i class="fas fa-box-open fs-1 text-muted"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="card-body pt-0 d-flex flex-column">
                                        <h5 class="fw-semibold mb-1 text-dark">{{ $item->item_name }}</h5>
                                        <p class="text-muted small mb-1">
                                            <i class="fas fa-map-marker-alt me-1 text-success"></i>
                                            {{ $item->location_found }}
                                        </p>
                                        <p class="text-muted small mb-2 text-truncate">
                                            <i class="fas fa-align-left me-1 text-success"></i>
                                            {{ $item->description }}
                                        </p>
                                        <div class="mt-auto d-flex justify-content-between align-items-center">
                                            @if ($item->status === 'Tersedia')
                                                <span class="badge bg-success rounded-pill px-3">Tersedia</span>
                                            @else
                                                <span class="badge bg-secondary rounded-pill px-3">Diambil</span>
                                            @endif
                                            <small class="text-muted">{{ $item->created_at->format('d M Y') }}</small>
                                        </div>
                                        <button class="btn btn-outline-primary w-100 mt-3 rounded-pill"
                                            data-bs-toggle="modal" data-bs-target="#foundModal{{ $item->item_id }}">
                                            Lihat Detail
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="d-flex justify-content-center mt-4">
                        {{ $foundItems->appends(['tab' => 'found'])->links() }}
                    </div>
                @endif

                <!-- Modals for Found Items -->
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
                                        <div class="card-thumbnail-wrapper mb-3"
                                            style="height: 400px; background: #f8f9fa;">
                                            <img src="{{ asset('storage/' . $item->photos[0]->image_url) }}"
                                                class="img-fluid rounded-4"
                                                style="height: 100%; width: 100%; object-fit: contain;"
                                                onerror="this.onerror=null;this.src='{{ asset('assets/images/no-data.png') }}';">
                                            <i class="fas fa-image fallback-icon"></i>
                                        </div>
                                    @else
                                        <div id="foundCarousel{{ $item->item_id }}" class="carousel slide">
                                            <div class="carousel-inner rounded-4 shadow-sm">
                                                @foreach ($item->photos as $index => $photo)
                                                    <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                                        <div class="card-thumbnail-wrapper"
                                                            style="height: 400px; background: #f8f9fa;">
                                                            <img src="{{ asset('storage/' . $photo->image_url) }}"
                                                                class="d-block w-100"
                                                                style="height: 100%; width: 100%; object-fit: contain;"
                                                                onerror="this.onerror=null;this.src='{{ asset('assets/images/no-data.png') }}';">
                                                            <i class="fas fa-image fallback-icon"></i>
                                                        </div>
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
