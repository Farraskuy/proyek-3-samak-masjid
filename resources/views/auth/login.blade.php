@extends('auth.layout')

@section('title', 'Masuk - Digital Masjid')

@push('styles')
    <style>
        .left-content {
            width: calc(100% - 450px);
        }

        .form-container {
            width: 100%;
            max-width: 450px;
            /* Ensures it never exceeds 550px */
        }

        .auth-wrapper {
            min-height: 100vh;
            display: flex;
            align-items: center;
        }
    </style>
@endpush

@section('content')
    <div class="auth-wrapper p-4">
        <!-- Left informational content (hidden on small screens) -->
        <div class="d-none d-lg-block left-content px-4 h-100">
            <div class="d-flex flex-column justify-content-between h-100">
                <div class="d-flex gap-3 align-items-center">
                    <img src="{{ asset('assets/images/logo.png') }}" alt="DIMAS Logo" class="logo bg-white rounded-4 p-1"
                        style="max-width: 70px; max-height: 70px;">
                    <div class="fw-semibold  d-flex flex-column justify-content-center">
                        <p class="fs-2 mb-0 text-white">SAMAK Masjid</p>
                        <p style="margin-top: -10px; margin-bottom: 0;">Sistem Aplikasi Managemen Aktivitas dan Keuangan
                            Masjid</p>
                    </div>
                </div>
                <div>
                    <h2 class="fw-semibold text-white">Menghubungkan Umat, Memakmurkan Masjid.</h2>
                    <p class="fw-semibold">Samak Masjid hadir untuk mendukung transparansi dan efisiensi Dewan Kemakmuran
                        Masjid (DKM) dalam mengelola
                        amanah umat dan melayani jamaah.</p>
                </div>
            </div>
        </div>

        <!-- Login Form -->
        <div class="form-container d-flex align-items-center justify-content-center h-100">
            <div class="w-100 h-100 bg-white rounded-4 shadow-sm p-4 d-flex flex-column gap-3 overflow-auto over">
                <div class="mb-4 px-3 py-2 rounded-4 fw-semibold text-white"
                    style="width: fit-content; background-color: #CE9138">
                    Masuk
                </div>
                <h4 class="fw-semibold">Selamat Datang Kembali di Samak Masjid!</h4>

                @if (session()->has('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="mb-3">
                        <label for="login" class="form-label">Nama Pengguna atau Email</label>
                        <input type="text" class="p-4 py-3 rounded-4 form-control @error('login') is-invalid @enderror"
                            id="login" name="login" placeholder="Masukan nama pengguna atau email kamu"
                            value="{{ old('login') }}" autofocus>
                        @error('login')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="p-4 py-3 rounded-4 form-control @error('password') is-invalid @enderror"
                            id="password" name="password" placeholder="Masukan Password" value="{{ old('password') }}"
                            autofocus>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-end mb-3">
                        <a href="#" class="text-decoration-none" style="color: #175C9E">Lupa Password?</a>
                    </div>

                    <button type="submit" style="background-color: #175C9E"
                        class="text-white btn fw-semibold rounded-4 py-2 w-100">Masuk</button>

                    <hr class="my-4">

                    <div class="text-center">
                        <span class="text-muted">Belum Punya akun? <a href="{{ route('register') }}"
                                class="text-decoration-none">Daftar Disini</a></span>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
