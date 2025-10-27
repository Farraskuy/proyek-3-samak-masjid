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
            <div class="w-100 h-100 bg-white rounded-4 shadow-sm p-4 d-flex flex-column gap-2">
                <div class="mb-3 px-3 py-2 rounded-4 fw-semibold text-white"
                    style="width: fit-content; background-color: #CE9138">
                    Daftar
                </div>

                <h4 class="fw-semibold">Daftar Sebagai Jamaah Digital Masjid</h4>

                <form method="POST" action="{{ route('login') }}">
                    @csrf



                    <div class="d-flex gap-3">
                        <div class="mb-3 flex-grow-1">
                            <label for="nama_lengkap" class="form-label">Nama Lengkap</label>
                            <input type="text"
                                class="p-4 py-3 rounded-4 form-control @error('nama_lengkap') is-invalid @enderror"
                                id="nama_lengkap" name="nama_lengkap" placeholder="Contoh: Budiono Susanto"
                                value="{{ old('nama_lengkap') }}" required autofocus>
                            @error('nama_lengkap')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3 flex-grow-1">
                            <label for="nama_unik" class="form-label">Nama Unik Pengguna</label>
                            <input type="text"
                                class="p-4 py-3 rounded-4 form-control @error('nama_unik') is-invalid @enderror"
                                id="nama_unik" name="nama_unik" placeholder="Contoh: budisusanto123"
                                value="{{ old('nama_unik') }}" required autofocus>
                            @error('nama_unik')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="nama_unik" class="form-label">Email</label>
                        <input type="email"
                            class="p-4 py-3 rounded-4 form-control @error('nama_unik') is-invalid @enderror" id="nama_unik"
                            name="nama_unik" placeholder="Contoh: budisusanto123@gmail.com" value="{{ old('nama_unik') }}"
                            required autofocus>
                        @error('nama_unik')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="login" class="form-label">Password</label>
                        <input type="password" class="p-4 py-3 rounded-4 form-control @error('login') is-invalid @enderror"
                            id="login" name="login" placeholder="Masukan Password" value="{{ old('login') }}"
                            required autofocus>
                        @error('login')
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
        console.log('Halaman login dimuat');
    </script>
@endpush
