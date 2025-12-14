@extends('admin.layout')
@section('title', 'Laporan Barang Hilang')

@section('content')
<section class="p-3">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-semibold mb-0">Manajemen Barang Hilang</h4>
        @can('create_lost_items')
        <a href="{{ route('admin.lost-items.create') }}" class="btn btn-success fw-semibold">
            <i class="fas fa-plus me-1"></i> Tambah Laporan
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

            {{-- Filter & Search Bar --}}
            <div class="bg-white position-sticky pt-3 pb-2" style="top: 61px; z-index: 1">
                <div class="d-flex gap-2 justify-content-end mb-2">
                    <input type="text" class="form-control" placeholder="Cari nama barang..."
                        value="{{ request()->query('keyword', '') }}" name="keyword">

                    <select class="form-select fs-14px h-100 w-auto" style="line-height: 1.7" name="sorted_by">
                        <option value="">Urutkan berdasarkan</option>
                        <option value="name" {{ request('sorted_by') == 'name' ? 'selected' : '' }}>Nama Barang</option>
                        <option value="date" {{ request('sorted_by') == 'date' ? 'selected' : '' }}>Tanggal Hilang</option>
                    </select>

                    {{-- Sort Toggle (ASC/DESC) --}}
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

            {{-- Table Data --}}
            <div class="table-responsive position-relative mb-3" style="min-height: 200px">
                <table class="table table-sm table-hover fs-14px">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Barang</th>
                            <th>Kategori</th>
                            <th>Lokasi Hilang</th>
                            <th>Tanggal Hilang</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse(($items ?? collect()) as $index => $item)
                        <tr>
                            <td>{{ $loop->iteration + $items->firstItem() - 1 }}</td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div>
                                        <div class="fw-semibold">{{ $item->item_name }}</div>
                                        <small class="text-muted">{{ Str::limit($item->description, 40) }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border">
                                    {{ $item->category->name ?? '-' }}
                                </span>
                            </td>
                            <td>{{ $item->location_lost ?? '-' }}</td>
                            <td>{{ $item->lost_at?->format('d M Y') ?? '-' }}</td>
                            <td class="text-nowrap">
                                @can('edit_lost_items')
                                <a href="{{ route('admin.lost-items.edit', $item->id) }}"
                                    class="btn btn-light btn-sm border" aria-label="Edit">
                                    <i class="fas fa-pen text-muted"></i>
                                </a>
                                @endcan

                                @can('delete_lost_items')
                                <button type="button" class="btn btn-danger btn-sm btn-delete-article"
                                    data-action="{{ route('admin.lost-items.destroy', $item->id) }}"
                                    aria-label="Hapus">
                                    <i class="fas fa-trash"></i>
                                </button>
                                @endcan
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center">
                                <div class="py-4">
                                    <img src="{{ asset('assets/images/no-data.png') }}" alt="No data"
                                        style="max-width:240px; opacity: 0.5;">
                                    <p class="text-muted mt-2">Data Tidak Ada</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="d-flex justify-content-between gap-2 flex-wrap">
                <div class="d-flex justify-content-between showing-wrapper-bawah">
                    <div class="d-flex fs-14px align-items-center gap-1">
                        Menampilkan
                        <select class="form-select form-select-sm w-auto" name="showing" onchange="this.form.submit()">
                            <option value="10" {{ request('showing') == '10' ? 'selected' : '' }}>10</option>
                            <option value="20" {{ request('showing') == '20' ? 'selected' : '' }}>20</option>
                            <option value="50" {{ request('showing') == '50' ? 'selected' : '' }}>50</option>
                        </select>
                        Data
                    </div>
                </div>
                {{ $items->links() }}
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