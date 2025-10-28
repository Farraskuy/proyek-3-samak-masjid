@extends('admin.layout')

@section('title', 'Home | Digital Masjid')
@section('content')
    <section class="p-5 position-relative">
        <div class="position-absolute w-100"
            style="height: 200px; background-color: #f9d428; z-index: -1; top: 0; left: 0; right: 0; "></div>
        <h1 class="fw-bold m-0 text-white">Selamat Datang di,</h1>
        <p class="fw-semibold">Dimas</p>
        <div class="rounded-3 bg-white p-3 border- shadow-sm d-flex flex-wrap" style="gap: 0 20px">
            <div class="flex-grow-1">
                <p class="fw-semibold mb-1 fs-14px">Menu Cepat</p>
                <div class="d-flex gap-3 mb-3 py-2 bg-light rounded-3 flex-wrap w-100" style="width: fit-content">
                    <a href="" class="btn text-start home-item">
                        <i class="fa-duotone icon fa-grid-2" style="color: blue"></i>
                        <div>
                            <p class="fw-semibold m-0">Dashboard <i class="fa-regular fa-arrow-right"></i></p>
                            <p class="m-0 text-secondary">Dahsboard Aplikasi</p>
                        </div>
                    </a>
                    <a href="" class="btn text-start home-item">
                        <i class="fa-duotone icon fa-cash-register" style="color: blue"></i>
                        <div>
                            <p class="fw-semibold m-0">Kasir <i class="fa-regular fa-arrow-right"></i></p>
                            <p class="m-0 text-secondary">Kasir Penjualan</p>
                        </div>
                    </a>
                </div>
            </div>
            <div class="flex-grow-1">
                <p class="fw-semibold mb-1 fs-14px">Menu Pengguna</p>
                <div class="d-flex gap-3 mb-3 py-2 bg-light rounded-3 flex-wrap w-100" style="width: fit-content">
                    <a href="" class="btn text-start home-item">
                        <i class="fa-duotone icon fa-user" style="color: darkred"></i>
                        <div>
                            <p class="fw-semibold m-0">Pengguna <i class="fa-regular fa-arrow-right"></i></p>
                            <p class="m-0 text-secondary">Daftar Pengguna Aplikasi</p>
                        </div>
                    </a>
                    <a href="" class="btn text-start home-item">
                        <i class="fa-duotone icon fa-shield-halved" style="color: darkred"></i>
                        <div>
                            <p class="fw-semibold m-0">Role <i class="fa-regular fa-arrow-right"></i></p>
                            <p class="m-0 text-secondary">Daftar Hak Akses Pengguna</p>
                        </div>
                    </a>
                </div>
            </div>
            <div class="flex-grow-1">
                <p class="fw-semibold mb-1 fs-14px">Menu Barang</p>
                <div class="d-flex gap-3 mb-3 py-2 bg-light rounded-3 flex-wrap w-100" style="width: fit-content">
                    <a href="" class="btn text-start home-item">
                        <i class="fa-duotone icon fa-box" style="color: purple"></i>
                        <div>
                            <p class="fw-semibold m-0">Data Barang <i class="fa-regular fa-arrow-right"></i></p>
                            <p class="m-0 text-secondary">Daftar Barang-barang</p>
                        </div>
                    </a>
                    <a href="" class="btn text-start home-item">
                        <i class="fa-duotone icon fa-hashtag" style="color: purple"></i>
                        <div>
                            <p class="fw-semibold m-0">Data Kategori <i class="fa-regular fa-arrow-right"></i></p>
                            <p class="m-0 text-secondary">Daftar Kategori</p>
                        </div>
                    </a>
                    <a href="" class="btn text-start home-item">
                        <i class="fa-duotone icon fa-user-tag" style="color: purple"></i>
                        <div>
                            <p class="fw-semibold m-0">Data Supplier <i class="fa-regular fa-arrow-right"></i></p>
                            <p class="m-0 text-secondary">Daftar Supplier</p>
                        </div>
                    </a>
                </div>
            </div>


            <div class="flex-grow-1">
                <p class="fw-semibold mb-1 fs-14px">Menu Transaksi/Pembelian</p>
                <div class="d-flex gap-3 mb-3 py-2 bg-light rounded-3 flex-wrap w-100" style="width: fit-content">
                    <a href="" class="btn text-start home-item">
                        <i class="fa-duotone icon fa-file-invoice" style="color: green"></i>
                        <div>
                            <p class="fw-semibold m-0">Data Pembelian <i class="fa-regular fa-arrow-right"></i></p>
                            <p class="m-0 text-secondary">Daftar Histori Pembelian</p>
                        </div>
                    </a>
                    <a href="" class="btn text-start home-item">
                        <i class="fa-duotone icon fa-cart-arrow-down" style="color: green"></i>
                        <div>
                            <p class="fw-semibold m-0">Transaksi Pembelian <i class="fa-regular fa-arrow-right"></i></p>
                            <p class="m-0 text-secondary">Transaksi Pembelian</p>
                        </div>
                    </a>
                </div>
            </div>

            <div class="flex-grow-1">
                <p class="fw-semibold mb-1 fs-14px">Menu Transaksi/Penjualan</p>
                <div class="d-flex gap-3 mb-3 py-2 bg-light rounded-3 flex-wrap w-100" style="width: fit-content">
                    <a href="" class="btn text-start home-item">
                        <i class="fa-duotone icon fa-file-invoice" style="color: green"></i>
                        <div>
                            <p class="fw-semibold m-0">Data Penjualan <i class="fa-regular fa-arrow-right"></i></p>
                            <p class="m-0 text-secondary">Daftar Histori Penjualan</p>
                        </div>
                    </a>
                    <a href="" class="btn text-start home-item">
                        <i class="fa-duotone icon fa-cart-arrow-down" style="color: green"></i>
                        <div>
                            <p class="fw-semibold m-0">Transaksi Penjualan <i class="fa-regular fa-arrow-right"></i></p>
                            <p class="m-0 text-secondary">Transaksi Penjualan</p>
                        </div>
                    </a>
                </div>
            </div>
            <div class="flex-grow-1">
                <p class="fw-semibold mb-1 fs-14px">Menu Laporan</p>
                <div class="d-flex gap-3 mb-3 py-2 bg-light rounded-3 flex-wrap w-100" style="width: fit-content">
                    <a href="" class="btn text-start home-item">
                        <i class="fa-duotone icon fa-file-invoice-dollar" style="color: orange"></i>
                        <div>
                            <p class="fw-semibold m-0">Laporan Penjualan <i class="fa-regular fa-arrow-right"></i></p>
                            <p class="m-0 text-secondary">Membuat Laporan Penjualan</p>
                        </div>
                    </a>
                </div>
            </div>
    </section>
@endsection
