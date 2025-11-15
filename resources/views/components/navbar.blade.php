<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm py-2 sticky-top border-bottom">
    <div class="container">
        <!-- Brand -->
        <a class="navbar-brand d-flex align-items-center" href="{{ url('/') }}">
            <img src="{{ asset('assets/images/logo.png') }}" class="rounded me-2"
                style="width: 36px; height: 36px; object-fit: cover;">
            <span class="fw-semibold">SAMAK</span>
        </a>

        <!-- Toggle -->
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Menu -->
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav mx-auto gap-lg-2">
                <li class="nav-item">
                    <a class="nav-link nav-min" href="/">Beranda</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link nav-min" href="/jadwal-kegiatan">Kegiatan</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link nav-min" href="/donasi">Donasi</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link nav-min" href="/laporan-keuangan">Keuangan</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link nav-min" href="/layanan/barang-hilang">Lost & Found</a>
                </li>
            </ul>

            <!-- Auth User -->
            @auth
                <div class="dropdown">
                    <button class="btn btn-light rounded-pill px-3 py-1 d-flex align-items-center gap-2 shadow-sm"
                        data-bs-toggle="dropdown">
                        <i class="fas fa-user-circle text-primary fs-5"></i>
                        <span class="fw-medium d-none d-md-inline">{{ Auth::user()->full_name }}</span>
                    </button>

                    <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 mt-2 p-2">
                        <li>
                            <a class="dropdown-item rounded small py-2" href="#">
                                <i class="fas fa-id-card me-2 text-primary"></i> Profil Saya
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item rounded small py-2" href="#">
                                <i class="fas fa-bell me-2 text-warning"></i> Notifikasi
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item rounded small py-2" href="#">
                                <i class="fas fa-cog me-2 text-secondary"></i> Pengaturan
                            </a>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button class="dropdown-item text-danger rounded small py-2">
                                    <i class="fas fa-sign-out-alt me-2"></i> Logout
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            @endauth

            <!-- Guest -->
            @guest
                <a href="{{ route('login') }}" class="btn btn-primary rounded-pill px-3 ms-2">
                    Masuk
                </a>
            @endguest
        </div>
    </div>
</nav>
