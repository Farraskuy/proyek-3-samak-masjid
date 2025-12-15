@extends('client.layout')

@section('title', 'Donasi Berhasil')

@section('content')
    <style>
        .zis-container {
            background-color: #f9fbfd;
            min-height: 80vh;
            padding: 80px 0;
            font-size: 1.1rem;
        }

        .success-card {
            border: none;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            border-radius: 20px;
            overflow: hidden;
            background: white;
        }

        .success-header {
            background-color: #175C9E;
            padding: 50px 0;
        }

        .btn-primary-custom {
            background-color: #175C9E;
            color: white;
            border-radius: 50px;
            font-weight: 600;
            padding: 12px 30px;
            border: none;
            transition: all 0.3s;
        }

        .btn-primary-custom:hover {
            background-color: #124a80;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(23, 92, 158, 0.3);
        }

        .btn-outline-custom {
            border: 2px solid #175C9E;
            color: #175C9E;
            border-radius: 50px;
            font-weight: 600;
            padding: 12px 30px;
            background: transparent;
            transition: all 0.3s;
        }

        .btn-outline-custom:hover {
            background-color: #175C9E;
            color: white;
        }
    </style>

    <section class="zis-container">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6 col-md-8">
                    <div class="success-card text-center">
                        <!-- Success Icon -->
                        <div class="success-header">
                            <div class="d-inline-flex justify-content-center align-items-center bg-white rounded-circle mb-3 shadow-sm"
                                style="width: 100px; height: 100px;">
                                <i class="fas fa-check" style="font-size: 50px; color: #175C9E;"></i>
                            </div>
                            <h2 class="text-white fw-bold mb-0">Konfirmasi Terkirim!</h2>
                        </div>

                        <div class="card-body px-4 py-5">
                            <p class="lead text-muted mb-4">
                                Terima kasih atas donasi Anda. Tim kami akan segera memverifikasi pembayaran Anda.
                            </p>

                            @if (isset($successData))
                                <div class="bg-light rounded-3 p-4 mb-4 text-start">
                                    <h5 class="text-muted mb-3 fw-bold" style="color: #175C9E !important;">Detail Donasi
                                    </h5>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span class="text-muted">Jenis Donasi:</span>
                                        <span class="fw-bold text-dark">{{ $successData['type'] ?? '-' }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span class="text-muted">Rekening Tujuan:</span>
                                        <span class="fw-bold text-dark">{{ $successData['bank'] ?? '-' }}</span>
                                    </div>
                                    <hr>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="text-muted">Jumlah:</span>
                                        <span class="fw-bold fs-3" style="color: #175C9E;">Rp
                                            {{ number_format($successData['amount'] ?? 0, 0, ',', '.') }}</span>
                                    </div>
                                </div>
                            @endif

                            <div class="alert alert-info border-0 text-start d-flex align-items-center" role="alert"
                                style="background-color: #e3f2fd; color: #0d47a1;">
                                <i class="fas fa-info-circle me-3 fs-4"></i>
                                <div>
                                    Status donasi Anda akan berubah menjadi "Terverifikasi" setelah admin memproses
                                    konfirmasi.
                                </div>
                            </div>

                            <div class="d-grid gap-3 mt-5">
                                <a href="{{ route('donasi.sekarang') }}" class="btn btn-primary-custom btn-lg">
                                    <i class="fas fa-plus-circle me-2"></i>Donasi Lagi
                                </a>
                                <a href="{{ route('donasi.informasi') }}" class="btn btn-outline-custom btn-lg">
                                    <i class="fas fa-house me-2"></i>Kembali ke Beranda
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Contact Info -->
                    @php
                        $websiteInfo = \App\Models\WebsiteInformation::first();
                        $contactPhone = $websiteInfo->footer_phone ?? '+62 812-xxxx-xxxx';
                        $waNumber = preg_replace('/[^0-9]/', '', $contactPhone);
                    @endphp
                    <div class="text-center mt-5">
                        <p class="text-muted small mb-0">
                            Ada pertanyaan? Hubungi kami di WhatsApp<br>
                            <a href="https://wa.me/{{ $waNumber }}" target="_blank" class="fw-bold text-decoration-none"
                                style="color: #175C9E;">
                                <i class="fab fa-whatsapp me-1"></i>{{ $contactPhone }}
                            </a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
