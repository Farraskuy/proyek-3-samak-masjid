@extends('admin.layout')

@section('title', 'Postingan')

@section('content')
    <section class="p-3">
        {{-- Header & Tombol Tambah --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-semibold mb-0">Manajemen Postingan</h4>
            @if (optional(auth()->user())->role !== 'super admin')
                <a href="{{ route('admin.postingan.create') }}" class="btn btn-success fw-semibold">
                    <i class="fas fa-plus me-1"></i> Tambah Data
                </a>
            @endif
        </div>

        {{-- Filter Cepat (Quick Links) --}}
        <div class="d-flex gap-2 mb-4 p-2 rounded-pill" style="background-color: rgba(0,0,0,0.05); width: fit-content;">
            <a href="{{ route('admin.postingan.index', ['status' => 'all']) }}"
                class="btn btn-sm {{ ($status ?? 'all') == 'all' ? 'btn-dark' : 'btn-light text-secondary' }} rounded-pill px-4 fw-semibold">
                Semua
            </a>
            <a href="{{ route('admin.postingan.index', ['status' => 'pending']) }}"
                class="btn btn-sm {{ ($status ?? 'all') == 'pending' ? 'btn-dark' : 'btn-light text-secondary' }} rounded-pill px-4 fw-semibold">
                Menunggu Approval
            </a>
            <a href="{{ route('admin.postingan.index', ['status' => 'approved']) }}"
                class="btn btn-sm {{ ($status ?? 'all') == 'approved' ? 'btn-dark' : 'btn-light text-secondary' }} rounded-pill px-4 fw-semibold">
                Disetujui
            </a>
            <a href="{{ route('admin.postingan.index', ['status' => 'rejected']) }}"
                class="btn btn-sm {{ ($status ?? 'all') == 'rejected' ? 'btn-dark' : 'btn-light text-secondary' }} rounded-pill px-4 fw-semibold">
                Ditolak
            </a>
            <a href="{{ route('admin.postingan.index', ['status' => 'revision']) }}"
                class="btn btn-sm {{ ($status ?? 'all') == 'revision' ? 'btn-dark' : 'btn-light text-secondary' }} rounded-pill px-4 fw-semibold">
                Revisi
            </a>
        </div>


        <div class="row g-0 gap-3">
            <form method="get" id="form_filter" class="col rounded-3 bg-white p-3 pt-0 form-filter"
                style="height: fit-content">
                <div class="alert-container"></div>

                {{-- Toolbar Pencarian & Sorting --}}
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
                                {{ request('ordered_by') == 'asc' ? 'checked' : '' }} onchange="this.form.submit()" hidden>
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


                {{-- Tabel Data --}}
                <div class="table-responsive position-relative mb-3" style="min-height: 200px">
                    <table class="table table-sm table-hover fs-14px">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Judul</th>
                                <th>Kategori</th>
                                <th>Status postingan</th>
                                <th>Keputusan</th>
                                <th>Revisi Msg</th> {{-- KOLOM BARU --}}
                                <th>Tanggal dibuat</th>
                                <th>Tanggal update</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse(($data ?? collect()) as $index => $row)
                                <tr>
                                    <td>{{ ($data->firstItem() ?? 0) + $index }}</td>
                                    <td>{{ $row->title ?? '-' }}</td>
                                    <td>{{ $row->kategori ?? '-' }}</td>
                                    
                                    {{-- Status Postingan (Draft/Published etc) --}}
                                    <td>
                                        @if (strtolower($row->status ?? '') == 'published')
                                            <span class="badge rounded-pill text-bg-success">{{ $row->status }}</span>
                                        @elseif(strtolower($row->status ?? '') == 'not published')
                                            <span class="badge rounded-pill text-bg-light text-danger-emphasis border border-danger-subtle">{{ $row->status }}</span>
                                        @elseif(strtolower($row->status ?? '') == 'pending')
                                            <span class="badge rounded-pill text-bg-warning">{{ $row->status }}</span>
                                        @elseif(strtolower($row->status ?? '') == 'revisi')
                                            <span class="badge rounded-pill text-bg-info text-white">{{ $row->status }}</span>
                                        @elseif (strtolower($row->status ?? '') == 'draft')
                                            <span class="badge rounded-pill text-bg-secondary">{{ $row->status }}</span>
                                        @else
                                            <span class="badge rounded-pill text-bg-secondary">{{ $row->status }}</span>
                                        @endif
                                    </td>

                                    {{-- Keputusan (Approved/Rejected/Revision) --}}
                                    <td>
                                        @php
                                            $status = strtolower($row->approval_status ?? '');
                                        @endphp

                                        @if ($status == 'pending')
                                            <span class="badge rounded-pill text-bg-warning border border-warning-subtle"> - </span>
                                        @elseif ($status == 'approved')
                                            <span class="badge rounded-pill text-bg-success border border-success-subtle">
                                                Approved
                                            </span>
                                        @elseif ($status == 'rejected')
                                            <span class="badge rounded-pill text-bg-danger border border-danger-subtle">
                                                Rejected
                                            </span>
                                        @elseif ($status == 'revision')
                                            {{-- REVISI DISINI SEKARANG HANYA BADGE STATIS (TIDAK ADA MODAL) --}}
                                            <span class="badge rounded-pill text-bg-info text-white border border-info-subtle">
                                                Revision
                                            </span>
                                        @else
                                            <span class="badge rounded-pill text-bg-secondary border border-secondary-subtle">
                                                {{ $row->approval_status ?? '-' }}
                                            </span>
                                        @endif
                                    </td>

                                    {{-- Revisi Msg (Kolom Baru) --}}
                                    <td>
                                        @if (optional(auth()->user())->role === 'admin' && strtolower($row->approval_status ?? '') === 'revision')
                                            
                                            {{-- Tombol Detail Revisi --}}
                                            <button type="button" class="btn btn-info btn-sm text-white py-0 px-2" style="font-size: 0.75rem;" 
                                                data-bs-toggle="modal" data-bs-target="#modalRevision{{ $row->id }}">
                                                Detail Revisi
                                            </button>

                                            {{-- MODAL REVISI (Dipindah ke sini) --}}
                                            <div class="modal fade text-dark" id="modalRevision{{ $row->id }}"
                                                tabindex="-1" aria-labelledby="modalRevisionLabel{{ $row->id }}"
                                                aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="modalRevisionLabel{{ $row->id }}">
                                                                Detail Revisi
                                                            </h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                                aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <p>Catatan Revisi:</p>
                                                            <div class="alert alert-warning border">
                                                                <strong>{{ $row->approval_note ?? 'Tidak ada catatan revisi.' }}</strong>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                data-bs-dismiss="modal">Tutup</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            {{-- MODAL END --}}

                                        @else
                                            {{-- Jika bukan admin atau bukan revisi, tampilkan strip --}}
                                            -
                                        @endif
                                    </td>

                                    <td>{{ $row->created_at ?? '-' }}</td>
                                    <td>{{ $row->updated_at ?? '-' }}</td>

                                    {{-- Aksi --}}
                                    <td class="text-nowrap text-end">
                                        
                                        @if (optional(auth()->user())->role === 'super admin' && $row->approval_status === 'pending')
                                            <a href="{{ route('admin.postingan.approval.show', $row->id) }}"
                                                class="btn btn-primary btn-sm border" aria-label="Approval">
                                                <i class="fas fa-check-to-slot"></i> Approval
                                            </a>
                                        @endif

                                        <a href="/admin/postingan/edit/{{ $row->id }}"
                                            class="btn btn-light btn-sm border" aria-label="Edit">
                                            <i class="fas fa-pen text-muted"></i>
                                        </a>

                                        @if (optional(auth()->user())->role === 'super admin')
                                            <button type="button" class="btn btn-danger btn-sm btn-delete-article"
                                                data-action="{{ url('/admin/postingan/delete/' . $row->id) }}"
                                                aria-label="Hapus">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center"> {{-- colspan disesuaikan karena tambah kolom --}}
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

                {{-- Pagination --}}
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