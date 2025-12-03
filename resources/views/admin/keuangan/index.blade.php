@extends('admin.layout')

@section('title', 'Manajemen Keuangan')

@section('content')
    <div class="container-fluid p-4">

        {{-- Header --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fw-semibold mb-1">Laporan Keuangan Masjid</h4>
                <small class="text-muted">Pantau arus kas per akun bank atau secara global.</small>
            </div>

            <div class="d-flex gap-2">

                <form action="{{ route('admin.keuangan') }}" method="GET">
                    <select name="bank" class="form-select border-primary text-primary fw-bold"
                        onchange="this.form.submit()" style="width: 180px;">

                        <option value="global" {{ $selectedBank == 'global' ? 'selected' : '' }}>Semua (Global)</option>

                        @foreach ($banks as $bank)
                            <option value="{{ $bank->bank_name }}"
                                {{ $selectedBank == $bank->bank_name ? 'selected' : '' }}>
                                {{ $bank->bank_name }}
                            </option>
                        @endforeach
                    </select>
                </form>

                @if (auth()->user()->hasPermission('manage_expense'))
                    <button class="btn btn-danger fw-semibold" data-bs-toggle="modal" data-bs-target="#modalTambah"
                        onclick="setJenisTransaksi('pengeluaran')">
                        <i class="fa-solid fa-minus me-1"></i> Pengeluaran
                    </button>
                @endif
                @if (auth()->user()->hasPermission('manage_income'))
                    <button class="btn btn-success fw-semibold" data-bs-toggle="modal" data-bs-target="#modalTambah"
                        onclick="setJenisTransaksi('pemasukan')">
                        <i class="fa-solid fa-plus me-1"></i> Pemasukan
                    </button>
                @endif
            </div>
        </div>

        {{-- Ringkasan Saldo Cards --}}
        <div class="row g-4 mb-4">

            {{-- Card Pemasukan --}}
            <div class="col-md-4">
                <div class="card border-0 shadow-sm p-3 border-start border-5 border-success">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted mb-1">Total Pemasukan</p>
                            {{-- Badge Nama Bank --}}
                            <span
                                class="badge {{ $selectedBank == 'global' ? 'bg-light text-secondary border' : 'bg-success-subtle text-success border border-success' }} mb-2">
                                <i class="fa-solid fa-wallet me-1"></i>
                                {{ $selectedBank == 'global' ? 'Semua Kas' : $selectedBank }}
                            </span>
                        </div>
                        <div class="bg-success-subtle text-success p-2 rounded">
                            <i class="fa-solid fa-arrow-trend-up"></i>
                        </div>
                    </div>
                    <h4 class="fw-bold text-success mt-2">Rp {{ number_format($totalPemasukan, 0, ',', '.') }}</h4>
                </div>
            </div>

            {{-- Card Pengeluaran --}}
            <div class="col-md-4">
                <div class="card border-0 shadow-sm p-3 border-start border-5 border-danger">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted mb-1">Total Pengeluaran</p>
                            {{-- Badge Nama Bank --}}
                            <span
                                class="badge {{ $selectedBank == 'global' ? 'bg-light text-secondary border' : 'bg-danger-subtle text-danger border border-danger' }} mb-2">
                                <i class="fa-solid fa-wallet me-1"></i>
                                {{ $selectedBank == 'global' ? 'Semua Kas' : $selectedBank }}
                            </span>
                        </div>
                        <div class="bg-danger-subtle text-danger p-2 rounded">
                            <i class="fa-solid fa-arrow-trend-down"></i>
                        </div>
                    </div>
                    <h4 class="fw-bold text-danger mt-2">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</h4>
                </div>
            </div>

            {{-- Card Saldo Akhir --}}
            <div class="col-md-4">
                <div class="card border-0 shadow-sm p-3 border-start border-5 border-primary"
                    style="background-color: #e3f2fd;">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted mb-1">Saldo Akhir</p>
                            {{-- Badge Nama Bank --}}
                            <span
                                class="badge {{ $selectedBank == 'global' ? 'bg-light text-secondary border' : 'bg-primary text-white' }} mb-2">
                                <i class="fa-solid fa-vault me-1"></i>
                                {{ $selectedBank == 'global' ? 'Semua Kas' : $selectedBank }}
                            </span>
                        </div>
                        <div class="bg-white text-primary p-2 rounded shadow-sm">
                            <i class="fa-solid fa-coins"></i>
                        </div>
                    </div>
                    <h4 class="fw-bold text-primary mt-2">Rp {{ number_format($saldoAkhir, 0, ',', '.') }}</h4>
                </div>
            </div>
        </div>

        {{-- Grafik Chart --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white py-3">
                <h6 class="mb-0 fw-bold">Grafik Arus Kas (Tahun Ini)</h6>
            </div>
            <div class="card-body">
                <canvas id="financeChart" style="max-height: 350px;"></canvas>
            </div>
        </div>

        {{-- Tabel Riwayat --}}
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3">
                <h6 class="mb-0 fw-bold">Riwayat Transaksi</h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive" style="min-height: 200px">
                    <table class="table table-sm table-hover fs-14px m-0">
                        <thead class="bg-light">
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
                                                <i class="fa-regular fa-eye"></i> Lihat
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
                                                                <i class="fa-solid fa-file-pdf text-danger"
                                                                    style="font-size: 5rem;"></i>
                                                                <p class="mt-3 fw-bold">File Dokumen PDF</p>
                                                                <a href="{{ asset('storage/' . $item->proof_image_url) }}"
                                                                    target="_blank" class="btn btn-primary mt-2">
                                                                    <i class="fa-solid fa-download"></i> Download PDF
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
                                                                    <i class="fa-solid fa-expand"></i> Buka Full Size
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
                                            <form action="{{ route('admin.keuangan.destroy', $item->id) }}" method="POST"
                                                onsubmit="return confirm('Hapus data ini?');">
                                                @csrf @method('DELETE')
                                                <button class="btn btn-sm btn-light border text-danger" title="Hapus">
                                                    <i class="fa-solid fa-trash"></i>
                                                </button>
                                            </form>
                                        @endcan
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted py-4">Belum ada data transaksi.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="p-3">
                    {{ $transactions->links() }}
                </div>
            </div>
        </div>
    </div>

    </div>

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
                                    <i class="bi bi-upload upload-icon"></i>
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

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        const ctx = document.getElementById('financeChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: @json($chartLabels),
                datasets: [{
                        label: 'Pemasukan',
                        data: @json($chartIncome),
                        backgroundColor: '#198754',
                        borderRadius: 5
                    },
                    {
                        label: 'Pengeluaran',
                        data: @json($chartExpense),
                        backgroundColor: '#dc3545',
                        borderRadius: 5
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
@endpush
