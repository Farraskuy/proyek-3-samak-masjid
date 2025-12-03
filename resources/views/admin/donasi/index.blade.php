@extends('admin.layout')

@section('title', 'Verifikasi Donasi')

@section('content')

    <section class="p-3">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-semibold mb-0">Verifikasi Donasi Masuk</h4>
        </div>

        {{-- Alert Notification --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if (session('warning'))
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                {{ session('warning') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row g-0 gap-3">

            <form method="get" id="form_filter" class="col rounded-3 bg-white p-3 pt-0 form-filter"
                style="height: fit-content">

                <div class="bg-white position-sticky pt-3 pb-2" style="top: 0px; z-index: 10">
                    <div class="d-flex gap-2 justify-content-between mb-2 align-items-center">
                        <div class="fw-bold text-muted">Total:
                            {{ $data instanceof \Illuminate\Pagination\LengthAwarePaginator ? $data->total() : $data->count() }}
                            Data</div>

                        <div class="d-flex gap-2">
                            <input type="text" class="form-control" placeholder="Cari Nama / Bank..."
                                value="{{ request()->query('keyword', '') }}" name="keyword">

                            <select name="status" class="form-select" style="width: 130px" onchange="this.form.submit()">
                                <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>Semua Status
                                </option>
                                <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>Pending
                                </option>
                                <option value="Verified" {{ request('status') == 'Verified' ? 'selected' : '' }}>Diterima
                                </option>
                                <option value="Rejected" {{ request('status') == 'Rejected' ? 'selected' : '' }}>Ditolak
                                </option>
                            </select>

                            <select name="donation_type" class="form-select" style="width: 130px"
                                onchange="this.form.submit()">
                                <option value="all" {{ request('donation_type') == 'all' ? 'selected' : '' }}>Semua Tipe
                                </option>
                                <option value="Infaq" {{ request('donation_type') == 'Infaq' ? 'selected' : '' }}>Infaq
                                </option>
                                <option value="Shodaqoh" {{ request('donation_type') == 'Shodaqoh' ? 'selected' : '' }}>
                                    Shodaqoh</option>
                                <option value="Zakat" {{ request('donation_type') == 'Zakat' ? 'selected' : '' }}>Zakat
                                </option>
                                <option value="Wakaf" {{ request('donation_type') == 'Wakaf' ? 'selected' : '' }}>Wakaf
                                </option>
                                <option value="Lainnya" {{ request('donation_type') == 'Lainnya' ? 'selected' : '' }}>
                                    Lainnya</option>
                            </select>

                            {{-- Tombol Sorting --}}
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
                            <input type="hidden" name="ordered_by_val" id="ordered_by_val"
                                value="{{ request('ordered_by', 'desc') }}">
                        </div>
                    </div>
                </div>

            </form>
            <div class="table-responsive position-relative mb-3 bg-white p-3" style="min-height: 200px">
                <table class="table table-hover fs-14px align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Donatur</th>
                            <th>Info Transfer</th>
                            <th>Tipe</th>
                            <th>Jumlah</th>
                            <th>Tanggal Trf</th>
                            <th>Bukti</th>
                            <th>Status</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse(($data ?? []) as $index => $row)
                            <tr>
                                <td>{{ $data instanceof \Illuminate\Pagination\LengthAwarePaginator ? $data->firstItem() + $index : $index + 1 }}
                                </td>

                                {{-- Donatur --}}
                                <td>
                                    <div class="fw-bold">{{ $row->user->name ?? $row->guest_name }}</div>
                                    <small class="text-muted">{{ $row->user->email ?? '-' }}</small>
                                </td>

                                {{-- Info Bank --}}
                                <td>
                                    <small class="d-block text-muted">Dari: <span
                                            class="text-dark fw-bold">{{ $row->source_bank }}</span></small>
                                    <small class="d-block text-muted">Ke: <span
                                            class="text-primary fw-bold">{{ $row->destinationAccount->bank_name ?? 'Bank Kita' }}</span></small>
                                </td>

                                {{-- Tipe --}}
                                <td>
                                    <small class="d-block text-muted">
                                        <span>{{ $row->donation_type }}</span>
                                    </small>
                                </td>

                                {{-- Jumlah --}}
                                <td class="fw-bold text-success">
                                    Rp {{ number_format($row->amount, 0, ',', '.') }}
                                </td>

                                {{-- Tanggal --}}
                                <td>{{ \Carbon\Carbon::parse($row->transfer_date)->format('d M Y') }}</td>

                                {{-- Bukti --}}
                                <td>
                                    @if (!empty($row->proof_image_url))
                                        <a href="{{ asset($row->proof_image_url) }}" target="_blank"
                                            class="btn btn-sm btn-outline-info">
                                            <i class="fa-regular fa-image"></i> Lihat
                                        </a>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>

                                {{-- Status --}}
                                <td>
                                    @if ($row->status == 'Verified')
                                        <span class="badge bg-success">Diterima</span>
                                    @elseif($row->status == 'Rejected')
                                        <span class="badge bg-danger">Ditolak</span>
                                    @else
                                        <span class="badge bg-warning text-dark">Pending</span>
                                    @endif
                                </td>

                                {{-- Aksi --}}
                                <td class="text-end">
                                    @if ($row->status == 'Pending')
                                        <div class="d-flex justify-content-end gap-1">

                                            {{-- 1. TOMBOL TERIMA  --}}
                                            @can('verify_donation')
                                                <form action="{{ route('admin.donasi.approve', $row->confirmation_id) }}"
                                                    method="POST"
                                                    onsubmit="return confirm('Yakin terima donasi ini? Dana akan otomatis masuk ke Laporan Keuangan.');">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-success" title="Terima">
                                                        <i class="fa-solid fa-check"></i>
                                                    </button>
                                                </form>

                                                {{-- 3. TOMBOL TOLAK --}}
                                                <form action="{{ route('admin.donasi.reject', $row->confirmation_id) }}"
                                                    method="POST" onsubmit="return confirm('Yakin tolak donasi ini?');">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-danger" title="Tolak">
                                                        <i class="fa-solid fa-xmark"></i>
                                                    </button>
                                                </form>
                                            @endcan

                                        </div>
                                    @else
                                        <span class="text-muted small">Selesai</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-5">
                                    <img src="{{ asset('assets/images/no-data.png') }}" alt="No data"
                                        style="max-width:150px; opacity: 0.5;">
                                    <p class="text-muted mt-2">Belum ada data konfirmasi donasi.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-between align-items-center flex-wrap p-3 bg-white rounded-bottom">
                <div class="d-flex fs-14px align-items-center gap-2">
                    Menampilkan
                    <select class="form-select form-select-sm w-auto" name="showing" form="form_filter"
                        onchange="document.getElementById('form_filter').submit()">
                        <option value="10" {{ request('showing') == 10 ? 'selected' : '' }}>10</option>
                        <option value="20" {{ request('showing') == 20 ? 'selected' : '' }}>20</option>
                        <option value="50" {{ request('showing') == 50 ? 'selected' : '' }}>50</option>
                        <option value="all" {{ request('showing') == 'all' ? 'selected' : '' }}>Semua</option>
                    </select>
                    Data
                </div>
                <div class="paginate">
                    @if ($data instanceof \Illuminate\Pagination\LengthAwarePaginator)
                        {{ $data->onEachSide(1)->links() }}
                    @endif
                </div>
            </div>

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
