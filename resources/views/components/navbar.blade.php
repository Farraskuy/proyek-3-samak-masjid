<nav id="navbar-samak" class="navbar navbar-expand-xl navbar-light bg-white shadow-sm py-2 sticky-top border-bottom">
    <div class="container">
        <!-- Brand -->
        <a class="navbar-brand d-flex align-items-center" href="{{ url('/') }}">
            <img src="{{ asset('assets/images/logo.png') }}" class="rounded me-2" alt="Logo SAMAK Masjid"
                style="width: 36px; height: 36px; object-fit: cover;">
            <span class="fw-semibold">SAMAK Masjid</span>
        </a>

        <!-- Toggle -->
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Menu -->
        <div class="collapse navbar-collapse" id="navbarNav">
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
                        href="/tentang-kami">
                        Tentang Kami
                    </a>
                </li>

            </ul>


            <!-- Authenticated User Dropdown -->
            @auth
                <div class="dropdown ms-lg-2">
                    <button
                        class="btn btn-outline-light text-dark d-flex align-items-center px-3 py-1 rounded-pill border-2"
                        type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false"
                        style="transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1); height: 38px; border: none !important;">
                        <div class="me-2">
                            <i class="fas fa-user-circle"></i>
                        </div>
                        <span class="d-none d-md-inline fw-medium">{{ Auth::user()->full_name ?? 'Jamaah' }}</span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg py-2" aria-labelledby="userDropdown">
                        <li class="px-3 py-2">
                            <div class="d-flex align-items-center">
                                <div class="bg-primary bg-opacity-10 rounded-circle d-flex justify-content-center align-items-center me-3"
                                    style="height: 35px; width: 35px;">
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
                            <a class="dropdown-item py-2" href="{{ route('client.consultations.index') }}">
                                <div class="d-flex align-items-center">
                                    <div class="bg-light rounded-circle d-flex justify-content-center align-items-center me-3"
                                        style="height: 35px; width: 35px;">
                                        <i class="fas fa-comments text-info"></i>
                                    </div>
                                    <div>
                                        <div class="fw-medium">Konsultasi Saya</div>
                                        <small class="text-muted">Lihat riwayat konsultasi</small>
                                    </div>
                                </div>
                            </a>
                        </li>
                        @if (Auth::user()->role !== 'jamaah' && Auth::user()->role !== null)
                            <li>
                                <a class="dropdown-item py-2" href="{{ route('admin.dashboard') }}">
                                    <div class="d-flex align-items-center">
                                        <div class="bg-warning bg-opacity-10 rounded-circle d-flex justify-content-center align-items-center me-3"
                                            style="height: 35px; width: 35px;">
                                            <i class="fas fa-lock text-warning"></i>
                                        </div>
                                        <div>
                                            <div class="fw-medium">Ke Admin</div>
                                            <small class="text-muted">Masuk panel administrator</small>
                                        </div>
                                    </div>
                                </a>
                            </li>
                        @endif
                        <li>
                            <a class="dropdown-item py-2" href="{{ route('profile.preferences') }}">
                                <div class="d-flex align-items-center">
                                    <div class="bg-light rounded-circle d-flex justify-content-center align-items-center me-3"
                                        style="height: 35px; width: 35px;">
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

                <!-- Notification Bell -->
                <li class="nav-item dropdown">
                    <a class="nav-link position-relative" href="#" id="notificationDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-bell"></i>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" id="notif-count" style="font-size:10px;">0</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="notificationDropdown" id="notif-list">
                        <li><span class="dropdown-item-text text-muted">Tidak ada notifikasi baru</span></li>
                    </ul>
                </li>
                @push('scripts')
                <script src="https://cdn.jsdelivr.net/npm/laravel-echo/dist/echo.iife.js"></script>
                <script src="https://cdn.jsdelivr.net/npm/socket.io-client/dist/socket.io.js"></script>
                <script>
                window.Echo = new Echo({
                    broadcaster: 'reverb',
                    key: '{{ env('REVERB_APP_KEY') }}',
                    host: '{{ env('REVERB_HOST') }}',
                    port: {{ env('REVERB_PORT') }},
                    scheme: '{{ env('REVERB_SCHEME') }}',
                    authEndpoint: '/broadcasting/auth',
                    auth: {
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    }
                });
                window.Echo.private('user.{{ Auth::id() }}')
                    .listen('.NotificationCreated', function(e) {
                        let notifList = document.getElementById('notif-list');
                        let notifCount = document.getElementById('notif-count');
                        let li = document.createElement('li');
                        li.innerHTML = `<a class='dropdown-item' href='${e.notification.action_url}'>${e.notification.title}: ${e.notification.message}</a>`;
                        notifList.prepend(li);
                        notifCount.textContent = parseInt(notifCount.textContent) + 1;
                    });
                </script>
                @endpush
            @endauth

            <!-- Guest Login Button -->
            @guest
                <a href="{{ route('login') }}"
                    class="btn btn-outline-light text-black ms-lg-2 px-3 py-1 rounded-pill border-2 d-flex align-items-center"
                    style="transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1); height: 38px; font-size: 0.875rem;">
                    <i class="fas fa-sign-in-alt me-2"></i>
                    <span class="d-none d-md-inline">Masuk</span>
                </a>
            @endguest

        </div>
    </div>
</nav>
