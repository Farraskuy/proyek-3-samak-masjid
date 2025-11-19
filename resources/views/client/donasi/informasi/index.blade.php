@extends('client.layout')

@section('title', 'Zakat, Infaq, dan Sedekah - SAMAK-Kampus')

@section('content')
<style>
    /* === Warna & Tampilan Umum === */
    body {
        background-color: #f9fbfd;
    }
    .section-header {
        background: linear-gradient(135deg, #175C9E, #2B98F0);
        color: white;
        border-radius: 20px;
        padding: 60px 20px;
        box-shadow: 0 8px 20px rgba(23, 92, 158, 0.3);
    }
    .section-header h1 {
        font-weight: 700;
    }

    /* === Tombol Utama === */
    .btn-main {
        background: linear-gradient(90deg, #175C9E, #2B98F0);
        color: #fff !important;
        border: none;
        transition: 0.3s;
    }
    .btn-main:hover {
        background: linear-gradient(90deg, #2B98F0, #175C9E);
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(23, 92, 158, 0.3);
    }

    /* === Card Manfaat === */
    .manfaat-card {
        background: white;
        border: none;
        border-radius: 20px;
        padding: 25px;
        text-align: center;
        transition: all 0.3s ease;
        box-shadow: 0 3px 10px rgba(0, 0, 0, 0.05);
    }
    .manfaat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    }
    .icon-circle {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        margin: 0 auto 20px;
        color: white;
    }

</style>

<div class="container py-5">

    {{-- ===== Header Utama ===== --}}
    <div class="text-center section-header mb-5">
        <h1 class="display-5 mb-3">Zakat, Infaq, dan Sedekah</h1>
        <p class="lead col-lg-8 mx-auto">
            Setiap kebaikan yang kamu berikan menjadi harapan bagi yang membutuhkan.  
            Yuk wujudkan perubahan bersama dengan berbagi keberkahan!
        </p>
        <a href="/donasi/sekarang" class="btn btn-lg btn-main px-5 py-3 rounded-pill mt-3">
            Donasi Sekarang <i class="bi bi-heart ms-2"></i>
        </a>
    </div>

    {{-- ===== Daftar Manfaat ===== --}}
    <div class="text-center mb-5">
        <h2 class="fw-bold mb-4">Manfaat Zakat, Infaq, dan Sedekah</h2>
    </div>

    @php
        $manfaat = [
            ['judul' => 'Membersihkan Harta', 'deskripsi' => 'Menyucikan harta dan menjadikannya halal serta penuh berkah.', 'image' => 'harta.png', 'class' => 'icon-1'],
            ['judul' => 'Membersihkan Jiwa', 'deskripsi' => 'Menghilangkan sifat kikir dan menumbuhkan empati sosial.', 'image' => 'jiwa.png', 'class' => 'icon-2'],
            ['judul' => 'Menyempurnakan Ibadah', 'deskripsi' => 'Melengkapi rukun Islam dan mendekatkan diri kepada Allah.', 'image' => 'ibadah.png', 'class' => 'icon-3'],
            ['judul' => 'Melipatgandakan Rezeki', 'deskripsi' => 'Allah menjanjikan balasan berlipat bagi orang yang berinfaq.', 'image' => 'rezeki.png', 'class' => 'icon-4'],
            ['judul' => 'Menghapus Dosa', 'deskripsi' => 'Sedekah ikhlas dapat menggugurkan dosa sebagaimana air memadamkan api.', 'image' => 'dosa.png', 'class' => 'icon-5'],
            ['judul' => 'Menolak Bala', 'deskripsi' => 'Infaq menjadi perisai dari musibah dan keburukan.', 'image' => 'menolak.png', 'class' => 'icon-6'],
        ];
    @endphp

    <div class="row g-4">
        @foreach ($manfaat as $item)
            <div class="col-lg-4 col-md-6">
                <div class="manfaat-card">
                    <div class="icon-circle {{ $item['class'] }}">
                        <img src="{{ asset('assets/images/donasi/' . $item['image']) }}" alt="{{ $item['judul'] }}" style="width: 100%; height: 100%; object-fit: contain;"></i>
                    </div>
                    <h5 class="fw-bold text-dark">{{ $item['judul'] }}</h5>
                    <p class="text-secondary">{{ $item['deskripsi'] }}</p>
                </div>
            </div>
        @endforeach
    </div>

    {{-- ===== Konfirmasi Donasi ===== --}}
    <div class="my-5 py-5 text-center" style="background: #fff3cd; border-radius: 20px;">
        <h2 class="fw-bold text-dark mb-3">Sudah Transfer?</h2>
        <p class="lead text-muted col-lg-8 mx-auto mb-4">
            Upload bukti donasi kamu agar tim kami bisa memverifikasi dengan cepat dan transparan.
        </p>
        <a href="/donasi/konfirmasi" class="btn btn-lg fw-bold rounded-pill px-5 py-3" 
           style="background-color: #FFC107; color: #212529;">
            <i class="bi bi-upload me-2"></i> Upload Bukti Donasi
        </a>
    </div>
</div>
@endsection
