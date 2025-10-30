<nav class="navbar navbar-expand-lg navbar-dark position-sticky" style="top: 0; z-index: 10; background-color: #175C9E">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="{{ url('/') }}">
            <img src="{{ asset("assets/images/logo.png") }}" class="rounded-lg d-flex align-items-center justify-content-center me-2" style="width: 40px; height: 40px;">
            <span class="d-none d-sm-inline fw-semibold">SAMAK Masjid</span>
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                <li class="nav-item"><a class="nav-link active" href="/">Beranda</a></li>
                <li class="nav-item"><a class="nav-link" href="/jadwal-kegiatan">Jadwal Kegiatan</a></li>
                <li class="nav-item"><a class="nav-link" href="/donasi">Donasi</a></li>
                <li class="nav-item"><a class="nav-link" href="/laporan-keuangan">Laporan Keuangan</a></li>
                <li class="nav-item"><a class="nav-link" href="/layanan/barang-hilang">Lost & Found</a></li>
            </ul>

            <div class="d-flex align-items-center">
                <div class="dropdown me-2">
                    <button class="btn btn-outline-light btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        <i class="fas fa-cog me-1"></i> Jamaah
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="#">Profil</a></li>
                        <li><a class="dropdown-item" href="#">Pengaturan</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger" href="#">Logout</a></li>
                    </ul>
                </div>
                <div class="dropdown">
                    <button class="btn btn-outline-light btn-sm" type="button" data-bs-toggle="dropdown">
                        <i class="fas fa-user"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="#">Pengaturan Akun</a></li>
                        <li><a class="dropdown-item" href="#">Notifikasi</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</nav>
