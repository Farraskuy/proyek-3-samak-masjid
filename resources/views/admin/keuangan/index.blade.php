@extends('admin.layout')

@section('title', 'Manajemen Keuangan')

@section('content')
<div class="container-fluid p-4">
    
    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold text-dark">Laporan Keuangan Masjid</h3>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambah">
            <i class="fa-solid fa-plus me-2"></i> Catat Transaksi
        </button>
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
            <div class="card border-0 shadow-sm p-3 border-start border-5 border-primary" style="background-color: #e3f2fd;">
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
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Tanggal</th>
                            <th>Kategori</th>
                            <th>Keterangan</th>
                            <th>Jenis</th>
                            <th>Nominal</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transactions as $item)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($item->transaction_date)->format('d M Y') }}</td>
                            <td>{{ $item->category }}</td>
                            <td class="text-muted small">{{ Str::limit($item->description, 40) }}</td>
                            <td>
                                @if($item->type == 'pemasukan')
                                    <span class="badge bg-success bg-opacity-10 text-success">Pemasukan</span>
                                @else
                                    <span class="badge bg-danger bg-opacity-10 text-danger">Pengeluaran</span>
                                @endif
                            </td>
                            <td class="fw-bold {{ $item->type == 'pemasukan' ? 'text-success' : 'text-danger' }}">
                                {{ $item->type == 'pemasukan' ? '+' : '-' }} Rp {{ number_format($item->amount, 0, ',', '.') }}
                            </td>
                            <td class="text-end">
                                <form action="{{ route('admin.keuangan.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus data ini?');">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-light text-danger"><i class="fa-solid fa-trash"></i></button>
                                </form>
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
            <div class="mt-3">
                {{ $transactions->links() }}
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
                        <input type="date" name="transaction_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nominal (Rp)</label>
                        <input type="number" name="amount" class="form-control" placeholder="Contoh: 500000" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Kategori</label>
                        <input type="text" name="category" class="form-control" placeholder="Contoh: Infaq Jumat / Bayar Listrik" required>
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
            datasets: [
                {
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
                legend: { position: 'top' }
            },
            scales: {
                y: { beginAtZero: true }
            }
        }
    });
</script>
@endpush