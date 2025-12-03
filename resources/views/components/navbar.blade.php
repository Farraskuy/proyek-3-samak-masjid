<nav id="navbar-samak"
    class="navbar navbar-expand-xl navbar-light bg-white shadow-sm py-2 sticky-top border-bottom d-flex flex-column">

    <!-- ======================= TOP NAVBAR ======================== -->
    <div class="container d-flex align-items-center justify-content-between w-100">

        <!-- Brand -->
        <a class="navbar-brand d-flex align-items-center" href="{{ url('/') }}">
            <img src="{{ asset('assets/images/logo.png') }}" class="rounded me-2" alt="Logo SAMAK Masjid"
                style="width: 36px; height: 36px; object-fit: cover;">
            <span class="fw-semibold">SAMAK Masjid</span>
        </a>

        <!-- Toggle – Untuk menu layer kedua -->
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="offcanvas" data-bs-target="#menuNavbar"
            aria-controls="menuNavbar">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Right User Section -->
        <div class="d-none d-xl-flex align-items-center">
            @auth
                <!-- User Dropdown -->
                <div class="dropdown">
                    <button
                        class="btn btn-outline-light text-dark d-flex align-items-center px-3 py-1 rounded-pill border-2 dropdown-toggle"
                        type="button" data-bs-toggle="dropdown" aria-expanded="false" style="height: 38px;">
                        <i class="fas fa-user-circle me-2"></i>
                        <span class="d-none d-md-inline fw-medium">{{ Auth::user()->full_name }}</span>
                    </button>

                    <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg py-2">
                        @if (!Auth::user()->hasRole('guest') && !Auth::user()->hasRole('jamaah'))
                            <li>
                                <a class="dropdown-item py-2" href="{{ route('admin.dashboard') }}">
                                    <div class="d-flex align-items-center">
                                        <div class="bg-primary bg-opacity-10 rounded-circle d-flex justify-content-center align-items-center me-3"
                                            style="height: 35px; width: 35px;">
                                            <i class="fas fa-tachometer-alt text-primary"></i>
                                        </div>
                                        <div>
                                            <div class="fw-medium">Dashboard Admin</div>
                                            <small class="text-muted">Akses halaman admin</small>
                                        </div>
                                    </div>
                                </a>
                            </li>
                            <li>
                                <hr class="dropdown-divider my-1 mx-3">
                            </li>
                        @endif
                        <li class="px-3 py-2">
                            <div class="d-flex align-items-center">
                                <div class="bg-primary bg-opacity-10 rounded-circle d-flex justify-content-center align-items-center me-3"
                                    style="height: 35px; width: 35px;">
                                    <i class="fas fa-user text-primary"></i>
                                </div>
                                <div>
                                    <div class="fw-semibold text-dark">Halo, {{ Auth::user()->full_name }}!</div>
                                    <small class="text-muted">Selamat datang kembali</small>
                                </div>
                            </div>
                        </li>

                        <li>
                            <hr class="dropdown-divider my-1 mx-3">
                        </li>

                        <li>
                            <a class="dropdown-item py-2" href="{{ route('profile.show') }}">
                                <div class="d-flex align-items-center">
                                    <div class="bg-light rounded-circle d-flex justify-content-center align-items-center me-3"
                                        style="height: 35px; width: 35px;">
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
                            <a class="dropdown-item py-2" href="{{ route('profile.preferences') }}">
                                <div class="d-flex align-items-center">
                                    <div class="bg-light rounded-circle d-flex justify-content-center align-items-center me-3"
                                        style="height: 35px; width: 35px;">
                                        <i class="fas fa-bell text-warning"></i>
                                    </div>
                                    <div>
                                        <div class="fw-medium">Notifikasi & Preferensi</div>
                                        <small class="text-muted">Atur notifikasi</small>
                                    </div>
                                </div>
                            </a>
                        </li>
                        </li>

                        <li>
                            <a class="dropdown-item py-2" href="{{ route('client.consultations.history') }}">
                                <div class="d-flex align-items-center">
                                    <div class="bg-light rounded-circle d-flex justify-content-center align-items-center me-3"
                                        style="height: 35px; width: 35px;">
                                        <i class="fas fa-history text-info"></i>
                                    </div>
                                    <div>
                                        <div class="fw-medium">Riwayat Konsultasi</div>
                                        <small class="text-muted">Lihat konsultasi saya</small>
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
                                        <div class="bg-danger bg-opacity-10 rounded-circle d-flex justify-content-center align-items-center me-3"
                                            style="height: 35px; width: 35px;">
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

            @guest
                <div class="d-flex">
                    <a href="{{ route('login') }}"
                        class="btn btn-outline-light fw-medium text-black ms-2 px-3 py-1 rounded-pill border-2 d-flex align-items-center"
                        style="height: 38px; font-size: 0.875rem;">
                        <i class="fas fa-sign-in-alt me-2"></i>
                        <span class="d-none d-md-inline">Masuk</span>
                    </a>
                    <a href="{{ route('register') }}"
                        class="btn btn-outline-light fw-medium text-black ms-2 px-3 py-1 rounded-pill border-2 d-flex align-items-center text-white"
                        style="height: 38px; font-size: 0.875rem; background-color: #CE9138;">
                        <i class="fas fa-user-plus me-2"></i>
                        <span class="d-none d-md-inline">Daftar</span>
                    </a>
                </div>
            @endguest

        </div>
    </div>

    <!-- ======================= BOTTOM NAVBAR (Menu) ======================== -->
    <div class="container w-100">
        <!-- Offcanvas Trigger (Mobile) -->
        <!-- Note: The toggler button is already in the TOP NAVBAR section, targeting #menuNavbar -->

        <div class="offcanvas offcanvas-end" tabindex="-1" id="menuNavbar" aria-labelledby="menuNavbarLabel">
            <div class="offcanvas-header border-bottom">
                <h5 class="offcanvas-title fw-bold" id="menuNavbarLabel" style="color: #175C9E;">
                    <img src="{{ asset('assets/images/logo.png') }}" class="rounded me-2" alt="Logo"
                        style="width: 30px; height: 30px; object-fit: cover;">
                    Menu Utama
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">
                <ul class="navbar-nav mx-auto gap-lg-2">

                    <li class="nav-item">
                        <a class="nav-link nav-min {{ request()->is('/') ? 'active' : '' }}" href="/">
                            Beranda
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link nav-min {{ request()->is('donasi*') ? 'active' : '' }}" href="/donasi">
                            Donasi
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link nav-min {{ request()->is('laporan-keuangan*') ? 'active' : '' }}"
                            href="/laporan-keuangan">
                            Keuangan
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link nav-min {{ request()->is('jadwal-kegiatan*') ? 'active' : '' }}"
                            href="/jadwal-kegiatan">
                            Kegiatan
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link nav-min {{ request()->is('postingan*') ? 'active' : '' }}"
                            href="/postingan">
                            Postingan
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link nav-min {{ request()->is('galeri*') ? 'active' : '' }}" href="/galeri">
                            Galeri Kita
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link nav-min {{ request()->is('layanan/barang-hilang*') ? 'active' : '' }}"
                            href="/layanan/barang-hilang">
                            Barang Hilang
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link nav-min {{ request()->is('tentang-kami*') ? 'active' : '' }}"
                            href="{{ route('client.tentang-kami') }}">
                            Tentang Kami
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link nav-min {{ request()->is('konsultasi*') ? 'active' : '' }}"
                            href="{{ route('client.consultations.index') }}">
                            Konsultasi
                        </a>
                    </li>
                </ul>

                @guest
                    <div class="d-flex flex-column d-xl-none mt-4 gap-2">
                        <hr class="my-2">
                        <p class="text-muted small mb-2 fw-bold px-2">Area Pengguna</p>
                        <a href="{{ route('login') }}" class="btn btn-outline-primary w-100 rounded-pill">
                            <i class="fas fa-sign-in-alt me-2"></i>Masuk
                        </a>
                        <a href="{{ route('register') }}" class="btn btn-primary w-100 rounded-pill text-white"
                            style="background-color: #CE9138; border-color: #CE9138;">
                            <i class="fas fa-user-plus me-2"></i>Daftar
                        </a>
                    </div>
                @endguest

                @auth
                    <div class="d-flex flex-column d-xl-none mt-4">
                        <hr class="my-2">
                        <p class="text-muted small mb-2 fw-bold px-2">Akun Saya</p>
                        <div class="d-flex align-items-center px-2 mb-3">
                            <div class="bg-primary bg-opacity-10 rounded-circle d-flex justify-content-center align-items-center me-3"
                                style="height: 40px; width: 40px;">
                                <i class="fas fa-user text-primary"></i>
                            </div>
                            <div>
                                <div class="fw-semibold text-dark">{{ Auth::user()->full_name }}</div>
                                <small class="text-muted">{{ Auth::user()->email }}</small>
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            @if (!Auth::user()->hasRole('guest') && !Auth::user()->hasRole('jamaah'))
                                <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-primary btn-sm text-start">
                                    <i class="fas fa-tachometer-alt me-2"></i>Dashboard Admin
                                </a>
                            @endif
                            <a href="{{ route('profile.show') }}" class="btn btn-light btn-sm text-start">
                                <i class="fas fa-id-card me-2 text-primary"></i>Profil Saya
                            </a>
                            <a href="{{ route('client.consultations.history') }}"
                                class="btn btn-light btn-sm text-start">
                                <i class="fas fa-history me-2 text-info"></i>Riwayat Konsultasi
                            </a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="btn btn-danger btn-sm w-100 text-start">
                                    <i class="fas fa-sign-out-alt me-2"></i>Logout
                                </button>
                            </form>
                        </div>
                    </div>
                @endauth

            </div>
        </div>
    </div>

    <style>
        /* Desktop Reset for Offcanvas */
        @media (min-width: 1200px) {
            .offcanvas {
                position: static !important;
                z-index: auto !important;
                flex-grow: 1 !important;
                width: auto !important;
                height: auto !important;
                visibility: visible !important;
                background-color: transparent !important;
                border: 0 !important;
                transform: none !important;
                transition: none !important;
                display: flex !important;
                flex-direction: row !important;
                align-items: center !important;
            }

            .offcanvas-header {
                display: none !important;
            }

            .offcanvas-body {
                display: flex !important;
                flex-grow: 1 !important;
                padding: 0 !important;
                overflow-y: visible !important;
            }

            .offcanvas.show {
                box-shadow: none !important;
            }

            .offcanvas-backdrop {
                display: none !important;
            }
        }
    </style>

</nav>
