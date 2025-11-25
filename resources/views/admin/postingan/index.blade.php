@extends('admin.layout')

@section('title', 'Postingan')

@section('content')
    <section class="p-3">
        <section class="p-3">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="fw-semibold mb-0">Manajemen Postingan</h4>
                <a href="{{ url('admin/postingan/tambah') }}" class="btn btn-success fw-semibold">
                    <i class="fas fa-plus me-1"></i> Tambah Data
                </a>
            </div>

            <div class="row g-0 gap-3">
                <form method="get" id="form_filter" class="col rounded-3 bg-white p-3 pt-0 form-filter"
                    style="height: fit-content">
                    <div class="alert-container"></div>

                    <div class="bg-white position-sticky pt-3 pb-2" style="top: 61px; z-index: 1">
                        <div class="d-flex gap-2 justify-content-end mb-2">
                            <input type="text" class="form-control" placeholder="Cari"
                                value="{{ request()->query('keyword', '') }}" name="keyword">
                            <select class="form-select fs-14px h-100 w-auto" style="line-height: 1.7" name="sorted_by">
                                <option value="">Urutkan berdasarkan</option>
                            </select>

                            {{-- Sort Toggle --}}
                            <div class="sort-toggle">
                                <input type="radio" name="ordered_by" value="asc" id="ordered_by_asc"
                                    {{ request('ordered_by') == 'asc' ? 'checked' : '' }} onchange="this.form.submit()"
                                    hidden>
                                <label for="ordered_by_asc" class="btn btn-outline-secondary" title="Urutkan A-Z">
                                    <i class="fas fa-sort-alpha-down"></i>
                                </label>

                                <input type="radio" name="ordered_by" value="desc" id="ordered_by_desc"
                                    {{ request('ordered_by', 'desc') == 'desc' ? 'checked' : '' }}
                                    onchange="this.form.submit()" hidden>
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
                                    <th>Kategori</th>
                                    <th>Status</th>
                                    <th>Tanggal</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse(($data ?? collect()) as $index => $row)
                                    <tr>
                                        <td>{{ ($data->firstItem() ?? 0) + $index }}</td>
                                        <td>{{ $row->title ?? '-' }}</td>
                                        <td>{{ $row->kategori ?? '-' }}</td>
                                        <td>
                                            @php
                                                $status = strtolower($row->status ?? '');
                                                $badgeClass = 'text-bg-secondary';

                                                if (in_array($status, ['published', 'dipublikasikan'])) {
                                                    $badgeClass = 'text-bg-success';
                                                } elseif (in_array($status, ['draft', 'arsip'])) {
                                                    $badgeClass = 'text-bg-warning';
                                                } elseif (in_array($status, ['rejected', 'ditolak'])) {
                                                    $badgeClass = 'text-bg-danger';
                                                }
                                            @endphp
                                            <span class="badge rounded-pill {{ $badgeClass }}">{{ $row->status }}</span>
                                        </td>
                                        <td>{{ $row->created_at ?? '-' }}</td>
                                        <td class="text-nowrap">
                                            <a href="/admin/artikel/edit/{{ $row->id }}"
                                                class="btn btn-light btn-sm border" aria-label="Edit">
                                                <i class="fas fa-pen text-muted"></i>
                                            </a>

                                            @if (optional(auth()->user())->role === 'super admin')
                                                <button type="button" class="btn btn-danger btn-sm btn-delete-article"
                                                    data-action="{{ url('/admin/artikel/delete/' . $row->id) }}"
                                                    aria-label="Hapus">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">
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
                                <select class="form-select form-select-sm w-auto" name="showing"
                                    onchange="this.form.submit()">
                                    <option {{ request()->query('showing', 50) == 10 ? 'selected' : '' }}>10</option>
                                    <option {{ request()->query('showing', 50) == 20 ? 'selected' : '' }}>20</option>
                                    <option {{ request()->query('showing', 50) == 50 ? 'selected' : '' }}>50</option>
                                    <option {{ request()->query('showing', 50) == 100 ? 'selected' : '' }}>100</option>
                                    <option value="all" {{ request()->query('showing') == 'all' ? 'selected' : '' }}>
                                        Semua
                                    </option>
                                </select>
                                Data
                            </div>
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
