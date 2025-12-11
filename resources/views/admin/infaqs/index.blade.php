@extends('admin.layout')

@section('title', 'Kelola Program Infaq')

@section('content')
    <section class="p-3">

        <form method="get" id="form_filter" class="bg-white p-3 rounded-3 shadow-sm">

            {{-- Header & Quick Filters --}}
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-3">
                <h4 class="fw-semibold mb-0">Program Infaq</h4>

                <div class="d-flex gap-2 overflow-auto pb-2 pb-md-0">
                    <button type="submit" name="status" value="all"
                        class="btn btn-sm rounded-pill {{ request('status', 'all') == 'all' ? 'btn-dark' : 'btn-outline-secondary' }} px-3">
                        Semua
                    </button>
                    <button type="submit" name="status" value="active"
                        class="btn btn-sm rounded-pill {{ request('status') == 'active' ? 'btn-success' : 'btn-outline-secondary' }} px-3">
                        Aktif
                    </button>
                    <button type="submit" name="status" value="inactive"
                        class="btn btn-sm rounded-pill {{ request('status') == 'inactive' ? 'btn-secondary' : 'btn-outline-secondary' }} px-3">
                        Nonaktif
                    </button>
                </div>
            </div>

            {{-- Sticky Toolbar --}}
            <div class="sticky-top bg-white py-2 z-2 border-bottom mb-3" style="top: 61px;">
                <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
                    <div class="d-flex gap-2 col-12 col-md-auto">
                        <a href="{{ route('admin.infaqs.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus me-1"></i> Tambah Program
                        </a>
                    </div>

                    <div class="d-flex gap-2 col-12 col-md-auto flex-grow-1 flex-md-grow-0">
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-light border-end-0"><i
                                    class="fas fa-search text-muted"></i></span>
                            <input type="text" name="keyword" class="form-control border-start-0 bg-light"
                                placeholder="Cari program..." value="{{ request('keyword') }}">
                        </div>

                        <select name="sort" class="form-select form-select-sm" style="width: 130px;"
                            onchange="document.getElementById('form_filter').submit()">
                            <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Terbaru</option>
                            <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Terlama</option>
                            <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Nama (A-Z)
                            </option>
                            <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Nama (Z-A)
                            </option>
                        </select>
                    </div>
                </div>
            </div>

            {{-- Table --}}
            <div class="table-responsive">
                <table class="table table-hover table-sm align-middle fs-14px">
                    <thead>
                        <tr>
                            <th width="50" class="fw-semibold">No</th>
                            <th width="80" class="fw-semibold">Poster</th>
                            <th class="fw-semibold">Nama Program</th>
                            <th class="fw-semibold">Rekening Tujuan</th>
                            <th width="100" class="fw-semibold">Status</th>
                            <th width="100" class="text-end fw-semibold">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($infaqs as $index => $infaq)
                            <tr>
                                <td>{{ $infaqs->firstItem() + $index }}</td>
                                <td>
                                    @if ($infaq->poster_url)
                                        <img src="{{ asset($infaq->poster_url) }}" alt="Poster"
                                            class="rounded object-fit-cover" width="40" height="40">
                                    @else
                                        <div class="bg-light rounded d-flex align-items-center justify-content-center text-muted"
                                            style="width: 40px; height: 40px;">
                                            <i class="fas fa-image"></i>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <div class="fw-bold text-dark">{{ $infaq->name }}</div>
                                    <div class="small text-muted text-truncate" style="max-width: 250px;">
                                        {{ Str::limit($infaq->description, 60) }}
                                    </div>
                                </td>
                                <td>
                                    @if ($infaq->bankAccount)
                                        <div class="d-flex align-items-center gap-2">
                                            @if ($infaq->bankAccount->logo_url)
                                                <img src="{{ asset($infaq->bankAccount->logo_url) }}" width="20"
                                                    height="20" class="object-fit-contain">
                                            @endif
                                            <div>
                                                <div class="fw-semibold">{{ $infaq->bankAccount->bank_name }}</div>
                                                <div class="small text-muted">{{ $infaq->bankAccount->account_number }}
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <span class="text-danger small"><i class="fas fa-exclamation-circle"></i> Rekening
                                            dihapus</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($infaq->is_active)
                                        <span
                                            class="badge bg-success-subtle text-success border border-success-subtle rounded-pill">Aktif</span>
                                    @else
                                        <span
                                            class="badge bg-secondary-subtle text-secondary border border-secondary-subtle rounded-pill">Nonaktif</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <div class="d-flex justify-content-end gap-1">
                                        <a href="{{ route('admin.infaqs.edit', $infaq->id) }}"
                                            class="btn btn-sm btn-light border text-primary" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button"
                                            class="btn btn-sm btn-light border text-danger btn-delete-article"
                                            data-action="{{ route('admin.infaqs.destroy', $infaq->id) }}" title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">
                                    <i class="fas fa-box-open fa-2x mb-3 opacity-50"></i>
                                    <p class="mb-0">Belum ada program infaq.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Footer Pagination --}}
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center border-top pt-3 mt-3">
                <small class="text-muted mb-2 mb-md-0">
                    Menampilkan {{ $infaqs->firstItem() ?? 0 }} - {{ $infaqs->lastItem() ?? 0 }} dari
                    {{ $infaqs->total() }} data
                </small>
                <div>
                    {{ $infaqs->links('pagination::bootstrap-5') }}
                </div>
            </div>

        </form>
    </section>
@endsection
