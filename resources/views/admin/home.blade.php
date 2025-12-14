@extends('admin.layout')

@section('title', 'Dashboard')

@push('styles')
    <style>
        /* Styling dasar untuk home-item */
        .home-item {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 10px 15px;
            border-radius: 8px;
            transition: background-color 0.2s;
        }

        .home-item:hover {
            background-color: #e9ecef;
            /* Hover effect */
        }

        .home-item .icon {
            font-size: 2.2rem;
            width: 30px;
            /* Lebar tetap untuk ikon */
            text-align: center;
        }

        .fs-14px {
            font-size: 14px;
        }
    </style>
@endpush
@section('content')
    <section class="p-5 position-relative">
        {{-- Header Background Biru Masjid --}}
        <div class="position-absolute w-100"
            style="height: 200px; background-color: #175C9E; z-index: -1; top: 0; left: 0; right: 0;"></div>

        {{-- Informasi Sambutan --}}
        <h1 class="fw-bold m-0 text-white">Selamat Datang di Halaman Admin,</h1>
        <p class="fw-semibold text-white">SAMAK-Kampus (Sistem Aplikasi Masjid Kampus)</p>

        {{-- Konten Utama Menu Halaman --}}
        <div class="rounded-3 bg-white p-4 border- shadow-sm d-flex flex-wrap" style="gap: 20px;">

            {{-- Bagian Kiri: Manajemen Konten & Kegiatan --}}
            @if (Auth::user()->hasPermission('view_posts') ||
                    Auth::user()->hasPermission('view_pages') ||
                    Auth::user()->hasPermission('view_gallery') ||
                    Auth::user()->hasPermission('view_events'))
                <div class="flex-grow-1" style="min-width: 300px;">
                    <p class="fw-bold mb-3 fs-14px text-primary">Konten & Kegiatan Masjid</p>
                    <div class="d-flex gap-3 mb-3 py-2 bg-light rounded-3 flex-wrap w-100">

                        {{-- 1. Manajemen Artikel & Postingan (CMS) --}}
                        @can('view_posts')
                            <a href="{{ route('admin.postingan.index') }}" class="btn text-start home-item">
                                <i class="fa-duotone icon fa-newspaper" style="color: #007bff"></i>
                                <div>
                                    <p class="fw-semibold m-0">Manajemen Postingan <i class="fa-regular fa-arrow-right"></i>
                                    </p>
                                    <p class="m-0 text-secondary">CRUD Berita/Artikel/Tausiyah </p>
                                </div>
                            </a>
                        @endcan

                        {{-- 2. Informasi Website --}}
                        @can('view_pages')
                            <a href="{{ route('admin.website-information.index') }}" class="btn text-start home-item">
                                <i class="fa-duotone icon fa-file-lines" style="color: #6c757d"></i>
                                <div>
                                    <p class="fw-semibold m-0">Informasi Website <i class="fa-regular fa-arrow-right"></i></p>
                                    <p class="m-0 text-secondary">Pengaturan informasi website</p>
                                </div>
                            </a>
                        @endcan

                        {{-- 3. Manajemen Galeri Foto --}}
                        @can('view_gallery')
                            <a href="{{ route('admin.galeri.index') }}" class="btn text-start home-item">
                                <i class="fa-duotone icon fa-images" style="color: #28a745"></i>
                                <div>
                                    <p class="fw-semibold m-0">Galeri Foto <i class="fa-regular fa-arrow-right"></i></p>
                                    <p class="m-0 text-secondary">Publikasi foto kegiatan </p>
                                </div>
                            </a>
                        @endcan

                        {{-- 4. Manajemen Kegiatan & Kalender --}}
                        @can('view_events')
                            <a href="{{ route('admin.kegiatan.index') }}" class="btn text-start home-item">
                                <i class="fa-duotone icon fa-calendar-days" style="color: #ffc107"></i>
                                <div>
                                    <p class="fw-semibold m-0">Manajemen Kegiatan <i class="fa-regular fa-arrow-right"></i>
                                    </p>
                                    <p class="m-0 text-secondary">Jadwal Kajian/Seminar </p>
                                </div>
                            </a>
                        @endcan
                    </div>
                </div>
            @endif

            {{-- Bagian Tengah: Keuangan (ZIS) --}}
            @if (Auth::user()->hasPermission('view_donation') ||
                    Auth::user()->hasPermission('view_expense') ||
                    Auth::user()->hasPermission('view_income') ||
                    Auth::user()->hasPermission('view_banks'))
                <div class="flex-grow-1" style="min-width: 300px;">
                    <p class="fw-bold mb-3 fs-14px text-success">Keuangan & Transparansi (ZIS)</p>
                    <div class="d-flex gap-3 mb-3 py-2 bg-light rounded-3 flex-wrap w-100">

                        {{-- 5. Verifikasi Konfirmasi Donasi --}}
                        @can('verify_donation')
                            <a href="{{ route('admin.donasi.index') }}" class="btn text-start home-item">
                                <i class="fa-duotone icon fa-money-check-dollar" style="color: #198754"></i>
                                <div>
                                    <p class="fw-semibold m-0">Verifikasi Donasi <i class="fa-regular fa-arrow-right"></i>
                                    </p>
                                    <p class="m-0 text-secondary">Cek Bukti Transfer Jamaah </p>
                                </div>
                            </a>
                        @endcan

                        {{-- 6. Manajemen Transaksi Keuangan (Pemasukan/Pengeluaran) --}}
                        @can('view_finance')
                            <a href="{{ route('admin.keuangan') }}" class="btn text-start home-item">
                                <i class="fa-duotone icon fa-chart-line-up" style="color: #6f42c1"></i>
                                <div>
                                    <p class="fw-semibold m-0">Manajemen Transaksi <i class="fa-regular fa-arrow-right"></i>
                                    </p>
                                    <p class="m-0 text-secondary">Input Pemasukan & Pengeluaran </p>
                                </div>
                            </a>
                        @endcan

                        {{-- 7. Kotak Amal --}}
                        @can('manage_income')
                            <a href="{{ route('admin.kotak-amal.index') }}" class="btn text-start home-item">
                                <i class="fa-duotone icon fa-box-dollar" style="color: #dc3545"></i>
                                <div>
                                    <p class="fw-semibold m-0">Pendataan Kotak Amal <i class="fa-regular fa-arrow-right"></i>
                                    </p>
                                    <p class="m-0 text-secondary">Input Hasil Kotak Amal </p>
                                </div>
                            </a>
                        @endcan

                        {{-- 8. Manajemen Rekening Bank --}}
                        @can('view_banks')
                            <a href="{{ route('admin.banks.index') }}" class="btn text-start home-item">
                                <i class="fa-duotone icon fa-building-columns" style="color: #0dcaf0"></i>
                                <div>
                                    <p class="fw-semibold m-0">Manajemen Rekening <i class="fa-regular fa-arrow-right"></i>
                                    </p>
                                    <p class="m-0 text-secondary">Atur Bank Zakat & Infak </p>
                                </div>
                            </a>
                        @endcan

                    </div>
                </div>
            @endif

            @if (Auth::user()->hasPermission('view_users') || Auth::user()->hasPermission('view_roles'))
                <div class="flex-grow-1" style="min-width: 300px;">
                    <p class="fw-bold mb-3 fs-14px text-danger">Pengguna Aplikasi</p>
                    <div class="d-flex gap-3 mb-3 py-2 bg-light rounded-3 flex-wrap w-100">


                        {{-- 8. Manajemen Pengguna (Admin/Super Admin) --}}
                        @can('view_users')
                            <a href="{{ route('admin.users.index') }}" class="btn text-start home-item">
                                <i class="fa-duotone icon fa-users-gear" style="color: darkred"></i>
                                <div>
                                    <p class="fw-semibold m-0">Manajemen Pengguna <i class="fa-regular fa-arrow-right"></i></p>
                                    <p class="m-0 text-secondary">Kelola Akun & Role (RBAC) </p>
                                </div>
                            </a>
                        @endcan

                        {{-- Role Management (Added back as it's important for Admin) --}}
                        @can('view_roles')
                            <a href="{{ route('admin.roles.index') }}" class="btn text-start home-item">
                                <i class="fa-duotone icon fa-shield-halved" style="color: darkred"></i>
                                <div>
                                    <p class="fw-semibold m-0">Role & Permission <i class="fa-regular fa-arrow-right"></i></p>
                                    <p class="m-0 text-secondary">Hak Akses </p>
                                </div>
                            </a>
                        @endcan

                    </div>
                </div>
            @endif

            @if (Auth::user()->hasPermission('view_lost_items') || Auth::user()->hasPermission('view_consultations'))
                {{-- Bagian Kanan: Layanan & Sistem --}}
                <div class="flex-grow-1" style="min-width: 300px;">
                    <p class="fw-bold mb-3 fs-14px text-danger">Pengguna & Layanan</p>
                    <div class="d-flex gap-3 mb-3 py-2 bg-light rounded-3 flex-wrap w-100">



                        {{-- 9. Lost & Found (Layanan Jamaah) --}}
                        @can('view_lost_items')
                            <a href="{{ route('admin.lost-items.index') }}" class="btn text-start home-item">
                                <i class="fa-duotone icon fa-box-open-full" style="color: #fd7e14"></i>
                                <div>
                                    <p class="fw-semibold m-0">Lost & Found <i class="fa-regular fa-arrow-right"></i></p>
                                    <p class="m-0 text-secondary">Kelola Laporan Barang Hilang </p>
                                </div>
                            </a>
                        @endcan

                        {{-- 10. Kotak Masuk Konsultasi --}}
                        @can('view_consultations')
                            <a href="{{ route('admin.consultations.index') }}" class="btn text-start home-item">
                                <i class="fa-duotone icon fa-comment-dots" style="color: #6610f2"></i>
                                <div>
                                    <p class="fw-semibold m-0">Kotak Masuk Konsultasi <i
                                            class="fa-regular fa-arrow-right"></i>
                                    </p>
                                    <p class="m-0 text-secondary">Tanggapi Formulir Jamaah </p>
                                </div>
                            </a>
                        @endcan
                    </div>
                </div>
            @endif
        </div>
    </section>
@endsection
