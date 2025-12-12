@extends('auth.layout')

@section('title', 'Kode Terkirim')

@push('styles')
    <style>
        .auth-card {
            max-width: 450px;
        }

        .icon-mail {
            font-size: 36px;
            color: #175C9E;
        }

        .note {
            color: #6b7280;
        }

        h4 {
            font-size: 1.25rem;
        }

        p {
            font-size: 0.875rem;
        }

        .btn-back,
        .btn-primary-custom {
            border-radius: 12px;
            padding: 12px 18px;
            font-weight: 600;
            text-decoration: none;
            display: inline-block;
            width: 100%;
        }

        .btn-primary-custom {
            background-color: #175C9E;
            color: white;
            border: none;
        }

        .btn-primary-custom:hover {
            background-color: #134570;
            color: white;
        }

        .btn-back {
            border: 1px solid #d1d5db;
            background-color: #f9fafb;
            color: #175C9E;
        }

        .btn-back:hover {
            background-color: #e5e7eb;
            color: #134570;
        }
    </style>
@endpush

@section('content')
    <div class="auth-wrapper p-0 p-md-4" style="min-height:100vh;">
        <div class="form-container d-flex align-items-center justify-content-center h-100">
            <div class="w-100 bg-white rounded-4 shadow-sm p-4 d-flex flex-column gap-3 auth-card">

                {{-- Icon --}}
                <div class="mb-3">
                    <i class="fa-regular fa-envelope icon-mail"></i>
                </div>

                {{-- Heading --}}
                <h4 class="fw-semibold mb-2">Kode verifikasi dikirim</h4>

                {{-- Info --}}
                <p class="text-muted mb-2">
                    Kami telah mengirimkan kode verifikasi ke <strong>{{ $destination }}</strong>.
                </p>

                <p class="small note mb-3">
                    Jika tidak menerima kode dalam beberapa menit, coba kirim ulang.
                </p>

                {{-- Tombol Pergi ke Halaman Verifikasi --}}
                <a href="{{ route('auth.showVerifyForm') }}?destination={{ urlencode($destination) }}"
                    class="btn-primary-custom">
                    Masukkan Kode
                </a>

                {{-- Kembali ke Login --}}
                <a href="/" class="btn-back mt-2">
                    Lanjut Ke Halaman Utama
                </a>
            </div>
        </div>
    </div>
@endsection
