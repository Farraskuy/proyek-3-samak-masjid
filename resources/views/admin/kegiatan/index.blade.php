@extends('admin.layout')

@section('title', 'Kegiatan')

@section('content')
    @php
        $columns = ['#', 'Judul', 'Lokasi', 'Tanggal', 'Aksi'];
    @endphp

    <section class="p-3">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-semibold mb-0">Kegiatan</h4>
            @can('create_events')
                <a href="{{ route('admin.kegiatan.create') }}" class="btn btn-success fw-semibold">
                    <i class="fa-solid fa-plus me-1"></i> Tambah Kegiatan
                </a>
            @endcan
        </div>

        @if (session('pj_credentials'))
            <div class="alert alert-success border-0 shadow-sm mb-4">
                <h5 class="alert-heading fw-bold"><i class="fas fa-check-circle me-2"></i> Akun Penanggung Jawab Berhasil
                    Dibuat!</h5>
                <p>Silakan catat kredensial berikut karena tidak akan ditampilkan lagi:</p>
                <hr>
                <div class="d-flex gap-4">
                    <div>
                        <small class="text-muted d-block uppercase tracking-wide font-bold">Email</small>
                        <span class="fw-bold fs-5">{{ session('pj_credentials')['email'] }}</span>
                    </div>
                    <div>
                        <small class="text-muted d-block uppercase tracking-wide font-bold">Password</small>
                        <span
                            class="fw-bold fs-5 font-monospace bg-light px-2 rounded">{{ session('pj_credentials')['password'] }}</span>
                    </div>
                </div>
            </div>
        @endif

        <div class="row g-0 gap-3">
            <form method="get" id="form_filter" class="col rounded-3 bg-white p-3 pt-0 form-filter"
                style="height: fit-content">
                <div class="alert-container"></div>

                <div class="bg-white position-sticky pt-3 pb-2" style="top: 61px; z-index: 1">
                    <div class="d-flex gap-2 justify-content-end mb-2">
                        <input type="text" class="form-control" placeholder="Cari"
                            value="{{ request()->query('keyword', '') }}" name="keyword">

                        <select class="form-select fs-14px h-100 w-auto" name="sorted_by">
                            <option value="">Urutkan berdasarkan</option>
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

                <div class="table-responsive mb-3" style="min-height: 200px">
                    <table class="table table-sm table-hover fs-14px">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Judul</th>
                                <th>Lokasi</th>
                                <th>Tanggal</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse(($data ?? collect()) as $index => $row)
                                <tr>
                                    <td>{{ ($data->firstItem() ?? 0) + $index }}</td>
                                    <td>{{ $row->event_name }}</td>
                                    <td>{{ $row->location }}</td>
                                    <td>{{ $row->start_time }}</td>
                                    <td>
                                        <div class="btn-group btn-group-sm" role="group">
                                            @can('edit_events')
                                                <a href="{{ route('admin.kegiatan.edit', $row->event_id) }}"
                                                    class="btn btn-sm btn-light border">
                                                    <i class="fas fa-pen text-muted"></i>
                                                </a>
                                            @endcan

                                            @can('delete_events')
                                                <button type="button"
                                                    class="btn btn-sm btn-light border text-danger"
                                                    onclick="showDeleteModal('{{ route('admin.kegiatan.destroy', $row->event_id) }}')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">
                                        <div class="py-4">
                                            <img src="{{ asset('assets/images/no-data.png') }}"
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
                            <option {{ request()->query('showing', 50) == 10 ? 'selected' : '' }}>10</option>
                            <option {{ request()->query('showing', 50) == 20 ? 'selected' : '' }}>20</option>
                            <option {{ request()->query('showing', 50) == 50 ? 'selected' : '' }}>50</option>
                            <option {{ request()->query('showing', 50) == 100 ? 'selected' : '' }}>100</option>
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

    @push('scripts')
        <script>
            function showDeleteModal(actionUrl) {
                const form = document.getElementById('confirmDeleteForm');
                const modalEl = document.getElementById('confirmDeleteModal');
                
                if (form && modalEl) {
                    form.action = actionUrl;
                    const modal = new bootstrap.Modal(modalEl);
                    modal.show();
                } else {
                    console.error('Modal atau form tidak ditemukan!');
                    if (!form) console.error('confirmDeleteForm tidak ada');
                    if (!modalEl) console.error('confirmDeleteModal tidak ada');
                }
            }
        </script>
    @endpush

    @push('styles')
        <style>
            /* Hide unselected sort labels */
            .sort-toggle input[type="radio"]:not(:checked)+label {
                display: none;
            }
        </style>
    @endpush
@endsection