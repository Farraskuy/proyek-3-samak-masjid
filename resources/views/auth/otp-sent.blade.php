@extends('auth.layout')

@section('title', 'Kode Terkirim - Digital Masjid')

@section('content')
    <div class="auth-wrapper p-4">
        <div class="form-container d-flex align-items-center justify-content-center h-100">
            <div class="w-100 bg-white rounded-4 shadow-sm p-4 d-flex flex-column gap-3 auth-card">
                <div class="mb-3 px-3 py-2 rounded-4 fw-semibold text-white" style="width: fit-content; background-color: #CE9138">
                    Kode Terkirim
                </div>

                <h4>Kode verifikasi dikirim</h4>
                <p>Kami telah mengirimkan kode verifikasi ke <strong>{{ $destination }}</strong>. Silakan masukkan kode tersebut pada halaman verifikasi.</p>

                <a href="{{ route('auth.showVerifyForm') }}?destination={{ urlencode($destination) }}&type={{ $type }}" class="btn btn-primary">Masukkan Kode</a>

                <div class="text-muted small mt-3">Jika tidak menerima kode dalam beberapa menit, coba kirim ulang.</div>
            </div>
        </div>
    </div>
@endsection
