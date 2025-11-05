@extends('admin.layout')

@section('title', 'Manajemen Lost & Found | SAMAK-Kampus')

@section('content')
<section class="p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold m-0">
            <i class="fa-duotone fa-box-open-full me-2" style="color: #fd7e14"></i>
            Manajemen Barang Hilang & Ditemukan
        </h2>
        <a href="{{ route('admin.barang-hilang.tambah') }}" class="btn btn-primary btn-sm">
            <i class="fa-duotone fa-plus me-1"></i> Tambah Barang
        </a>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="bg-white rounded-3 shadow-sm border p-4">
        @if($items->isEmpty())
        <div class="text-center py-5 text-muted">
            <i class="fa-duotone fa-box-open-full fs-1 mb-3" style="color: #fd7e14"></i>
            <p class="mb-0">Belum ada laporan barang.</p>
            <p class="small text-secondary">Mulai dengan menambahkan barang temuan baru.</p>
        </div>
        @else
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th width="5%">No</th>
                        <th>Barang</th>
                        <th>Lokasi</th>
                        <th>Status</th>
                        <th>Diinput Oleh</th>
                        <th>Tanggal</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($items as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            <div class="d-flex align-items-center">
                                @if($item->featured_image_url)
                                <img src="{{ asset('storage/' . $item->featured_image_url) }}"
                                    alt="{{ $item->item_name }}"
                                    class="me-3 rounded"
                                    width="40" height="40"
                                    style="object-fit: cover;">
                                @else
                                <div class="bg-light me-3 d-flex align-items-center justify-content-center rounded"
                                    style="width: 40px; height: 40px;">
                                    <i class="fa-duotone fa-box text-muted fs-5"></i>
                                </div>
                                @endif
                                <div>
                                    <div class="fw-semibold">{{ $item->item_name }}</div>
                                    <small class="text-muted">{{ Str::limit($item->description, 40) }}</small>
                                </div>
                            </div>
                        </td>
                        <td>{{ $item->location_found }}</td>
                        <td>
                            @if($item->status === 'Tersedia')
                            <span class="badge bg-success">Tersedia</span>
                            @elseif($item->status === 'Diambil')
                            <span class="badge bg-secondary">Diambil</span>
                            @else
                            <span class="badge bg-warning">{{ $item->status }}</span>
                            @endif
                        </td>
                        <td>
                            @if($item->user)
                            <span class="fw-medium">{{ $item->user->full_name }}</span>
                            <br>
                            <small class="text-muted">{{ $item->user->role }}</small>
                            @else
                            <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td>{{ $item->created_at->format('d M Y') }}</td>
                        <td class="text-end">
                            <div class="d-flex justify-content-end gap-1">
                                <button class="btn btn-sm btn-outline-secondary disabled">
                                    <i class="fa-regular fa-pen-to-square"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-danger disabled">
                                    <i class="fa-regular fa-trash-can"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>
</section>
@endsection