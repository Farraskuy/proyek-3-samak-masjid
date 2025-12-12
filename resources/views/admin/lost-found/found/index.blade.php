@extends('admin.layout')
@section('title', 'Manajemen Barang Ditemukan')

@section('content')
<section class="p-3">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-semibold mb-0">Manajemen Barang Ditemukan</h4>
        @can('create_lost_items')
        <a href="{{ route('admin.barang-hilang.tambah') }}" class="btn btn-success fw-semibold">
            <i class="fas fa-plus me-1"></i> Tambah Data
        </a>
        @endcan
    </div>

    <div class="row g-0 gap-3">
        <form method="get" id="form_filter" class="col rounded-3 bg-white p-3 pt-0 form-filter"
            style="height: fit-content">
            <div class="alert-container"></div>

            @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif

            <div class="bg-white position-sticky pt-3 pb-2" style="top: 61px; z-index: 1">
                <div class="d-flex gap-2 justify-content-end mb-2">
                    <input type="text" class="form-control" placeholder="Cari barang"
                        value="{{ request()->query('keyword', '') }}" name="keyword">
                    <select class="form-select fs-14px h-100 w-auto" style="line-height: 1.7" name="sorted_by">
                        <option value="">Urutkan berdasarkan</option>
                        <option value="name">Nama Barang</option>
                        <option value="date">Tanggal</option>
                        <option value="status">Status</option>
                    </select>

                    {{-- Sort Toggle --}}
                    <div class="sort-toggle">
                        <input type="radio" name="ordered_by" value="asc" id="ordered_by_asc"
                            {{ request('ordered_by') == 'asc' ? 'checked' : '' }} onchange="this.form.submit()" hidden>
                        <label for="ordered_by_asc" class="btn btn-outline-secondary" title="Urutkan A-Z">
                            <i class="fas fa-sort-alpha-down"></i>
                        </label>

                        <input type="radio" name="ordered_by" value="desc" id="ordered_by_desc"
                            {{ request('ordered_by', 'desc') == 'desc' ? 'checked' : '' }} onchange="this.form.submit()"
                            hidden>
                        <label for="ordered_by_desc" class="btn btn-outline-secondary" title="Urutkan Z-A">
                            <i class="fas fa-sort-alpha-up"></i>
                        </label>
                    </div>
                </div>
            </div>

            <div class="table-responsive position-relative mb-3" style="min-height: 200px">
                <table class="table table-sm table-hover fs-14px">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Barang</th>
                            <th>Lokasi Temuan</th>
                            <th>Status</th>
                            <th>Ditemukan</th>
                            <th>Tanggal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse(($items ?? collect()) as $index => $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div>
                                        <div class="fw-semibold">{{ $item->item_name ?? '-' }}</div>
                                        <small class="text-muted">{{ Str::limit($item->description ?? '', 35) }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $item->location_found ?? '-' }}</td>
                            <td>
                                @php
                                $statusClass = match ($item->status) {
                                'Tersedia' => 'badge-success',
                                'Diambil' => 'badge-secondary',
                                default => 'badge-warning',
                                };
                                @endphp
                                <span class="badge rounded-pill {{ $statusClass }} text-white">
                                    {{ $item->status ?? 'Unknown' }}
                                </span>
                            </td>
                            <td>
                                @if ($item->user)
                                <div class="fw-semibold small">{{ $item->user->full_name }}</div>
                                <small class="text-muted">{{ ucfirst($item->user->role->name ?? $item->user->role) }}</small>
                                @else
                                <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td>{{ $item->created_at?->format('d M Y') ?? '-' }}</td>
                            <td class="text-nowrap">
                                @can('edit_lost_items')
                                <a href="{{ route('admin.barang-hilang.edit', $item->item_id) }}"
                                    class="btn btn-light btn-sm border" aria-label="Edit">
                                    <i class="fas fa-pen text-muted"></i>
                                </a>
                                @endcan

                                @can('delete_lost_items')
                                <form action="{{ route('admin.barang-hilang.destroy', $item->item_id) }}"
                                    method="POST" class="d-inline"
                                    onsubmit="return confirm('Yakin ingin menghapus barang ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" aria-label="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                                @endcan
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center">
                                <div class="py-4">
                                    <img src="{{ asset('assets/images/no-data.png') }}" alt="No data"
                                        style="max-width:240px; opacity: 0.5;">
                                    <p>Data Tidak Ada</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-between gap-2 flex-wrap">
                <div class="d-flex justify-content-between showing-wrapper-bawah">
                    <div class="d-flex fs-14px align-items-center gap-1">
                        Menampilkan
                        <select class="form-select form-select-sm w-auto" name="showing" onchange="this.form.submit()">
                            <option value="10">10</option>
                            <option value="20">20</option>
                            <option value="50" selected>50</option>
                            <option value="100">100</option>
                            <option value="all">Semua</option>
                        </select>
                        Data
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>

@push('styles')
<style>
    /* Hide unselected sort labels */
    .sort-toggle input[type="radio"]:not(:checked)+label {
        display: none;
    }
</style>
@endpush
@endsection