@extends('admin.layout')

@section('title', 'Manajemen Keuangan')

@section('content')
    <div class="container-fluid p-4">

        {{-- Header --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-semibold mb-0">Laporan Keuangan Masjid</h4>
            @can('create_finance')
                <button class="btn btn-success fw-semibold" data-bs-toggle="modal" data-bs-target="#modalTambah">
                    <i class="fa-solid fa-plus me-1"></i> Catat Transaksi
                </button>
            @endcan
        </div>

        {{-- Ringkasan Saldo Cards --}}
        <div class="row g-4 mb-4">
            <div class="col-md-4">
                <div class="card border-0 shadow-sm p-3 border-start border-5 border-success">
                    <p class="text-muted mb-1">Total Pemasukan</p>
                    <h4 class="fw-bold text-success">Rp {{ number_format($totalPemasukan, 0, ',', '.') }}</h4>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm p-3 border-start border-5 border-danger">
                    <p class="text-muted mb-1">Total Pengeluaran</p>
                    <h4 class="fw-bold text-danger">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</h4>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm p-3 border-start border-5 border-primary"
                    style="background-color: #e3f2fd;">
                    <p class="text-muted mb-1">Saldo Akhir</p>
                    <h4 class="fw-bold text-primary">Rp {{ number_format($saldoAkhir, 0, ',', '.') }}</h4>
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
                                <th class="p-3">Kategori</th>
                                <th class="p-3">Keterangan</th>
                                <th class="p-3">Jenis</th>
                                <th class="p-3">Nominal</th>
                                <th class="p-3 text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($transactions as $item)
                                <tr>
                                    <td class="p-3 align-middle">
                                        {{ \Carbon\Carbon::parse($item->transaction_date)->format('d M Y') }}</td>
                                    <td class="p-3 align-middle">{{ $item->category }}</td>
                                    <td class="p-3 align-middle text-muted small">{{ Str::limit($item->description, 40) }}
                                    </td>
                                    <td class="p-3 align-middle">
                                        @if ($item->type == 'pemasukan')
                                            <span class="badge rounded-pill text-bg-success">Pemasukan</span>
                                        @else
                                            <span class="badge rounded-pill text-bg-danger">Pengeluaran</span>
                                        @endif
                                    </td>
                                    <td
                                        class="p-3 align-middle fw-bold {{ $item->type == 'pemasukan' ? 'text-success' : 'text-danger' }}">
                                        {{ $item->type == 'pemasukan' ? '+' : '-' }} Rp
                                        {{ number_format($item->amount, 0, ',', '.') }}
                                    </td>
                                    <td class="p-3 align-middle text-end">
                                        @can('delete_finance')
                                            <form action="{{ route('admin.keuangan.destroy', $item->id) }}" method="POST"
                                                onsubmit="return confirm('Hapus data ini?');">
                                                @csrf @method('DELETE')
                                                <button class="btn btn-sm btn-light border text-danger"><i
                                                        class="fa-solid fa-trash"></i></button>
                                            </form>
                                        @endcan
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">Belum ada data transaksi.</td>
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
                <form action="{{ route('admin.keuangan.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Jenis Transaksi</label>
                            <select name="type" class="form-select" required>
                                <option value="pemasukan">Pemasukan (Uang Masuk)</option>
                                <option value="pengeluaran">Pengeluaran (Uang Keluar)</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tanggal</label>
                            <input type="date" name="transaction_date" class="form-control" value="{{ date('Y-m-d') }}"
                                required>
                        </div>
                        <div class="mb-3">
                            <div class="d-flex gap-2 justify-content-end mb-2">
                                <input type="text" class="form-control" placeholder="Cari"
                                    value="{{ request()->query('keyword', '') }}" name="keyword">

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
                            <label class="form-label">Keterangan (Opsional)</label>
                            <textarea name="description" class="form-control" rows="2"></textarea>
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
