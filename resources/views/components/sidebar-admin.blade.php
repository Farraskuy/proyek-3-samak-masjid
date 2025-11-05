<aside class="sidebar bg-white py-2" style="line-height: 1.25">
    <div class="logo">
        <a href="{{ url('/admin') }}" class="logo-details">
            <div class="img">
                <img src="{{ asset('assets/images/logo.png') }}" alt="Logo Masjid" width="35" height="35"
                    class="d-inline-block align-text-top">
            </div>
            <div class="logo_name">
                <span class=" h6 m-0 fw-semibold wrap-text">SAMAK-Kampus</span>
                <marquee behavior="scroll" direction="left" scrollamount="3" class="fw-light text-secondary"
                    style="font-size: 10px; overflow: hidden; white-space: nowrap;">
                    Sistem Aplikasi Managemen Aktivitas dan Keuangan Masjid Kampus
                </marquee>
            </div>
        </a>
    </div>

    {{-- NAVIGASI UTAMA APLIKASI SAMAK-KAMPUS --}}
    <ul class="nav-links" style="padding-bottom: 115px;">

        {{-- 1. HOME/DASHBOARD --}}
        <li>
            <div class="nav-button {{ request()->is('admin') ? 'active' : '' }}">
                <a href="{{ url('/admin') }}">
                    <i class="fa-regular fa-house"></i>
                    <span class="link_name">Dashboard</span>
                </a>
            </div>
            <ul class="sub-menu blank">
                <li class="fw-semibold link_name">Dashboard</li>
            </ul>
        </li>

        {{-- 2. MODUL MANAJEMEN KONTEN (CMS) --}}
        {{-- Aktif jika path dimulai dengan admin/konten atau admin/artikel atau admin/galeri --}}
        <li class="{{ request()->is('admin/artikel*', 'admin/halaman-statis*', 'admin/galeri*') ? 'showMenu' : '' }}">
            <div
                class="nav-button {{ request()->is('admin/artikel*', 'admin/halaman-statis*', 'admin/galeri*') ? 'active' : '' }}">
                <div class="iocn-link" onclick="expandMenu(this)">
                    <a>
                        <i class="fa-light fa-feather-pointed"></i>
                        <span class="link_name">Manajemen Konten</span>
                    </a>
                    <i class='fa-regular fa-angle-down arrow'></i>
                </div>
            </div>
            <ul class="sub-menu">
                <li><span class="link_name fw-semibold">Manajemen Konten</span></li>

                <li class="nav-button {{ request()->is('admin/artikel*') ? 'active' : '' }}">
                    <a class="d-flex gap-2 fw-semibold" href="{{ url('/admin/artikel') }}">
                        <span class="fa-regular fa-newspaper"></span> Artikel & Berita
                    </a>
                </li>
                <li class="nav-button {{ request()->is('admin/halaman-statis*') ? 'active' : '' }}">
                    <a class="d-flex gap-2 fw-semibold" href="{{ url('/admin/halaman-statis') }}">
                        <span class="fa-regular fa-file-lines"></span> Halaman Statis
                    </a>
                </li>
                <li class="nav-button {{ request()->is('admin/galeri*') ? 'active' : '' }}">
                    <a class="d-flex gap-2 fw-semibold" href="{{ url('/admin/galeri') }}">
                        <span class="fa-regular fa-images"></span> Galeri Foto
                    </a>
                </li>
            </ul>
        </li>

        {{-- 3. MODUL JADWAL KEGIATAN --}}
        {{-- Aktif jika path dimulai dengan admin/kegiatan atau admin/kajian --}}
        <li class="{{ request()->is('admin/kegiatan*', 'admin/kajian*') ? 'showMenu' : '' }}">
            <div class="nav-button {{ request()->is('admin/kegiatan*', 'admin/kajian*') ? 'active' : '' }}">
                <div class="iocn-link" onclick="expandMenu(this)">
                    <a>
                        <i class="fa-light fa-calendar-days"></i>
                        <span class="link_name">Jadwal & Event</span>
                    </a>
                    <i class='fa-regular fa-angle-down arrow'></i>
                </div>
            </div>
            <ul class="sub-menu">
                <li><span class="link_name fw-semibold">Jadwal & Event</span></li>
                <li class="nav-button {{ request()->is('admin/kegiatan*') ? 'active' : '' }}">
                    <a class="d-flex gap-2 fw-semibold" href="{{ url('/admin/kegiatan') }}">
                        <span class="fa-regular fa-list-check"></span> Manajemen Kegiatan
                    </a>
                </li>
                <li class="nav-button {{ request()->is('admin/kajian*') ? 'active' : '' }}">
                    <a class="d-flex gap-2 fw-semibold" href="{{ url('/admin/kajian') }}">
                        <span class="fa-regular fa-user-check"></span> Verifikasi Pendaftar
                    </a>
                </li>
            </ul>
        </li>

        {{-- 4. MODUL KEUANGAN (ZIS) --}}
        {{-- Aktif jika path dimulai dengan admin/keuangan atau admin/donasi --}}
        <li class="{{ request()->is('admin/keuangan*', 'admin/donasi*') ? 'showMenu' : '' }}">
            <div class="nav-button {{ request()->is('admin/keuangan*', 'admin/donasi*') ? 'active' : '' }}">
                <div class="iocn-link" onclick="expandMenu(this)">
                    <a>
                        <i class="fa-light fa-hand-holding-dollar"></i>
                        <span class="link_name">Keuangan (ZIS)</span>
                    </a>
                    <i class='fa-regular fa-angle-down arrow'></i>
                </div>
            </div>
            <ul class="sub-menu">
                <li><span class="link_name fw-semibold">Keuangan (ZIS)</span></li>
                <li class="nav-button {{ request()->is('admin/donasi/verifikasi*') ? 'active' : '' }}">
                    <a class="d-flex gap-2 fw-semibold" href="{{ url('/admin/donasi/verifikasi') }}">
                        <span class="fa-regular fa-money-check-dollar"></span> Verifikasi Donasi
                    </a>
                </li>
                <li class="nav-button {{ request()->is('admin/keuangan*') ? 'active' : '' }}">
                    <a class="d-flex gap-2 fw-semibold" href="{{ url('/admin/keuangan') }}">
                        <span class="fa-regular fa-chart-line-up"></span> Manajemen Transaksi
                    </a>
                </li>
            </ul>
        </li>

        {{-- 5. MODUL LAYANAN JAMA'AH --}}
        {{-- Aktif jika path dimulai dengan admin/barang-hilang atau admin/konsultasi --}}
        <li class="{{ request()->is('admin/barang-hilang*', 'admin/konsultasi*') ? 'showMenu' : '' }}">
            <div class="nav-button {{ request()->is('admin/barang-hilang*', 'admin/konsultasi*') ? 'active' : '' }}">
                <div class="iocn-link" onclick="expandMenu(this)">
                    <a>
                        <i class="fa-light fa-handshake-angle"></i>
                        <span class="link_name">Layanan Jama'ah</span>
                    </a>
                    <i class='fa-regular fa-angle-down arrow'></i>
                </div>
            </div>
            <ul class="sub-menu">
                <li><span class="link_name fw-semibold">Layanan Jama'ah</span></li>
                <li class="nav-button {{ request()->is('admin/barang-hilang*') ? 'active' : '' }}">
                    <a class="d-flex gap-2 fw-semibold" href="{{ url('/admin/barang-hilang') }}">
                        <span class="fa-regular fa-box-open-full"></span> Lost & Found
                    </a>
                </li>
                <li class="nav-button {{ request()->is('admin/konsultasi*') ? 'active' : '' }}">
                    <a class="d-flex gap-2 fw-semibold" href="{{ url('/admin/konsultasi') }}">
                        <span class="fa-regular fa-comment-dots"></span> Konsultasi Online
                    </a>
                </li>
            </ul>
        </li>

        {{-- 6. MODUL MANAJEMEN PENGGUNA --}}
        <li>
            <div class="nav-button {{ request()->is('admin/pengguna*') ? 'active' : '' }}">
                <a href="{{ url('/admin/pengguna') }}">
                    <i class="fa-regular fa-users-gear"></i>
                    <span class="link_name">Manajemen Pengguna</span>
                </a>
            </div>
            <ul class="sub-menu blank">
                <li class="fw-semibold link_name">Manajemen Pengguna</li>
            </ul>
        </li>


        {{-- BAGIAN BAWAH: PENGATURAN & KELUAR --}}
        <li class="position-absolute w-100 bg-white" style="bottom: 0">
            <div class="bg-white me-2">
                <div class="nav-button {{ request()->is('admin/pengaturan*') ? 'active' : '' }}">
                    <a href="{{ url('/admin/pengaturan') }}">
                        <i class="fa-regular fa-gear"></i>
                        <span class="link_name">Pengaturan Sistem</span>
                    </a>
                </div>
                <div class="nav-button">
                    <a href="{{ url('/logout') }}">
                        <i class="fa-regular fa-right-from-bracket" style="color: #dc3545 !important;"></i>
                        <span class="link_name" style="color: #dc3545 !important;">Logout</span>
                    </a>
                </div>
            </div>
            <ul class="sub-menu blank">
                <li class="fw-semibold link_name">Pengaturan</li>
            </ul>
        </li>
    </ul>
</aside>
