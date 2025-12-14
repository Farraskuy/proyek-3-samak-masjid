@extends('admin.layout')

@section('title', 'Manajemen Keuangan')

@section('content')
    <section class="p-3">
        {{-- Header --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-semibold mb-0">Manajemen Transaksi Keuangan</h4>
            <div class="d-flex gap-2">
                @if (auth()->user()->hasPermission('manage_expense'))
                    <button class="btn btn-danger fw-semibold" data-bs-toggle="modal" data-bs-target="#modalTambah"
                        onclick="setJenisTransaksi('pengeluaran')">
                        <i class="fas fa-minus me-1"></i> Pengeluaran
                    </button>
                @endif
                @if (auth()->user()->hasPermission('manage_income'))
                    <button class="btn btn-success fw-semibold" data-bs-toggle="modal" data-bs-target="#modalTambah"
                        onclick="setJenisTransaksi('pemasukan')">
                        <i class="fas fa-plus me-1"></i> Pemasukan
                    </button>
                @endif
            </div>
        </div>

        {{-- Quick Filter (Tipe Transaksi) --}}
        <div class="d-flex gap-2 mb-4 p-2 rounded-pill" style="background-color: rgba(0,0,0,0.05); width: fit-content;">
            <a href="{{ route('admin.keuangan', array_merge(request()->query(), ['type' => 'all'])) }}"
                class="btn btn-sm {{ request('type', 'all') == 'all' ? 'btn-dark' : 'btn-light text-secondary' }} rounded-pill px-4 fw-semibold">
                Semua
            </a>
            <a href="{{ route('admin.keuangan', array_merge(request()->query(), ['type' => 'pemasukan'])) }}"
                class="btn btn-sm {{ request('type') == 'pemasukan' ? 'btn-dark' : 'btn-light text-secondary' }} rounded-pill px-4 fw-semibold">
                Pemasukan
            </a>
            <a href="{{ route('admin.keuangan', array_merge(request()->query(), ['type' => 'pengeluaran'])) }}"
                class="btn btn-sm {{ request('type') == 'pengeluaran' ? 'btn-dark' : 'btn-light text-secondary' }} rounded-pill px-4 fw-semibold">
                Pengeluaran
            </a>
        </div>

        <div class="row g-0 gap-3">
            <form method="get" action="{{ route('admin.keuangan') }}" class="col rounded-3 bg-white p-3 pt-0 form-filter"
                style="height: fit-content">
                {{-- Persist other filters --}}
                @if (request('type'))
                    <input type="hidden" name="type" value="{{ request('type') }}">
                @endif

                {{-- Toolbar --}}
                <div class="bg-white position-sticky pt-3 pb-2" style="top: 61px; z-index: 10">
                    <div class="d-flex gap-2 justify-content-end mb-2 align-items-center">
                        {{-- Filter Bank (Moved from Header) --}}
                        <select name="bank" class="form-select fs-14px h-100 w-auto" style="line-height: 1.7"
                            onchange="this.form.submit()">
                            <option value="global" {{ $selectedBank == 'global' ? 'selected' : '' }}>Semua Bank (Global)
                            </option>
                            @foreach ($banks as $bank)
                                <option value="{{ $bank->bank_name }}"
                                    {{ $selectedBank == $bank->bank_name ? 'selected' : '' }}>
                                    {{ $bank->bank_name }}
                                </option>
                            @endforeach
                        </select>

                        <input type="text" class="form-control" placeholder="Cari Transaksi..."
                            value="{{ request('keyword') }}" name="keyword">

                        {{-- Sort Toggle --}}
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

                {{-- Table --}}
                <div class="table-responsive position-relative mb-3" style="min-height: 200px">
                    <table class="table table-sm table-hover fs-14px align-middle">
                        <thead>
                            <tr>
                                <th class="p-3">Tanggal</th>
                                <th class="p-3">Bank / Kas</th>
                                <th class="p-3">Kategori</th>
                                <th class="p-3">Keterangan</th>
                                <th class="p-3">Jenis</th>
                                <th class="p-3">Nominal</th>
                                <th class="p-3 text-center">Gambar</th>
                                <th class="p-3 text-center" style="width: 100px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($transactions as $item)
                                <tr>
                                    <td class="p-3 align-middle" style="white-space: nowrap;">
                                        {{ \Carbon\Carbon::parse($item->transaction_date)->format('d M Y') }}
                                    </td>
                                    <td class="p-3 align-middle">{{ $item->bank_name }}</td>
                                    <td class="p-3 align-middle">{{ $item->category }}</td>
                                    <td class="p-3 align-middle text-muted small">
                                        {{ Str::limit($item->description, 35) }}
                                    </td>
                                    <td class="p-3 align-middle">
                                        @if ($item->type == 'pemasukan')
                                            <span class="badge rounded-pill text-bg-success">Pemasukan</span>
                                        @else
                                            <span class="badge rounded-pill text-bg-danger">Pengeluaran</span>
                                        @endif
                                    </td>
                                    <td class="p-3 align-middle fw-bold {{ $item->type == 'pemasukan' ? 'text-success' : 'text-danger' }}"
                                        style="white-space: nowrap;">
                                        {{ $item->type == 'pemasukan' ? '+' : '-' }} Rp
                                        {{ number_format($item->amount, 0, ',', '.') }}
                                    </td>

                                    {{-- Kolom Gambar --}}
                                    <td class="p-3 align-middle text-center">
                                        @if ($item->proof_image_url)
                                            <button type="button" class="btn btn-sm btn-outline-secondary"
                                                data-bs-toggle="modal" data-bs-target="#modalBukti{{ $item->id }}">
                                                <i class="fas fa-eye"></i> Lihat
                                            </button>

                                            {{-- Modal Preview --}}
                                            <div class="modal fade" id="modalBukti{{ $item->id }}" tabindex="-1"
                                                aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered modal-lg">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title fs-6">Bukti - {{ $item->category }}</h5>
                                                            <button type="button" class="btn-close"
                                                                data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <div class="modal-body text-center bg-light p-4">
                                                            @if (Str::endsWith(strtolower($item->proof_image_url), '.pdf'))
                                                                <i class="fas fa-file-pdf text-danger"
                                                                    style="font-size: 5rem;"></i>
                                                                <p class="mt-3 fw-bold">File Dokumen PDF</p>
                                                                <a href="{{ asset('storage/' . $item->proof_image_url) }}"
                                                                    target="_blank" class="btn btn-primary mt-2">
                                                                    <i class="fas fa-download"></i> Download PDF
                                                                </a>
                                                            @else
                                                                <img src="{{ asset('storage/' . $item->proof_image_url) }}"
                                                                    class="img-fluid rounded shadow-sm"
                                                                    style="max-height: 400px;" alt="Bukti">
                                                            @endif
                                                        </div>
                                                        @if (!Str::endsWith(strtolower($item->proof_image_url), '.pdf'))
                                                            <div class="modal-footer p-1">
                                                                <a href="{{ asset('storage/' . $item->proof_image_url) }}"
                                                                    target="_blank" class="btn btn-sm btn-primary">
                                                                    <i class="fas fa-expand"></i> Buka Full Size
                                                                </a>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            <span class="text-muted small">-</span>
                                        @endif
                                    </td>

                                    <td class="p-3 align-middle text-center">
                                        @can('delete_finance')
                                            <button type="button" class="btn btn-sm btn-light border text-danger"
                                                title="Hapus"
                                                onclick="showConfirmModal({
                                                    action: '{{ route('admin.keuangan.destroy', $item->id) }}',
                                                    method: 'DELETE',
                                                    type: 'delete',
                                                    title: 'Hapus Transaksi',
                                                    message: 'Apakah Anda yakin ingin menghapus data transaksi ini?',
                                                    buttonText: 'Ya, Hapus'
                                                })">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        @endcan
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center text-muted py-4">Belum ada data transaksi.</td>
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
                        {{ $transactions->links() }}
                    </div>
                </div>
            </form>
        </div>

        {{-- Hidden Delete Forms --}}
        @foreach ($transactions as $item)
            @can('delete_finance')
                <form id="delete-form-{{ $item->id }}" action="{{ route('admin.keuangan.destroy', $item->id) }}"
                    method="POST" class="d-none">
                    @csrf @method('DELETE')
                </form>
            @endcan
        @endforeach

    </section>

    {{-- Modal Tambah Transaksi --}}
    <div class="modal fade" id="modalTambah" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Catat Transaksi Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('admin.keuangan.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">

                        <div class="mb-3">
                            <select name="bank_name" class="form-select" required>
                                <option value="" disabled selected>-- Pilih Bank --</option>
                                @foreach ($banks as $bank)
                                    <option value="{{ $bank->bank_name }}">
                                        {{ $bank->bank_name }} (Rp
                                        {{ number_format($bank->saldo_saat_ini, 0, ',', '.') }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Jenis Transaksi</label>
                            <select name="type" class="form-select" required>
                                @if (auth()->user()->hasPermission('manage_expense'))
                                    <option value="pengeluaran">Pengeluaran (Uang Keluar)</option>
                                @endif
                                @if (auth()->user()->hasPermission('manage_income'))
                                    <option value="pemasukan">Pemasukan (Uang Masuk)</option>
                                @endif
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tanggal</label>
                            <input type="date" name="transaction_date" class="form-control"
                                value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Nominal (Rp)</label>
                            <input type="number" name="amount" class="form-control" placeholder="Contoh: 500000"
                                required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Kategori</label>
                            <input type="text" name="category" class="form-control"
                                placeholder="Contoh: Infaq Jumat / Bayar Listrik" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Keterangan </label>
                            <textarea name="description" class="form-control" rows="2" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Upload Bukti Transaksi</label>
                            <div class="upload-zone">
                                <input type="file" name="proof_file" accept="image/png, image/jpeg, application/pdf"
                                    required onchange="previewFile(this)">
                                <div class="upload-content">
                                    <i class="fas fa-upload upload-icon"></i>
                                    <p class="mb-0 text-muted small" id="upload-text">Klik untuk upload JPG, PNG, atau PDF
                                    </p>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@push('styles')
    <style>
        .sort-toggle input[type="radio"]:not(:checked)+label {
            display: none;
        }
    </style>
@endpush
