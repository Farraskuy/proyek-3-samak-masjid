<nav id="navbar-samak"
    class="navbar navbar-expand-xl navbar-light bg-white shadow-sm py-2 sticky-top border-bottom d-flex flex-column">

    <!-- =======================
         TOP NAVBAR (Brand + User)
    ======================== -->
    <div class="container d-flex align-items-center justify-content-between w-100">

        <!-- Brand -->
        <a class="navbar-brand d-flex align-items-center" href="{{ url('/') }}">
            <img src="{{ asset('assets/images/logo.png') }}" class="rounded me-2"
                style="width: 36px; height: 36px; object-fit: cover;">
            <span class="fw-semibold">SAMAK Masjid</span>
        </a>

        <!-- Toggle – Untuk menu layer kedua -->
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#menuNavbar">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Right User Section -->
        <div class="d-flex align-items-center">

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
                            <a class="dropdown-item py-2" href="#">
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
                            <a class="dropdown-item py-2" href="#">
                                <div class="d-flex align-items-center">
                                    <div class="bg-light rounded-circle d-flex justify-content-center align-items-center me-3"
                                        style="height: 35px; width: 35px;">
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
                <a href="{{ route('login') }}"
                    class="btn btn-outline-light text-black ms-2 px-3 py-1 rounded-pill border-2 d-flex align-items-center"
                    style="height: 38px; font-size: 0.875rem;">
                    <i class="fas fa-sign-in-alt me-2"></i>
                    <span class="d-none d-md-inline">Masuk</span>
                </a>
            @endguest

        </div>
    </div>

    <!-- =======================
         BOTTOM NAVBAR (Menu)
    ======================== -->
    <div class="container w-100">
        <div class="collapse navbar-collapse mt-2" id="menuNavbar">

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
                    <a class="nav-link nav-min {{ request()->is('postingan*') ? 'active' : '' }}" href="/postingan">
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
                        href="javascript:void(0)">
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

        </div>
    </div>

</nav>
