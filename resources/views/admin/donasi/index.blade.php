@extends('admin.layout')

@section('title', 'Verifikasi Donasi')

@section('content')

    <section class="p-3">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-semibold mb-0">Verifikasi Donasi Masuk</h4>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if (session('warning'))
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                {{ session('warning') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Status Tabs -->
        {{-- Filter Cepat (Quick Links) --}}
        <div class="d-flex gap-2 mb-4 p-2 rounded-pill" style="background-color: rgba(0,0,0,0.05); width: fit-content;">
            <a href="?status=all"
                class="btn btn-sm {{ ($status ?? 'all') == 'all' ? 'btn-dark' : 'btn-light text-secondary' }} rounded-pill px-4 fw-semibold">
                Semua
            </a>
            <a href="?status=Pending"
                class="btn btn-sm {{ ($status ?? 'all') == 'Pending' ? 'btn-dark' : 'btn-light text-secondary' }} rounded-pill px-4 fw-semibold">
                Pending
            </a>
            <a href="?status=Verified"
                class="btn btn-sm {{ ($status ?? 'all') == 'Verified' ? 'btn-dark' : 'btn-light text-secondary' }} rounded-pill px-4 fw-semibold">
                Diterima
            </a>
            <a href="?status=Rejected"
                class="btn btn-sm {{ ($status ?? 'all') == 'Rejected' ? 'btn-dark' : 'btn-light text-secondary' }} rounded-pill px-4 fw-semibold">
                Ditolak
            </a>
        </div>

        <div class="row g-0 gap-3">
            <form method="get" id="form_filter" class="col rounded-3 bg-white p-3 pt-0 form-filter"
                style="height: fit-content">
                <input type="hidden" name="status" value="{{ $status }}">

                <div class="bg-white position-sticky pt-3 pb-2" style="top: 61px; z-index: 10">
                    <div class="d-flex gap-2 justify-content-end mb-2 align-items-center">
                        <input type="text" class="form-control" placeholder="Cari Nama / Bank..."
                            value="{{ request()->query('keyword', '') }}" name="keyword">

                        <select name="donation_type" class="form-select fs-14px h-100 w-auto" style="line-height: 1.7"
                            onchange="this.form.submit()">
                            <option value="all" {{ request('donation_type') == 'all' ? 'selected' : '' }}>Semua Tipe
                            </option>
                            <option value="zakat" {{ request('donation_type') == 'zakat' ? 'selected' : '' }}>Zakat
                            </option>
                            <option value="infaq" {{ request('donation_type') == 'infaq' ? 'selected' : '' }}>Infaq
                            </option>
                        </select>

                        <div class="sort-toggle">
                            <input type="radio" name="ordered_by" value="asc" id="ordered_by_asc"
                                {{ request('ordered_by') == 'asc' ? 'checked' : '' }} onchange="this.form.submit()" hidden>
                            <label for="ordered_by_asc" class="btn btn-outline-secondary" title="Terlama">
                                <i class="fas fa-sort-amount-up"></i>
                            </label>

                            <input type="radio" name="ordered_by" value="desc" id="ordered_by_desc"
                                {{ request('ordered_by', 'desc') == 'desc' ? 'checked' : '' }}
                                onchange="this.form.submit()" hidden>
                            <label for="ordered_by_desc" class="btn btn-outline-secondary" title="Terbaru">
                                <i class="fas fa-sort-amount-down"></i>
                            </label>
                        </div>
                    </div>
                </div>


                <div class="table-responsive position-relative mb-3 bg-white p-3" style="min-height: 200px">
                    <table class="table table-sm table-hover fs-14px align-middle">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Donatur</th>
                                <th>Info Transfer</th>
                                <th>Tipe</th>
                                <th>Jumlah</th>
                                <th>Tanggal</th>
                                <th>Status</th>
                                <th class="text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse(($data ?? []) as $index => $row)
                                <tr>
                                    <td>{{ $data instanceof \Illuminate\Pagination\LengthAwarePaginator ? $data->firstItem() + $index : $index + 1 }}
                                    </td>

                                    <td>
                                        <div class="fw-bold">{{ $row->user->name ?? $row->guest_name }}</div>
                                        <small class="text-muted">{{ $row->user->email ?? '-' }}</small>
                                    </td>

                                    <td>
                                        <small class="d-block text-muted">Dari: <span
                                                class="text-dark fw-bold">{{ $row->source_bank }}</span></small>
                                        <small class="d-block text-muted">Ke: <span
                                                class="text-primary fw-bold">{{ $row->destinationAccount->bank_name ?? 'Bank' }}</span></small>
                                    </td>

                                    <td>
                                        @php
                                            $typeParts = explode('_', $row->donation_type);
                                            $category = ucfirst($typeParts[0] ?? 'Lainnya');
                                            $type = ucfirst($typeParts[1] ?? '');
                                        @endphp
                                        <span
                                            class="badge {{ $category === 'Zakat' ? 'bg-success' : 'bg-info' }}">{{ $category }}</span>
                                        @if ($type)
                                            <small class="d-block text-muted">{{ $type }}</small>
                                        @endif
                                    </td>

                                    <td class="fw-bold text-success">
                                        Rp {{ number_format($row->amount, 0, ',', '.') }}
                                    </td>

                                    <td>{{ \Carbon\Carbon::parse($row->transfer_date)->format('d M Y') }}</td>

                                    <td>
                                        @if ($row->status == 'Verified')
                                            <span class="badge bg-success">Diterima</span>
                                        @elseif($row->status == 'Rejected')
                                            <span class="badge bg-danger">Ditolak</span>
                                        @else
                                            <span class="badge bg-warning text-dark">Pending</span>
                                        @endif
                                    </td>

                                    <td class="text-end">
                                        <button type="button" class="btn btn-sm btn-warning" data-bs-toggle="modal"
                                            data-bs-target="#modalDetail{{ $row->confirmation_id }}" title="Cek Bukti">
                                            <i class="fas fa-eye"></i> Cek
                                        </button>
                                    </td>
                                </tr>


                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-5">
                                        <i class="fas fa-inbox fs-1 text-muted d-block mb-2"></i>
                                        <p class="text-muted mt-2 mb-0">Belum ada data konfirmasi donasi.</p>
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
                                <option value="10" {{ request('showing') == 10 ? 'selected' : '' }}>10</option>
                                <option value="20" {{ request('showing') == 20 ? 'selected' : '' }}>20</option>
                                <option value="50" {{ request('showing') == 50 ? 'selected' : '' }}>50</option>
                                <option value="all" {{ request('showing') == 'all' ? 'selected' : '' }}>Semua</option>
                            </select>
                            Data
                        </div>
                    </div>
                    <div class="paginate">
                        @if ($data instanceof \Illuminate\Pagination\LengthAwarePaginator)
                            {{ $data->onEachSide(1)->links() }}
                        @endif
                    </div>
            </form>
        </div>
    </section>

    <!-- Modals (Placed outside table) -->
    @foreach ($data ?? [] as $row)
        <div class="modal fade" id="modalDetail{{ $row->confirmation_id }}" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Detail Konfirmasi Donasi #{{ $row->confirmation_id }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-sm">
                                    <tr>
                                        <td class="text-muted">Donatur</td>
                                        <td class="fw-bold">{{ $row->user->name ?? $row->guest_name }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">Email</td>
                                        <td>{{ $row->user->email ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">Jenis Donasi</td>
                                        <td>{{ ucfirst(str_replace('_', ' ', $row->donation_type)) }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">Jumlah</td>
                                        <td class="fw-bold fs-5 text-success">Rp
                                            {{ number_format($row->amount, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">Tanggal Transfer</td>
                                        <td>{{ \Carbon\Carbon::parse($row->transfer_date)->format('d M Y') }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">Bank Pengirim</td>
                                        <td>{{ $row->source_bank }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">Rekening Tujuan</td>
                                        <td>
                                            {{ $row->destinationAccount->bank_name ?? '-' }}<br>
                                            <small
                                                class="text-muted">{{ $row->destinationAccount->account_number ?? '' }}</small>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">Catatan</td>
                                        <td>{{ $row->notes ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">Status</td>
                                        <td>
                                            @if ($row->status == 'Verified')
                                                <span class="badge bg-success">Diterima</span>
                                            @elseif($row->status == 'Rejected')
                                                <span class="badge bg-danger">Ditolak</span>
                                            @else
                                                <span class="badge bg-warning text-dark">Pending</span>
                                            @endif
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <p class="text-muted mb-2">Bukti Transfer:</p>
                                @if (!empty($row->proof_image_url))
                                    <a href="{{ asset($row->proof_image_url) }}" target="_blank">
                                        <img src="{{ asset($row->proof_image_url) }}" class="img-fluid rounded border"
                                            style="max-height: 400px;">
                                    </a>
                                @else
                                    <div class="text-center py-5 bg-light rounded">
                                        <i class="fas fa-image fs-1 text-muted"></i>
                                        <p class="text-muted mb-0">Tidak ada bukti</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        @if ($row->status == 'Pending')
                            @can('verify_donation')
                                <button type="button" class="btn btn-danger"
                                    onclick="showConfirmModal({
                                        action: '{{ route('admin.donasi.reject', $row->confirmation_id) }}',
                                        method: 'POST',
                                        type: 'reject',
                                        title: 'Tolak Donasi',
                                        message: 'Apakah Anda yakin ingin menolak donasi ini?',
                                        buttonText: 'Ya, Tolak'
                                    })">
                                    <i class="fas fa-times me-1"></i> Tolak
                                </button>
                                <button type="button" class="btn btn-success"
                                    onclick="showConfirmModal({
                                        action: '{{ route('admin.donasi.approve', $row->confirmation_id) }}',
                                        method: 'POST',
                                        type: 'accept',
                                        title: 'Terima Donasi',
                                        message: 'Apakah Anda yakin ingin menerima donasi ini? Saldo akan ditambahkan ke rekening tujuan.',
                                        buttonText: 'Ya, Terima'
                                    })">
                                    <i class="fas fa-check me-1"></i> Terima & Setujui
                                </button>
                            @endcan
                        @else
                            <span class="text-muted">Donasi sudah diproses</span>
                        @endif
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    @push('styles')
        <style>
            .sort-toggle input[type="radio"]:not(:checked)+label {
                display: none;
            }
        </style>
    @endpush
@endsection
