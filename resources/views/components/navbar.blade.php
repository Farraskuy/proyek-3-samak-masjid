<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm sticky-top" id="navbar-samak"
    style="background: linear-gradient(135deg, #175C9E 0%, #1a6bb8 100%);">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="{{ url('/') }}">
            <img src="{{ asset('assets/images/logo.png') }}"
                class="rounded-lg d-flex align-items-center justify-content-center me-2"
                style="width: 40px; height: 40px; object-fit: cover;">
            <span class="d-none d-sm-inline fw-bold">SAMAK Masjid</span>
        </a>

        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link rounded-pill active" href="/">Beranda</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link rounded-pill" href="/jadwal-kegiatan">Jadwal Kegiatan</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link rounded-pill" href="/donasi">Donasi</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link rounded-pill" href="/laporan-keuangan">Laporan Keuangan</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link rounded-pill" href="/layanan/barang-hilang">Lost & Found</a>
                </li>
            </ul>

            <!-- Authenticated User Dropdown -->
            @auth
                <div class="dropdown ms-lg-2">
                    <button
                        class="btn btn-outline-light d-flex align-items-center px-3 py-1 rounded-pill border-2 dropdown-toggle"
                        type="button" data-bs-toggle="dropdown" aria-expanded="false"
                        style="transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1); height: 38px; font-size: 0.875rem;">
                        <div class="me-2">
                            <i class="fas fa-user-circle"></i>
                        </div>
                        <span class="d-none d-md-inline fw-medium">{{ Auth::user()->full_name ?? 'Jamaah' }}</span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg py-2">
                        <li class="px-3 py-2">
                            <div class="d-flex align-items-center">
                                <div class="bg-primary bg-opacity-10 rounded-circle d-flex justify-content-center align-items-center me-3" style="height: 35px; width: 35px;">
                                    <i class="fas fa-user text-primary"></i>
                                </div>
                                <div>
                                    <div class="fw-semibold text-dark">Halo, {{ Auth::user()->full_name ?? 'Jamaah' }}!</div>
                                    <small class="text-muted">Selamat datang kembali</small>
                                </div>
                            </div>
                        </li>
                        <li>
                            <hr class="dropdown-divider my-1 mx-3">
                        </li>
                        <li>
                            <a class="dropdown-item py-2" href="">
                                <div class="d-flex align-items-center">
                                    <div class="bg-light rounded-circle d-flex justify-content-center align-items-center me-3" style="height: 35px; width: 35px;">
                                        <i class="fas fa-id-card text-primary"></i>
                                    </div>
                                    <div>
                                        <div class="fw-medium">Profil Saya</div>
                                        <small class="text-muted">Kelola informasi akun</small>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item py-2" href="">
                                <div class="d-flex align-items-center">
                                    <div class="bg-light rounded-circle d-flex justify-content-center align-items-center me-3" style="height: 35px; width: 35px;">
                                        <i class="fas fa-bell text-warning"></i>
                                    </div>
                                    <div>
                                        <div class="fw-medium">Notifikasi</div>
                                        <small class="text-muted">Lihat pemberitahuan terbaru</small>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item py-2" href="">
                                <div class="d-flex align-items-center">
                                    <div class="bg-light rounded-circle d-flex justify-content-center align-items-center me-3" style="height: 35px; width: 35px;">
                                        <i class="fas fa-cog text-secondary"></i>
                                    </div>
                                    <div>
                                        <div class="fw-medium">Pengaturan Akun</div>
                                        <small class="text-muted">Sesuaikan preferensi Anda</small>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li>
                            <hr class="dropdown-divider my-1 mx-3">
                        </li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}" class="w-100">
                                @csrf
                                <button type="submit" class="dropdown-item py-2 text-danger text-start">
                                    <div class="d-flex align-items-center">
                                        <div class="bg-danger bg-opacity-10 rounded-circle d-flex justify-content-center align-items-center me-3" style="height: 35px; width: 35px;">
                                            <i class="fas fa-sign-out-alt text-danger"></i>
                                        </div>
                                        <div>
                                            <div class="fw-medium">Logout</div>
                                            <small class="text-muted">Keluar dari akun Anda</small>
                                        </div>
                                    </div>
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            @endauth

            <!-- Guest Login Button -->
            @guest
                <a href="{{ route('login') }}" class="btn btn-outline-light ms-lg-2 px-3 py-1 rounded-pill border-2 d-flex align-items-center"
                    style="transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1); height: 38px; font-size: 0.875rem;">
                    <i class="fas fa-sign-in-alt me-2" style="margin-bottom: 2.5px"></i>
                    <span class="d-none d-md-inline">Masuk</span>
                </a>
            @endguest
        </div>
    </div>
</nav>
