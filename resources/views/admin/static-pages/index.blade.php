@extends('admin.layout')

@section('title', 'Halaman Statis')

@section('content')
    <section class="p-3">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-semibold mb-0">Manajemen Halaman Statis</h4>
             @if (optional(auth()->user())->role === 'admin')
                <a href="{{ route('admin.static-pages.tambah') }}" class="btn btn-success fw-semibold">
                    <i class="fas fa-plus me-1"></i> Tambah Data
                </a>
            @endif
        </div>

        <div class="row g-0 gap-3">
            <form method="get" id="form_filter" class="col rounded-3 bg-white p-3 pt-0 form-filter"
                style="height: fit-content">
                <div class="alert-container"></div>

                <div class="bg-white position-sticky pt-3 pb-2" style="top: 61px; z-index: 1">
                    <div class="d-flex gap-2 justify-content-end mb-2">
                        <input type="text" class="form-control" placeholder="Cari"
                            value="{{ request()->query('keyword', '') }}" name="keyword">

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
                                <th>Judul</th>
                                <th>Deskripsi</th>
                                <th>Diperbarui Pada</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse(($pages ?? collect()) as $index => $row)
                                <tr>
                                    <td>{{ ($pages->firstItem() ?? 0) + $index }}</td>
                                    <td><strong>{{ $row->title ?? '-' }}</strong></td>
                                    <td>{{ Str::limit($row->description, 50) ?? '-' }}</td>
                                    <td>{{ $row->updated_at?->format('d M Y H:i') ?? '-' }}</td>
                                    <td class="text-nowrap">
                                        <a href="{{ route('admin.static-pages.edit', $row->id) }}"
                                            class="btn btn-light btn-sm border" aria-label="Edit" title="Edit">
                                            <i class="fas fa-pen text-muted"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">
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
                                <option {{ request()->query('showing', 50) == 10 ? 'selected' : '' }}>10</option>
                                <option {{ request()->query('showing', 50) == 20 ? 'selected' : '' }}>20</option>
                                <option {{ request()->query('showing', 50) == 50 ? 'selected' : '' }}>50</option>
                                <option {{ request()->query('showing', 50) == 100 ? 'selected' : '' }}>100</option>
                                <option value="all" {{ request()->query('showing') == 'all' ? 'selected' : '' }}>Semua
                                </option>
                            </select>
                            Data
                        </div>
                    </div>
                    <div class="paginate">
                        @if (isset($pages) && method_exists($pages, 'links'))
                            {{ $pages->onEachSide(1)->links() }}
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
