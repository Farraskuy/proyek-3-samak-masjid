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
        }

        .auth-wrapper {
            min-height: 100vh;
            display: flex;
            align-items: center;
        }
    </style>
@endpush

@section('content')
    <div class="auth-wrapper p-0 p-md-4">
        <div class="d-none d-lg-block left-content px-4 h-100">
            <div class="d-flex flex-column justify-content-between h-100">
                <div class="d-flex gap-3 align-items-center">
                    <img src="{{ asset('assets/images/logo.png') }}" alt="DIMAS Logo" class="logo"
                        style="max-width: 70px; max-height: 70px;">
                    <div class="fw-semibold  d-flex flex-column justify-content-center">
                        <p class="fs-2 mb-0 text-white">SAMAK Masjid</p>
                        <p style="margin-top: -10px; margin-bottom: 0;">Sistem Aplikasi Managemen Aktivitas dan Keuangan
                            Masjid</p>
                    </div>
                </div>
                <div>
                    <h2 class="fw-semibold text-white">Menghubungkan Umat, Memakmurkan Masjid.</h2>
                    <p class="fw-medium" style="color:lightgray;">Samak Masjid hadir untuk mendukung transparansi dan efisiensi Dewan Kemakmuran
                        Masjid (DKM) dalam mengelola
                        amanah umat dan melayani jamaah.</p>
                </div>
            </div>
        </div>

        <!-- Login Form -->
        <div class="form-container d-flex align-items-center justify-content-center h-100" style="max-height: 600px">
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
                    <input type="hidden" id="g-recaptcha-response" name="g-recaptcha-response">

                    <div class="mb-3">
                        <label for="login" class="form-label">Nama Pengguna atau Email</label>
                        <input type="text" class="p-4 py-3 rounded-4 form-control @error('login') is-invalid @enderror"
                            id="login" name="login" placeholder="Masukan nama pengguna atau email kamu"
                            value="{{ old('login') }}" autofocus>
                        @error('login')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3 position-relative">
                        <label for="password" class="form-label">Password</label>
                        <input type="password"
                            class="p-4 py-3 rounded-4 form-control @error('password') is-invalid @enderror" id="password"
                            name="password" placeholder="Masukan Password" value="{{ old('password') }}" required autofocus>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-end mb-3">
                        <a href="{{ route('password.request') }}" class="text-decoration-none" style="color: #175C9E">Lupa
                            Password?</a>
                    </div>

                    <button type="submit" class="btn btn-submit text-white w-100 fw-semibold  rounded-3" id="submit-btn">
                        <span id="spinner" class="spinner-border spinner-border-sm d-none me-2 "
                            aria-hidden="true"></span>
                        Login
                    </button>
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

@push('scripts')
    <script src="https://www.google.com/recaptcha/api.js?render={{ config('services.recaptcha.key') }}"></script>
    <script>
        const form = document.querySelector('form');
        const btn = document.querySelector('.btn-submit');
        const spinner = btn.querySelector('#spinner');

        form.addEventListener('submit', function(e) {
            e.preventDefault();
            btn.disabled = true;
            spinner.classList.remove('d-none');
            grecaptcha.ready(function() {
                grecaptcha.execute('{{ config('services.recaptcha.key') }}', {
                    action: 'reset_password'
                }).then(function(token) {
                    document.getElementById('g-recaptcha-response').value = token;
                    form.submit();
                });
            });
        });
    </script>
@endpush
