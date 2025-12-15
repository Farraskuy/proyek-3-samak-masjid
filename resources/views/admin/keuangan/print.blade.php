@extends('admin.layout')

@section('title', 'Nota Transaksi')

@section('content')
    <section class="p-3">
        <div class="d-flex justify-content-between align-items-center mb-4 d-print-none">
            <div class="d-flex align-items-center gap-2">
                <a href="{{ route('admin.keuangan') }}" class="btn btn-light btn-sm rounded-4">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <h4 class="fw-semibold mb-0">Nota Transaksi</h4>
            </div>
            <button onclick="window.print()" class="btn btn-primary">
                <i class="fas fa-print me-1"></i> Cetak
            </button>
        </div>

        <div class="card border-0 shadow-sm rounded-0" id="print-area">
            <div class="card-body p-5">
                {{-- Header --}}
                <div class="text-center mb-5 border-bottom pb-4">
                    <img src="{{ asset('assets/images/logo.png') }}" alt="Logo" width="80" class="mb-3">
                    <h3 class="fw-bold text-uppercase">Nota Transaksi Keuangan</h3>
                    <h5 class="fw-normal">{{ config('app.name', 'Masjid Kampus') }}</h5>
                </div>

                {{-- Transaction Details --}}
                <div class="row mb-5">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td width="150" class="fw-bold">No. Transaksi</td>
                                <td>: #{{ str_pad($transaction->id, 6, '0', STR_PAD_LEFT) }}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Tanggal</td>
                                <td>: {{ $transaction->transaction_date->translatedFormat('l, d F Y') }}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Jenis</td>
                                <td>:
                                    <span
                                        class="badge {{ $transaction->type == 'pemasukan' ? 'bg-success' : 'bg-danger' }}">
                                        {{ ucfirst($transaction->type) }}
                                    </span>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <div class="p-3 {{ $transaction->type == 'pemasukan' ? 'bg-success-subtle' : 'bg-danger-subtle' }} rounded border d-inline-block text-start"
                            style="min-width: 200px;">
                            <small class="text-muted d-block">NOMINAL</small>
                            <h3
                                class="fw-bold {{ $transaction->type == 'pemasukan' ? 'text-success' : 'text-danger' }} mb-0">
                                {{ $transaction->type == 'pemasukan' ? '+' : '-' }} Rp
                                {{ number_format($transaction->amount, 0, ',', '.') }}
                            </h3>
                        </div>
                    </div>
                </div>

                {{-- Details Table --}}
                <div class="table-responsive mb-4">
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <td width="200" class="bg-light fw-bold">Bank / Rekening</td>
                                <td>{{ $transaction->bank_name }}</td>
                            </tr>
                            <tr>
                                <td class="bg-light fw-bold">Kategori</td>
                                <td>{{ $transaction->category }}</td>
                            </tr>
                            <tr>
                                <td class="bg-light fw-bold">Keterangan</td>
                                <td>{{ $transaction->description ?: '-' }}</td>
                            </tr>
                            <tr>
                                <td class="bg-light fw-bold">Dicatat Oleh</td>
                                <td>{{ $transaction->user?->full_name ?? 'Sistem' }}</td>
                            </tr>
                            <tr>
                                <td class="bg-light fw-bold">Waktu Input</td>
                                <td>{{ $transaction->created_at->translatedFormat('d F Y, H:i') }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                {{-- Proof Image --}}
                @if ($transaction->proof_image_url)
                    <div class="mb-4">
                        <h6 class="fw-bold mb-2">Bukti Transaksi</h6>
                        <img src="{{ Storage::url($transaction->proof_image_url) }}" alt="Bukti"
                            class="img-fluid rounded border" style="max-height: 200px;">
                    </div>
                @endif

                {{-- Footer --}}
                <div class="row mt-5">
                    <div class="col-6">
                        <div class="text-center">
                            <p class="mb-5">Mengetahui,</p>
                            <p class="fw-bold mb-0 border-top d-inline-block pt-2 px-3">Bendahara</p>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="text-center">
                            <p class="mb-5">Dibuat Oleh,</p>
                            <p class="fw-bold mb-0 border-top d-inline-block pt-2 px-3">
                                {{ $transaction->user?->full_name ?? 'Sistem' }}</p>
                        </div>
                    </div>
                </div>

                <div class="mt-5 text-center text-muted small d-none d-print-block">
                    Dicetak pada: {{ now()->translatedFormat('d F Y H:i') }}
                </div>
            </div>
        </div>
    </section>

    <style>
        @media print {
            body * {
                visibility: hidden;
            }

            #print-area,
            #print-area * {
                visibility: visible;
            }

            #print-area {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
                border: none !important;
                box-shadow: none !important;
            }

            .d-print-none {
                display: none !important;
            }

            .d-print-block {
                display: block !important;
            }
        }
    </style>
@endsection
