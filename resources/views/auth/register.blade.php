@extends('auth.layout')

@section('title', 'Masuk - Digital Masjid')

@push('styles')
    <style>
        .left-content {
            width: calc(100% - 550px);
        }

        .form-container {
            width: 100%;
            max-width: 550px;
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
                <div class="d-flex gap-3">
                    <img src="{{ asset('assets/images/logo.png') }}" alt="DIMAS Logo" class="logo" style="max-width: 70px;">
                    <div class="fw-semibold  d-flex flex-column justify-content-center">
                        <p class="fs-2 mb-0 text-white">DIMAS</p>
                        <p style="margin-top: -10px; margin-bottom: 0;">Digital Masjid</p>
                    </div>
                </div>
                <div>
                    <h2 class="fw-semibold text-white">Menghubungkan Umat, Memakmurkan Masjid.</h2>
                    <p class="fw-semibold">DIMAS hadir untuk mendukung transparansi dan efisiensi Dewan Kemakmuran Masjid
                        (DKM) dalam mengelola
                        amanah umat dan melayani jamaah.</p>
                </div>
            </div>
        </div>

        <!-- Login Form -->
        <div class="form-container d-flex align-items-center justify-content-center h-100">
            <div class="w-100 h-100 bg-white rounded-4 shadow-sm p-4 d-flex flex-column gap-2 overflow-auto">
                <div class="mb-3 px-3 py-2 rounded-4 fw-semibold text-white"
                    style="width: fit-content; background-color: #CE9138">
                    Daftar
                </div>

                <h4 class="fw-semibold">Daftar Sebagai Jamaah Digital Masjid</h4>

                <form method="POST" action="{{ route('register') }}">
                    @csrf



                    <div class="d-flex gap-3 mb-3 flex-wrap flex-lg-nowrap">
                        <div class="flex-grow-1">
                            <label for="full_name" class="form-label">Nama Lengkap</label>
                            <input type="text"
                                class="p-4 py-3 rounded-4 form-control @error('full_name') is-invalid @enderror"
                                id="full_name" name="full_name" placeholder="Contoh: Budiono Susanto"
                                value="{{ old('full_name') }}" required autofocus>
                            @error('full_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="flex-grow-1">
                            <label for="username" class="form-label">Nama Unik Pengguna</label>
                            <input type="text"
                                class="p-4 py-3 rounded-4 form-control @error('username') is-invalid @enderror"
                                id="username" name="username" placeholder="Contoh: budisusanto123"
                                value="{{ old('username') }}" required autofocus>
                            @error('username')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="p-4 py-3 rounded-4 form-control @error('email') is-invalid @enderror"
                            id="email" name="email" placeholder="Contoh: budisusanto123@gmail.com"
                            value="{{ old('email') }}" required autofocus>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="phone_number" class="form-label">Nomor Telepon</label>
                        <input type="tel"
                            class="p-4 py-3 rounded-4 form-control @error('phone_number') is-invalid @enderror"
                            id="phone_number" name="phone_number" placeholder="Contoh: 012345678910"
                            value="{{ old('phone_number') }}" required autofocus>
                        @error('phone_number')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3 position-relative">

                        <label for="password" class="form-label">Password</label>
                        <input type="password"
                            class="p-4 py-3 rounded-4 form-control @error('password') is-invalid @enderror" id="password"
                            name="password" placeholder="Masukan Password" value="{{ old('password') }}" required
                            autofocus>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" style="background-color: #175C9E"
                        class="text-white btn fw-semibold rounded-4 py-2 w-100">Masuk</button>

                    <hr class="my-4">

                    <div class="text-center">
                        <span class="text-muted">Sudah Punya akun? <a href="{{ route('login') }}"
                                class="text-decoration-none">Masuk Disini</a></span>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const passwordInput = document.querySelectorAll('input[type="password"]');
            const togglePassword = document.createElement('div');
            togglePassword.innerHTML = '<i class="fa-regular fa-eye-slash"></i>';
            togglePassword.classList.add('position-absolute', 'bottom-0', 'end-0', 'p-4', 'py-3', 'text-muted');
            togglePassword.style.cursor = 'pointer';

            togglePassword.addEventListener('click', function() {
                passwordInput.forEach(function(input) {
                    if (input.type === 'password') {
                        input.type = 'text';
                        togglePassword.innerHTML = '<i class="fa-regular fa-eye"></i>';
                    } else {
                        input.type = 'password';
                        togglePassword.innerHTML = '<i class="fa-regular fa-eye-slash"></i>';
                    }
                });
            });

            passwordInput.forEach(function(input) {
                input.parentElement.prepend(togglePassword);
            });
        });
    </script>
@endpush
