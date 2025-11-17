@extends('auth.layout')

@section('title', 'Periksa Email Anda')

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

        .btn-back {
            border-radius: 12px;
            padding: 12px 18px;
            border: 1px solid #d1d5db;
            background-color: #f9fafb;
            color: #175C9E;
            font-weight: 600;
            text-decoration: none;
            display: inline-block;
            width: 100%;
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
            <div class="w-100 bg-white rounded-4 shadow-sm p-4 d-flex flex-column gap-3 overflow-auto auth-card">

                <div class="mb-3">
                    <i class="fa-regular fa-envelope icon-mail"></i>
                </div>

                <h4 class="fw-semibold mb-2">Periksa email Anda</h4>
                <p class="text-muted mb-2">
                    Kami telah mengirimkan tautan reset password ke email Anda.
                </p>
                <p class="small note mb-3">
                    Tidak menemukan emailnya? Coba periksa folder spam atau promosi.
                </p>

                <a href="{{ route('login') }}" class="btn-back">Kembali ke halaman login</a>
            </div>
        </div>
    </div>
@endsection
