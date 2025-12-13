<aside class="sidebar bg-white py-2 offcanvas-lg offcanvas-start" tabindex="-1" id="sidebarMenu"
    aria-labelledby="sidebarMenuLabel" style="line-height: 1.25">
    <div class="offcanvas-header d-lg-none">
        <div class="d-flex align-items-center gap-2" id="sidebarMenuLabel">
            <img src="{{ asset('assets/images/logo.png') }}" alt="Logo Masjid" width="35" height="35">
            <div class="logo_name">
                <span class="h6 m-0 fw-semibold">SAMAK-Kampus</span>
                <div class="marquee-container">
                    <span class="marquee-text fw-light text-secondary">
                        Sistem Aplikasi Managemen Aktivitas dan Keuangan Masjid Kampus
                    </span>
                </div>
            </div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" data-bs-target="#sidebarMenu"
            aria-label="Close"></button>
    </div>
    <div class="offcanvas-body p-0 d-flex flex-column">
        <div class="logo d-none d-lg-flex">
            <a href="{{ url('/admin') }}" class="logo-details">
                <div class="img">
                    <img src="{{ asset('assets/images/logo.png') }}" alt="Logo Masjid" width="35" height="35"
                        class="d-inline-block align-text-top">
                </div>
                <div class="logo_name">
                    <span class=" h6 m-0 fw-semibold wrap-text">SAMAK-Kampus</span>
                    <div class="marquee-container">
                        <span class="marquee-text fw-light text-secondary">
                            Sistem Aplikasi Managemen Aktivitas dan Keuangan Masjid Kampus
                        </span>
                    </div>
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
                        <span class="link_name">Home</span>
                    </a>
                </div>
                <ul class="sub-menu blank">
                    <li class="fw-semibold link_name">Home</li>
                </ul>
            </li>

            {{-- 2. MODUL MANAJEMEN KONTEN (CMS) --}}
            {{-- Aktif jika path dimulai dengan admin/konten atau admin/postingan atau admin/galeri --}}
            @if (auth()->user()->hasPermission('view_posts') ||
                    auth()->user()->hasPermission('view_pages') ||
                    auth()->user()->hasPermission('view_gallery'))
                <li
                    class="{{ request()->is('admin/postingan*', 'admin/informasi-website*', 'admin/galeri*', 'admin/postingan/approval*') ? 'showMenu' : '' }}">
                    <div
                        class="nav-button {{ request()->is('admin/postingan*', 'admin/informasi-website*', 'admin/galeri*', 'admin/postingan/approval*') ? 'active' : '' }}">
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

                        @if (auth()->user()->hasPermission('view_posts'))
                            <li
                                class="nav-button {{ request()->is('admin/postingan*') && !request()->is('admin/postingan/approval*') ? 'active' : '' }}">
                                <a class="d-flex gap-2 fw-semibold" href="{{ url('/admin/postingan') }}">
                                    <span class="fa-regular fa-newspaper"></span> Artikel & Berita
                                </a>
                            </li>
                        @endif

                        @if (auth()->user()->hasPermission('approve_posts'))
                            <li class="nav-button {{ request()->is('admin/postingan/approval*') ? 'active' : '' }}">
                                <a class="d-flex gap-2 fw-semibold" href="{{ url('/admin/postingan/approval') }}">
                                    <span class="fa-regular fa-user-check"></span> Approval Artikel & Berita
                                </a>
                            </li>
                        @endif

                        @if (auth()->user()->hasPermission('view_pages'))
                            <li class="nav-button {{ request()->is('admin/informasi-website*') ? 'active' : '' }}">
                                <a class="d-flex gap-2 fw-semibold" href="{{ url('/admin/informasi-website') }}">
                                    <span class="fa-regular fa-file-lines"></span> Informasi Website
                                </a>
                            </li>
                        @endif

                        @if (auth()->user()->hasPermission('view_gallery'))
                            <li class="nav-button {{ request()->is('admin/galeri*') ? 'active' : '' }}">
                                <a class="d-flex gap-2 fw-semibold" href="{{ url('/admin/galeri') }}">
                                    <span class="fa-regular fa-images"></span> Galeri Foto
                                </a>
                            </li>
                        @endif
                    </ul>
                </li>
            @endif

            {{-- 3. MODUL JADWAL KEGIATAN --}}
            {{-- Aktif jika path dimulai dengan admin/kegiatan atau admin/kajian --}}
            @if (auth()->user()->hasPermission('view_events'))
                <li class="{{ request()->is('admin/jadwal-kegiatan*', 'admin/kajian*') ? 'showMenu' : '' }}">
                    <div
                        class="nav-button {{ request()->is('admin/jadwal-kegiatan*', 'admin/kajian*') ? 'active' : '' }}">
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
                        <li class="nav-button {{ request()->is('admin/jadwal-kegiatan*') ? 'active' : '' }}">
                            <a class="d-flex gap-2 fw-semibold" href="{{ url('/admin/jadwal-kegiatan') }}">
                                <span class="fa-regular fa-list-check"></span> Manajemen Kegiatan
                            </a>
                        </li>
                        <li class="nav-button {{ request()->is('admin/kajian*') ? 'active' : '' }}">
                            <a class="d-flex gap-2 fw-semibold" href="{{ url('/admin/kajian') }}">
                                <span class="fa-regular fa-user-check"></span> Verifikasi Pendaftar
                            </a>
                        </li>
                        <li class="nav-button {{ request()->is('admin/forms*') ? 'active' : '' }}">
                            <a class="d-flex gap-2 fw-semibold" href="{{ route('admin.forms.index') }}">
                                <span class="fa-regular fa-clipboard-list"></span> Form Builder
                            </a>
                        </li>
                    </ul>
                </li>
            @endif

            {{-- 4. MODUL KEUANGAN (ZIS) --}}
            {{-- Aktif jika path dimulai dengan admin/keuangan atau admin/donasi atau admin/infaqs atau admin/settings --}}
            @if (auth()->user()->hasPermission('view_finance') || auth()->user()->hasPermission('view_banks') || auth()->user()->hasPermission('view_infaq') || auth()->user()->hasPermission('manage_zakat_settings'))
                <li class="{{ request()->is('admin/keuangan*', 'admin/donasi*', 'admin/infaqs*', 'admin/banks*', 'admin/settings/zakat*', 'admin/dashboard-keuangan*') ? 'showMenu' : '' }}">
                    <div class="nav-button {{ request()->is('admin/keuangan*', 'admin/donasi*', 'admin/infaqs*', 'admin/banks*', 'admin/settings/zakat*', 'admin/dashboard-keuangan*') ? 'active' : '' }}">
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
                        @if (auth()->user()->hasPermission('view_finance'))
                            <li class="nav-button {{ request()->is('admin/dashboard-keuangan*') ? 'active' : '' }}">
                                <a class="d-flex gap-2 fw-semibold" href="{{ route('admin.finance.dashboard') }}">
                                    <span class="fa-regular fa-chart-pie"></span> Dashboard Keuangan
                                </a>
                            </li>
                        @endif
                        @if (auth()->user()->hasPermission('verify_donation'))
                            <li class="nav-button {{ request()->is('admin/donasi/verifikasi*') ? 'active' : '' }}">
                                <a class="d-flex gap-2 fw-semibold" href="{{ url('/admin/donasi/verifikasi') }}">
                                    <span class="fa-regular fa-money-check-dollar"></span> Verifikasi Donasi
                                </a>
                            </li>
                            <li class="nav-button {{ request()->is('admin/donasi/offline*') ? 'active' : '' }}">
                                <a class="d-flex gap-2 fw-semibold" href="{{ route('admin.donasi.offline.create') }}">
                                    <span class="fa-regular fa-hand-holding-heart"></span> Input Donasi Offline
                                </a>
                            </li>
                        @endif

                        @if (auth()->user()->hasPermission('view_finance'))
                            <li class="nav-button {{ request()->is('admin/kotak-amal*') ? 'active' : '' }}">
                                <a class="d-flex gap-2 fw-semibold" href="{{ route('admin.kotak-amal.index') }}">
                                    <span class="fa-regular fa-box-open"></span> Pendataan Kotak Amal
                                </a>
                            </li>
                        @endif
                        <li class="nav-button {{ request()->is('admin/keuangan*') ? 'active' : '' }}">
                            <a class="d-flex gap-2 fw-semibold" href="{{ url('/admin/keuangan') }}">
                                <span class="fa-regular fa-chart-line-up"></span> Manajemen Transaksi
                            </a>
                        </li>
                        @if (auth()->user()->hasPermission('view_banks'))
                            <li class="nav-button {{ request()->is('admin/banks*') ? 'active' : '' }}">
                                <a class="d-flex gap-2 fw-semibold" href="{{ route('admin.banks.index') }}">
                                    <span class="fa-regular fa-building-columns"></span> Manajemen Rekening
                                </a>
                            </li>
                        @endif
                        @if (auth()->user()->hasPermission('view_infaq'))
                            <li class="nav-button {{ request()->is('admin/infaqs*') ? 'active' : '' }}">
                                <a class="d-flex gap-2 fw-semibold" href="{{ route('admin.infaqs.index') }}">
                                    <span class="fa-regular fa-hand-holding-seedling"></span> Program Infaq
                                </a>
                            </li>
                        @endif
                        @if (auth()->user()->hasPermission('manage_zakat_settings'))
                            <li class="nav-button {{ request()->is('admin/settings/zakat*') ? 'active' : '' }}">
                                <a class="d-flex gap-2 fw-semibold" href="{{ route('admin.settings.zakat.index') }}">
                                    <span class="fa-regular fa-sliders"></span> Pengaturan Nisab
                                </a>
                            </li>
                        @endif
                    </ul>
                </li>
            @endif

            {{-- 5. MODUL LAYANAN JAMA'AH --}}
            {{-- Aktif jika path dimulai dengan admin/barang-hilang atau admin/konsultasi --}}
            @if (auth()->user()->hasPermission('view_lost_items') || auth()->user()->hasPermission('view_consultations'))
                <li class="{{ request()->is('admin/barang-hilang*', 'admin/konsultasi*') ? 'showMenu' : '' }}">
                    <div
                        class="nav-button {{ request()->is('admin/barang-hilang*', 'admin/konsultasi*') ? 'active' : '' }}">
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
                        @if (auth()->user()->hasPermission('view_lost_items'))
                            <li class="nav-button {{ request()->is('admin/barang-hilang*') ? 'active' : '' }}">
                                <a class="d-flex gap-2 fw-semibold" href="{{ url('/admin/barang-hilang') }}">
                                    <span class="fa-regular fa-box-open-full"></span> Lost & Found
                                </a>
                            </li>
                        @endif
                        @if (auth()->user()->hasPermission('view_consultations'))
                            <li class="nav-button {{ request()->is('admin/konsultasi*') ? 'active' : '' }}">
                                <a class="d-flex gap-2 fw-semibold" href="{{ url('/admin/konsultasi') }}">
                                    <span class="fa-regular fa-comment-dots"></span> Konsultasi Online
                                </a>
                            </li>
                        @endif
                    </ul>
                </li>
            @endif

            {{-- 6. MODUL MANAJEMEN PENGGUNA --}}
            @if (auth()->user()->hasPermission('view_users'))
                <li>
                    <div class="nav-button {{ request()->is('admin/users*') ? 'active' : '' }}">
                        <a href="{{ route('admin.users.index') }}">
                            <i class="fa-regular fa-users-gear"></i>
                            <span class="link_name">Manajemen Pengguna</span>
                        </a>
                    </div>
                    <ul class="sub-menu blank">
                        <li class="fw-semibold link_name">Manajemen Pengguna</li>
                    </ul>
                </li>
            @endif

            {{-- 7. MANAJEMEN ROLE --}}
            @if (auth()->user()->hasPermission('view_roles'))
                <li>
                    <div class="nav-button {{ request()->is('admin/roles*') ? 'active' : '' }}">
                        <a href="{{ route('admin.roles.index') }}">
                            <i class="fa-regular fa-user-shield"></i>
                            <span class="link_name">Manajemen Role</span>
                        </a>
                    </div>
                    <ul class="sub-menu blank">
                        <li class="fw-semibold link_name">Manajemen Role</li>
                    </ul>
                </li>
            @endif

            {{-- 8. BACKUP DATABASE (Admin Only) --}}
            @if (auth()->user()->hasPermission('manage_backup'))
                <li>
                    <div class="nav-button {{ request()->is('admin/backup*') ? 'active' : '' }}">
                        <a href="{{ route('admin.backup.index') }}">
                            <i class="fa-regular fa-database"></i>
                            <span class="link_name">Backup Database</span>
                        </a>
                    </div>
                    <ul class="sub-menu blank">
                        <li class="fw-semibold link_name">Backup Database</li>
                    </ul>
                </li>
            @endif




            {{-- BAGIAN BAWAH: PENGATURAN & KELUAR --}}
            {{-- <li class="position-absolute w-100 bg-white" style="bottom: 0">
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
        </li> --}}
        </ul>
    </div>
</aside>
