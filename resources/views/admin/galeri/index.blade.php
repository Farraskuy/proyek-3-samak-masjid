@extends('admin.layout')

@section('title', 'Galeri')

@section('content')
    @php
        $columns = ['#', 'Judul', 'Tanggal', 'Aksi'];
    @endphp

    <section class="p-3">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="fw-semibold mb-0">Galeri</h4>
            @can('create_gallery')
                <a href="{{ route('admin.galeri.create') }}" class="btn btn-success fw-bold">
                    <i class="fa-solid fa-plus me-1"></i> Tambah Album
                </a>
            @endcan
        </div>

        <div class="row g-0 gap-3">
            <form method="get" id="form_filter" class="col rounded-3 bg-white p-3 pt-0 form-filter"
                style="height: fit-content">

                <div class="alert-container"></div>

                <div class="bg-white position-sticky pt-3 pb-2" style="top: 61px; z-index: 1">
                    <div class="d-flex gap-2 justify-content-end mb-2">
                        <div class="input-group">
                            <input type="text" class="form-control form-control-sm" placeholder="Cari album..."
                                value="{{ request()->query('keyword', '') }}" name="keyword">
                            <button class="btn btn-sm btn-outline-secondary" type="submit">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>

                        <select class="form-select fs-14px h-100 w-auto" name="sorted_by">
                            <option value="">Urutkan berdasarkan</option>
                        </select>

                        <div class="btn-group">
                            <button type="button" class="btn btn-outline-secondary"
                                onclick="document.getElementById('ordered_by_asc').checked = true; this.form.submit();">Asc</button>
                            <button type="button" class="btn btn-outline-secondary"
                                onclick="document.getElementById('ordered_by_desc').checked = true; this.form.submit();">Desc</button>
                        </div>
                        <input type="radio" name="ordered_by" value="asc" id="ordered_by_asc" hidden>
                        <input type="radio" name="ordered_by" value="desc" id="ordered_by_desc" hidden checked>
                    </div>
                </div>

                <div class="table-responsive mb-3" style="min-height: 200px">
                    <table class="table table-sm table-hover fs-14px">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Judul Album</th>
                                <th>Tanggal</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse(($data ?? collect()) as $index => $row)
                                <tr>
                                    <td>{{ ($data->firstItem() ?? 0) + $index }}</td>
                                    <td>{{ $row->album_name ?? '-' }}</td>
                                    <td>{{ $row->created_at?->format('d M Y') ?? '-' }}</td>

                                    <td>
                                        <div class="btn-group btn-group-sm" role="group">

                                            {{-- Tombol Edit --}}
                                            @can('edit_gallery')
                                                <a href="{{ route('admin.galeri.edit', $row->album_id) }}"
                                                    class="btn btn-sm btn-light border">
                                                    <i class="fas fa-pen text-muted"></i></a>
                                                </a>
                                            @endcan

                                            {{-- Tombol Delete (Diperbarui) --}}
                                            @can('delete_gallery')
                                                <button type="button"
                                                    class="btn btn-sm btn-light border text-danger btn-delete-article"
                                                    data-action="{{ route('admin.galeri.delete', $row->album_id) }}">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            @endcan

                                        </div>
                                    </td>
                                </tr>

                            @empty
                                <tr>
                                    <td colspan="4" class="text-center">
                                        <div class="py-4">
                                            <img src="{{ asset('assets/images/no-data.png') }}" alt="Tidak ada data"
                                                style="max-width:240px;opacity:0.5;">
                                            <p>Data Tidak Ada</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>

                    </table>
                </div>

                <div class="d-flex justify-content-between gap-2 flex-wrap">
                    <div class="d-flex fs-14px align-items-center gap-1">
                        Menampilkan
                        <select class="form-select form-select-sm w-auto" name="showing" onchange="this.form.submit()">
                            <option value="10" {{ request()->query('showing', 50) == 10 ? 'selected' : '' }}>10</option>
                            <option value="20" {{ request()->query('showing', 50) == 20 ? 'selected' : '' }}>20</option>
                            <option value="50" {{ request()->query('showing', 50) == 50 ? 'selected' : '' }}>50
                            </option>
                            <option value="100" {{ request()->query('showing', 50) == 100 ? 'selected' : '' }}>100
                            </option>
                            <option value="all" {{ request()->query('showing') == 'all' ? 'selected' : '' }}>Semua
                            </option>
                        </select>
                        Data
                    </div>

                    <div class="paginate">
                        @if (isset($data) && method_exists($data, 'links'))
                            {{ $data->onEachSide(1)->links() }}
                        @endif
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