@extends('admin.layout')

@section('title', 'Dashboard Keuangan')

@section('content')
    <section class="p-4">
        {{-- Header --}}
        <div class="mb-4">
            <h4 class="fw-bold mb-1">Dashboard Keuangan</h4>
            <p class="text-muted mb-0 small">Ringkasan kondisi keuangan masjid</p>
        </div>

        {{-- Statistics Cards - Simple Design --}}
        <div class="row g-3 mb-4">
            <div class="col-md-6 col-lg-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <p class="text-muted mb-1 small">Total Saldo</p>
                                <h5 class="fw-bold mb-0 text-primary">Rp {{ number_format($totalSaldo, 0, ',', '.') }}</h5>
                            </div>
                            <div class="rounded-circle bg-primary bg-opacity-10 p-3">
                                <i class="fa-solid fa-wallet text-primary"></i>
                            </div>
                        </div>
                        <small class="text-muted">{{ $totalBanks }} rekening aktif</small>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <p class="text-muted mb-1 small">Pemasukan</p>
                                <h5 class="fw-bold mb-0 text-success">Rp
                                    {{ number_format($pemasukanBulanIni, 0, ',', '.') }}</h5>
                            </div>
                            <div class="rounded-circle bg-success bg-opacity-10 p-3">
                                <i class="fa-solid fa-arrow-up text-success"></i>
                            </div>
                        </div>
                        <small class="text-muted">{{ now()->format('F Y') }}</small>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <p class="text-muted mb-1 small">Pengeluaran</p>
                                <h5 class="fw-bold mb-0 text-danger">Rp
                                    {{ number_format($pengeluaranBulanIni, 0, ',', '.') }}</h5>
                            </div>
                            <div class="rounded-circle bg-danger bg-opacity-10 p-3">
                                <i class="fa-solid fa-arrow-down text-danger"></i>
                            </div>
                        </div>
                        <small class="text-muted">{{ now()->format('F Y') }}</small>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <p class="text-muted mb-1 small">Donasi Pending</p>
                                <h5 class="fw-bold mb-0 text-warning">{{ $donasiPending }}</h5>
                            </div>
                            <div class="rounded-circle bg-warning bg-opacity-10 p-3">
                                <i class="fa-solid fa-clock text-warning"></i>
                            </div>
                        </div>
                        <small class="text-muted">Menunggu verifikasi</small>
                    </div>
                </div>
            </div>
        </div>

        {{-- Content Row --}}
        <div class="row g-3">
            {{-- Bank Accounts --}}
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-0 py-3">
                        <h6 class="mb-0 fw-semibold">Rekening Bank</h6>
                    </div>
                    <div class="card-body p-0">
                        @forelse($banks as $bank)
                            <div class="d-flex justify-content-between align-items-center px-3 py-2 border-bottom">
                                <div>
                                    <p class="mb-0 fw-medium">{{ $bank->bank_name }}</p>
                                    <small class="text-muted">{{ $bank->account_number }}</small>
                                </div>
                                <span class="fw-bold">Rp {{ number_format($bank->balance, 0, ',', '.') }}</span>
                            </div>
                        @empty
                            <p class="text-muted text-center py-4 mb-0">Belum ada rekening</p>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- Recent Transactions --}}
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-0 py-3">
                        <h6 class="mb-0 fw-semibold">Transaksi Terbaru</h6>
                    </div>
                    <div class="card-body p-0">
                        @forelse($recentTransactions as $tx)
                            <div class="d-flex justify-content-between align-items-center px-3 py-2 border-bottom">
                                <div>
                                    <p class="mb-0">{{ Str::limit($tx->description ?? 'Transaksi', 25) }}</p>
                                    <small class="text-muted">{{ $tx->created_at->format('d M Y') }}</small>
                                </div>
                                <span class="fw-bold {{ $tx->type == 'pemasukan' ? 'text-success' : 'text-danger' }}">
                                    {{ $tx->type == 'pemasukan' ? '+' : '-' }}Rp
                                    {{ number_format($tx->amount, 0, ',', '.') }}
                                </span>
                            </div>
                        @empty
                            <p class="text-muted text-center py-4 mb-0">Belum ada transaksi</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
