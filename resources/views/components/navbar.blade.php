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

        <!-- Toggle – Untuk menu layer kedua berubah jadi OFFCANVAS -->
        <button class="btn border-0 d-xl-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarMenu">
            <i class="fa-solid fa-bars fa-lg"></i>
        </button>

        <!-- Right User Section -->
        <div class="d-none d-xl-flex align-items-center">
            @auth
                <div class="dropdown">
                    <button
                        class="btn btn-outline-light text-dark d-flex align-items-center px-3 py-1 rounded-pill border-2 dropdown-toggle"
                        type="button" data-bs-toggle="dropdown" aria-expanded="false" style="height: 38px;">
                        <i class="fas fa-user-circle me-2"></i>
                        <span class="d-none d-md-inline fw-medium">{{ Auth::user()->full_name }}</span>
                    </button>

                    <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg py-2">
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

                        <li><hr class="dropdown-divider my-1 mx-3"></li>

                        @if (!Auth::user()->hasRole('jamaah'))
                            <li>
                                <a class="dropdown-item py-2" href="{{ route('admin.dashboard') }}">
                                    <div class="d-flex align-items-center">
                                        <div class="bg-light rounded-circle d-flex justify-content-center align-items-center me-3"
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
                        @endif

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

                        <li><hr class="dropdown-divider my-1 mx-3"></li>

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
                        style="height: 38px;">
                        <i class="fas fa-sign-in-alt me-2"></i>
                        <span class="d-none d-md-inline">Masuk</span>
                    </a>
                    <a href="{{ route('register') }}"
                        class="btn btn-outline-light fw-medium text-black ms-2 px-3 py-1 rounded-pill border-2 d-flex align-items-center text-white"
                        style="height: 38px; background-color: #CE9138;">
                        <i class="fas fa-user-plus me-2"></i>
                        <span class="d-none d-md-inline">Daftar</span>
                    </a>
                </div>
            @endguest

        </div>
    </div>

    <!-- ======================= BOTTOM NAVBAR (Menu) ======================== -->
    <div class="container w-100">
        <div class="collapse navbar-collapse justify-content-center" id="menuNavbar">
            <ul class="navbar-nav gap-lg-2 text-center">

                <li class="nav-item"><a class="nav-link nav-min {{ request()->is('/') ? 'active' : '' }}" href="/">Beranda</a></li>
                <li class="nav-item"><a class="nav-link nav-min {{ request()->is('donasi*') ? 'active' : '' }}" href="/donasi">Donasi</a></li>
                <li class="nav-item"><a class="nav-link nav-min {{ request()->is('laporan-keuangan*') ? 'active' : '' }}" href="/laporan-keuangan">Keuangan</a></li>
                <li class="nav-item"><a class="nav-link nav-min {{ request()->is('jadwal-kegiatan*') ? 'active' : '' }}" href="/jadwal-kegiatan">Kegiatan</a></li>
                <li class="nav-item"><a class="nav-link nav-min {{ request()->is('postingan*') ? 'active' : '' }}" href="/postingan">Postingan</a></li>
                <li class="nav-item"><a class="nav-link nav-min {{ request()->is('galeri*') ? 'active' : '' }}" href="/galeri">Galeri Kita</a></li>
                <li class="nav-item"><a class="nav-link nav-min {{ request()->is('layanan/barang-hilang*') ? 'active' : '' }}" href="/layanan/barang-hilang">Barang Hilang</a></li>
                <li class="nav-item"><a class="nav-link nav-min {{ request()->is('tentang-kami*') ? 'active' : '' }}" href="{{ route('client.tentang-kami') }}">Tentang Kami</a></li>
                <li class="nav-item"><a class="nav-link nav-min {{ request()->is('konsultasi*') ? 'active' : '' }}" href="{{ route('client.consultations.index') }}">Konsultasi</a></li>
            </ul>
        </div>
    </div>
</nav>

<!-- ======================= OFFCANVAS SIDEBAR (Mobile Menu) ======================== -->
<div class="offcanvas offcanvas-start" tabindex="-1" id="sidebarMenu">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title fw-semibold">Menu</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
    </div>

    <div class="offcanvas-body">
        <ul class="navbar-nav">
            <li class="nav-item"><a class="nav-link nav-min py-2 {{ request()->is('/') ? 'active' : '' }}" href="/">Beranda</a></li>
            <li class="nav-item"><a class="nav-link nav-min py-2 {{ request()->is('donasi*') ? 'active' : '' }}" href="/donasi">Donasi</a></li>
            <li class="nav-item"><a class="nav-link nav-min py-2 {{ request()->is('laporan-keuangan*') ? 'active' : '' }}" href="/laporan-keuangan">Keuangan</a></li>
            <li class="nav-item"><a class="nav-link nav-min py-2 {{ request()->is('jadwal-kegiatan*') ? 'active' : '' }}" href="/jadwal-kegiatan">Kegiatan</a></li>
            <li class="nav-item"><a class="nav-link nav-min py-2 {{ request()->is('postingan*') ? 'active' : '' }}" href="/postingan">Postingan</a></li>
            <li class="nav-item"><a class="nav-link nav-min py-2 {{ request()->is('galeri*') ? 'active' : '' }}" href="/galeri">Galeri Kita</a></li>
            <li class="nav-item"><a class="nav-link nav-min py-2 {{ request()->is('layanan/barang-hilang*') ? 'active' : '' }}" href="/layanan/barang-hilang">Barang Hilang</a></li>
            <li class="nav-item"><a class="nav-link nav-min py-2 {{ request()->is('tentang-kami*') ? 'active' : '' }}" href="{{ route('client.tentang-kami') }}">Tentang Kami</a></li>
            <li class="nav-item"><a class="nav-link nav-min py-2 {{ request()->is('konsultasi*') ? 'active' : '' }}" href="{{ route('client.consultations.index') }}">Konsultasi</a></li>
        </ul>

        <hr>

        <!-- USER SECTION -->
        @auth
            <div class="mt-3">
                <p class="text-muted small fw-bold">Akun Saya</p>
                <div class="d-grid gap-2">
                    <a href="{{ route('profile.show') }}" class="btn btn-light text-start"><i class="fas fa-id-card me-2"></i>Profil Saya</a>
                    <a href="{{ route('client.consultations.history') }}" class="btn btn-light text-start"><i class="fas fa-history me-2"></i>Riwayat Konsultasi</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="btn btn-danger text-start"><i class="fas fa-sign-out-alt me-2"></i>Logout</button>
                    </form>
                </div>
            </div>
        @endauth

        @guest
            <div class="mt-3 d-grid gap-2">
                <a href="{{ route('login') }}" class="btn btn-outline-primary w-100"><i class="fas fa-sign-in-alt me-2"></i>Masuk</a>
                <a href="{{ route('register') }}" class="btn btn-primary text-white w-100" style="background:#CE9138"><i class="fas fa-user-plus me-2"></i>Daftar</a>
            </div>
        @endguest

    </div>
</div>
