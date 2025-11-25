@extends('client.layout')

@section('title', 'Laporan Keuangan Masjid - Transparansi Umat')

@push('styles')
    <style>
        * {
            font-family: 'Poppins', "Lexend", Geneva, Verdana, sans-serif;
        }

        .bg-pattern {
            background-image: radial-gradient(circle at 1px 1px, rgba(255, 255, 255, 0.15) 1px, transparent 1px);
            background-size: 25px 25px;
        }

        [data-aos] {
            transition-property: transform, opacity !important;
        }

        .card-stat {
            border: none;
            border-radius: 20px;
            transition: .25s ease-in-out;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            background: white;
        }

        .card-stat:hover {
            transform: translateY(-6px);
            box-shadow: 0 1.2rem 3rem rgba(0, 0, 0, .10) !important;
        }

        .icon-box {
            width: 60px;
            height: 60px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            margin-bottom: 1.5rem;
        }
    </style>
@endpush

@section('content')

    <section class="py-5 bg-pattern" style="background-color: #175C9E; height: 320px; display: flex; align-items: center;">
        <div class="container text-center">
            <h1 class="display-5 fw-bold text-white mb-3" data-aos="fade-up" data-aos-duration="900">
                Laporan <span class="text-warning">Keuangan</span> Masjid
            </h1>

            <p class="lead text-white-50 mb-0 col-lg-8 mx-auto" data-aos="fade-up" data-aos-duration="1100"
                data-aos-delay="200">
                Transparansi pengelolaan dana Infaq, Shadaqah, dan Operasional demi kemaslahatan umat dan kemakmuran masjid.
            </p>
        </div>
    </section>

    <div class="container py-5" style="margin-top: -50px; position: relative; z-index: 2;">

        <div class="row g-4 mb-5">
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="100" data-aos-duration="800">
                <div class="card card-stat h-100 p-4">
                    <div class="d-flex flex-column h-100">
                        <div class="icon-box bg-primary bg-opacity-10 text-primary">
                            <i class="fa-solid fa-wallet"></i>
                        </div>
                        <h6 class="text-muted mb-1 fw-semibold">Total Saldo Kas</h6>
                        <h3 class="fw-bold text-primary mb-0">Rp {{ number_format($saldoAkhir, 0, ',', '.') }}</h3>
                        <small class="text-muted mt-2">Dana tersedia saat ini</small>
                    </div>
                </div>
            </div>

            <div class="col-md-4" data-aos="fade-up" data-aos-delay="200" data-aos-duration="800">
                <div class="card card-stat h-100 p-4">
                    <div class="d-flex flex-column h-100">
                        <div class="icon-box bg-success bg-opacity-10 text-success">
                            <i class="fa-solid fa-arrow-trend-up"></i>
                        </div>
                        <h6 class="text-muted mb-1 fw-semibold">Total Pemasukan</h6>
                        <h3 class="fw-bold text-success mb-0">Rp {{ number_format($totalPemasukan, 0, ',', '.') }}</h3>
                        <small class="text-muted mt-2">Akumulasi dana masuk</small>
                    </div>
                </div>
            </div>

            <div class="col-md-4" data-aos="fade-up" data-aos-delay="300" data-aos-duration="800">
                <div class="card card-stat h-100 p-4">
                    <div class="d-flex flex-column h-100">
                        <div class="icon-box bg-danger bg-opacity-10 text-danger">
                            <i class="fa-solid fa-arrow-trend-down"></i>
                        </div>
                        <h6 class="text-muted mb-1 fw-semibold">Total Pengeluaran</h6>
                        <h3 class="fw-bold text-danger mb-0">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</h3>
                        <small class="text-muted mt-2">Operasional & kegiatan</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">

            <div class="col-lg-12 mb-4" data-aos="fade-up" data-aos-duration="1000">
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-header bg-white py-3 border-0">
                        <h5 class="fw-bold mb-0"><i class="fa-solid fa-chart-column me-2 text-warning"></i> Grafik Arus Kas
                            (Tahun Ini)</h5>
                    </div>
                    <div class="card-body">
                        <div style="height: 350px;">
                            <canvas id="financeChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-12" data-aos="fade-up" data-aos-duration="1200">
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center">
                        <h5 class="fw-bold mb-0"><i class="fa-solid fa-clock-rotate-left me-2 text-info"></i> Riwayat
                            Transaksi Terbaru</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-4">Tanggal</th>
                                        <th>Kategori</th>
                                        <th>Keterangan</th>
                                        <th>Jenis</th>
                                        <th class="text-end pe-4">Nominal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($transactions as $item)
                                        <tr>
                                            <td class="ps-4 fw-medium">
                                                {{ \Carbon\Carbon::parse($item->transaction_date)->translatedFormat('d M Y') }}
                                            </td>
                                            <td>
                                                <span
                                                    class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-10 rounded-pill px-3">
                                                    {{ $item->category }}
                                                </span>
                                            </td>
                                            <td class="text-muted small">{{ $item->description ?? '-' }}</td>
                                            <td>
                                                @if ($item->type == 'pemasukan')
                                                    <span class="text-success fw-bold small"><i
                                                            class="fa-solid fa-arrow-down me-1"></i> Masuk</span>
                                                @else
                                                    <span class="text-danger fw-bold small"><i
                                                            class="fa-solid fa-arrow-up me-1"></i> Keluar</span>
                                                @endif
                                            </td>
                                            <td
                                                class="text-end pe-4 fw-bold {{ $item->type == 'pemasukan' ? 'text-success' : 'text-danger' }}">
                                                {{ $item->type == 'pemasukan' ? '+' : '-' }} Rp
                                                {{ number_format($item->amount, 0, ',', '.') }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center text-muted py-5">
                                                <img src="{{ asset('assets/images/no-data.png') }}" alt="Kosong"
                                                    style="max-width: 100px; opacity: 0.6;">
                                                <p class="mt-2">Belum ada data transaksi.</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer bg-white border-0 py-3">
                        {{ $transactions->links() }}
                    </div>
                </div>
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
                        borderRadius: 8,
                        barPercentage: 0.6,
                    },
                    {
                        label: 'Pengeluaran',
                        data: @json($chartExpense),
                        backgroundColor: '#dc3545',
                        borderRadius: 8,
                        barPercentage: 0.6,
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
                        beginAtZero: true,
                        grid: {
                            color: '#f8f9fa'
                        },
                        border: {
                            dash: [5, 5]
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    </script>
@endpush
