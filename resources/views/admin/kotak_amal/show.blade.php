@extends('admin.layout')

@section('title', 'Laporan Kotak Amal')

@section('content')
    <section class="p-3">
        <div class="d-flex justify-content-between align-items-center mb-4 d-print-none">
            <div class="d-flex align-items-center gap-2">
                <a href="{{ route('admin.kotak-amal.index') }}" class="btn btn-light btn-sm rounded-4">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <h4 class="fw-semibold mb-0">Laporan Kotak Amal</h4>
            </div>
            <button onclick="window.print()" class="btn btn-primary">
                <i class="fas fa-print me-1"></i> Cetak Laporan
            </button>
        </div>

        <div class="card border-0 shadow-sm rounded-0" id="print-area">
            <div class="card-body p-5">

                {{-- Header Report --}}
                <div class="text-center mb-5 border-bottom pb-4">
                    <img src="{{ asset('assets/images/logo.png') }}" alt="Logo" width="80" class="mb-3">
                    <h3 class="fw-bold text-uppercase">Berita Acara Pembukaan Kotak Amal</h3>
                    <h5 class="fw-normal">{{ config('app.name', 'Masjid Kampus') }}</h5>
                </div>

                {{-- Info --}}
                <div class="row mb-4">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td width="150" class="fw-bold">Identitas Kotak</td>
                                <td>: {{ $collection->box_name }}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Tanggal</td>
                                <td>: {{ $collection->collection_date->translatedFormat('l, d F Y') }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <div class="p-3 bg-light rounded border d-inline-block text-start" style="min-width: 200px;">
                            <small class="text-muted d-block">TOTAL PEROLEHAN</small>
                            <h3 class="fw-bold text-success mb-0">Rp
                                {{ number_format($collection->total_amount, 0, ',', '.') }}</h3>
                        </div>
                    </div>
                </div>

                {{-- Money Details --}}
                <h5 class="fw-bold border-bottom pb-2 mb-3">Rincian Perhitungan</h5>
                <div class="table-responsive mb-5">
                    <table class="table table-bordered table-sm align-middle">
                        <thead class="table-light text-center">
                            <tr>
                                <th>Pecahan</th>
                                <th>Jumlah</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($collection->details as $detail)
                                <tr>
                                    <td class="text-end">Rp {{ number_format($detail['nominal'], 0, ',', '.') }}</td>
                                    <td class="text-center">{{ $detail['quantity'] }}</td>
                                    <td class="text-end fw-bold">Rp {{ number_format($detail['subtotal'], 0, ',', '.') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="table-light fw-bold">
                            <tr>
                                <td colspan="2" class="text-end">TOTAL</td>
                                <td class="text-end">Rp {{ number_format($collection->total_amount, 0, ',', '.') }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                {{-- Signatures --}}
                <h5 class="fw-bold border-bottom pb-2 mb-4">Petugas / Saksi</h5>
                <div class="row g-4">
                    @foreach ($collection->officers as $officer)
                        <div class="col-4 text-center">
                            <div class="border rounded p-3 h-100 d-flex flex-column justify-content-between"
                                style="min-height: 150px;">
                                <div class="mb-3">
                                    <small class="text-muted d-block mb-2">Tanda Tangan</small>
                                    @if ($officer['signature'])
                                        <div class="fst-italic text-muted"
                                            style="font-family: 'Courier New', Courier, monospace;">
                                            "{{ $officer['signature'] }}"
                                        </div>
                                    @else
                                        <div class="text-muted opacity-25">Belum ada TTD</div>
                                    @endif
                                </div>
                                <div>
                                    <div class="fw-bold border-top pt-2 d-inline-block px-3">{{ $officer['name'] }}</div>
                                    <div class="small text-muted">{{ $officer['phone'] ?? '-' }}</div>
                                </div>
                            </div>
                        </div>
                    @endforeach
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
