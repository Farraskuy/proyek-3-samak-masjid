@extends('admin.layout')

@section('title', 'Dashboard | SAMAK-Kampus')

{{-- Tambahkan styling custom untuk ikon fa-duotone jika diperlukan --}}
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
        <h1 class="fw-bold m-0 text-white">Selamat Datang di Dashboard Admin,</h1>
        <p class="fw-semibold text-white">SAMAK-Kampus (Sistem Aplikasi Masjid Kampus)</p>

        {{-- Konten Utama Menu Dashboard --}}
        <div class="rounded-3 bg-white p-4 border- shadow-sm d-flex flex-wrap" style="gap: 20px;">

            {{-- Bagian Kiri: Manajemen Konten & Kegiatan --}}
            <div class="flex-grow-1" style="min-width: 300px;">
                <p class="fw-bold mb-3 fs-14px text-primary">Konten & Kegiatan Masjid</p>
                <div class="d-flex gap-3 mb-3 py-2 bg-light rounded-3 flex-wrap w-100">

                    {{-- 1. Manajemen Artikel & Berita (CMS) --}}
                    <a href="{{ url('/admin/artikel') }}" class="btn text-start home-item">
                        <i class="fa-duotone icon fa-newspaper" style="color: #007bff"></i>
                        <div>
                            <p class="fw-semibold m-0">Manajemen Berita <i class="fa-regular fa-arrow-right"></i></p>
                            <p class="m-0 text-secondary">CRUD Artikel/Tausiyah </p>
                        </div>
                    </a>

                    {{-- 2. Halaman Statis (Tentang Kami, dll) --}}
                    <a href="{{ url('/admin/halaman-statis') }}" class="btn text-start home-item">
                        <i class="fa-duotone icon fa-file-lines" style="color: #6c757d"></i>
                        <div>
                            <p class="fw-semibold m-0">Halaman Statis <i class="fa-regular fa-arrow-right"></i></p>
                            <p class="m-0 text-secondary">Visi Misi, Struktur DKM </p>
                        </div>
                    </a>

                    {{-- 3. Manajemen Galeri Foto --}}
                    <a href="{{ url('/admin/galeri/') }}" class="btn text-start home-item">
                        <i class="fa-duotone icon fa-images" style="color: #28a745"></i>
                        <div>
                            <p class="fw-semibold m-0">Galeri Foto <i class="fa-regular fa-arrow-right"></i></p>
                            <p class="m-0 text-secondary">Publikasi foto kegiatan </p>
                        </div>
                    </a>

                    {{-- 4. Manajemen Kegiatan & Kalender --}}
                    <a href="{{ url('/admin/kegiatan') }}" class="btn text-start home-item">
                        <i class="fa-duotone icon fa-calendar-days" style="color: #ffc107"></i>
                        <div>
                            <p class="fw-semibold m-0">Manajemen Kegiatan <i class="fa-regular fa-arrow-right"></i></p>
                            <p class="m-0 text-secondary">Jadwal Kajian/Seminar </p>
                        </div>
                    </a>
                </div>
            </div>

            {{-- Bagian Tengah: Keuangan (ZIS) --}}
            <div class="flex-grow-1" style="min-width: 300px;">
                <p class="fw-bold mb-3 fs-14px text-success">Keuangan & Transparansi (ZIS)</p>
                <div class="d-flex gap-3 mb-3 py-2 bg-light rounded-3 flex-wrap w-100">

                    {{-- 5. Verifikasi Konfirmasi Donasi --}}
                    <a href="{{ url('/admin/donasi/verifikasi') }}" class="btn text-start home-item">
                        <i class="fa-duotone icon fa-money-check-dollar" style="color: #198754"></i>
                        <div>
                            <p class="fw-semibold m-0">Verifikasi Donasi <i class="fa-regular fa-arrow-right"></i></p>
                            <p class="m-0 text-secondary">Cek Bukti Transfer Jamaah </p>
                        </div>
                    </a>

                    {{-- 6. Manajemen Transaksi Keuangan (Pemasukan/Pengeluaran) --}}
                    <a href="{{ url('/admin/keuangan') }}" class="btn text-start home-item">
                        <i class="fa-duotone icon fa-chart-line-up" style="color: #6f42c1"></i>
                        <div>
                            <p class="fw-semibold m-0">Manajemen Transaksi <i class="fa-regular fa-arrow-right"></i></p>
                            <p class="m-0 text-secondary">Input Pemasukan & Pengeluaran </p>
                        </div>
                    </a>

                    {{-- 7. Pendaftaran Kegiatan (Verifikasi Peserta) --}}
                    <a href="{{ url('/admin/kajian/') }}" class="btn text-start home-item">
                        <i class="fa-duotone icon fa-user-check" style="color: #dc3545"></i>
                        <div>
                            <p class="fw-semibold m-0">Verifikasi Pendaftar <i class="fa-regular fa-arrow-right"></i></p>
                            <p class="m-0 text-secondary">Daftar Pendaftaran Kajian </p>
                        </div>
                    </a>

                </div>
            </div>

            {{-- Bagian Kanan: Layanan & Sistem --}}
            <div class="flex-grow-1" style="min-width: 300px;">
                <p class="fw-bold mb-3 fs-14px text-danger">Pengguna & Layanan</p>
                <div class="d-flex gap-3 mb-3 py-2 bg-light rounded-3 flex-wrap w-100">

                    {{-- 8. Manajemen Pengguna (Admin/Super Admin) --}}
                    <a href="{{ url('/admin/pengguna') }}" class="btn text-start home-item">
                        <i class="fa-duotone icon fa-users-gear" style="color: darkred"></i>
                        <div>
                            <p class="fw-semibold m-0">Manajemen Pengguna <i class="fa-regular fa-arrow-right"></i></p>
                            <p class="m-0 text-secondary">Kelola Akun & Role (RBAC) </p>
                        </div>
                    </a>

                    {{-- 9. Lost & Found (Layanan Jamaah) --}}
                    <a href="{{ url('/admin/barang-hilang') }}" class="btn text-start home-item">
                        <i class="fa-duotone icon fa-box-open-full" style="color: #fd7e14"></i>
                        <div>
                            <p class="fw-semibold m-0">Lost & Found <i class="fa-regular fa-arrow-right"></i></p>
                            <p class="m-0 text-secondary">Kelola Laporan Barang Hilang </p>
                        </div>
                    </a>

                    {{-- 10. Kotak Masuk Konsultasi --}}
                    <a href="{{ url('/admin/konsultasi') }}" class="btn text-start home-item">
                        <i class="fa-duotone icon fa-comment-dots" style="color: #6610f2"></i>
                        <div>
                            <p class="fw-semibold m-0">Kotak Masuk Konsultasi <i class="fa-regular fa-arrow-right"></i></p>
                            <p class="m-0 text-secondary">Tanggapi Formulir Jamaah </p>
                        </div>
                    </a>
                </div>
            </div>

        </div>
    </section>
@endsection
