@extends('client.layout')

@section('title', 'Zakat, Infaq, dan Sedekah - SAMAK-Kampus')

@push('styles')
    <style>
        .bg-pattern {
            background-image: radial-gradient(circle at 1px 1px, rgba(255, 255, 255, 0.15) 1px, transparent 1px);
            background-size: 25px 25px;
        }

        [data-aos] {
            transition-property: transform, opacity !important;
        }

        .feature-card {
            transition: .25s ease-in-out;
            background: white;
            border: none;
        }

        .feature-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 1.2rem 3rem rgba(0, 0, 0, .15) !important;
        }

        .icon-wrapper {
            width: 60px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
            margin-bottom: 1.5rem;
        }

        .cta-box {
            background-color: #fff3cd;
            border-radius: 20px;
            transition: transform 0.3s;
        }

        .cta-box:hover {
            transform: scale(1.01);
            box-shadow: 0 10px 30px rgba(255, 193, 7, 0.2);
        }
    </style>
@endpush

@section('content')

    <section class="py-5 bg-pattern" style="background-color: #175C9E; height: 320px; display: flex; align-items: center;">
        <div class="container text-center">
            <h1 class="display-5 fw-bold text-white mb-3" data-aos="fade-up" data-aos-duration="900">
                Zakat, Infaq, dan <span class="text-warning">Sedekah</span>
            </h1>

            <p class="lead text-white-50 mb-4 col-lg-8 mx-auto" data-aos="fade-up" data-aos-duration="1100"
                data-aos-delay="200">
                Setiap kebaikan yang kamu berikan menjadi harapan bagi yang membutuhkan.
                Yuk wujudkan perubahan bersama dengan berbagi keberkahan!
            </p>

                <div data-aos="fade-up" data-aos-delay="400">
                    <a href="/donasi/sekarang" class="btn btn-lg btn-light text-primary fw-bold px-5 py-3 rounded-pill shadow-sm">
                        Donasi Sekarang
                    </a>
                </div>
            </div>

        </div>
    </div>
</section>

<section class="py-5 bg-light">
    <div class="container">
        <div class="cta-box p-5 text-center mx-auto col-lg-10" 
             data-aos="zoom-in" data-aos-duration="900">
            
            <div class="mb-4">
                <i class="fas fa-receipt fa-3x text-warning mb-3"></i>
                <h2 class="fw-bold text-dark">Sudah Melakukan Transfer?</h2>
                <p class="lead text-muted col-lg-9 mx-auto">
                    Upload bukti donasi kamu agar tim kami bisa memverifikasi dengan cepat, transparan, dan amanah.
                </p>
            </div>

            <a href="/donasi/konfirmasi" class="btn btn-lg rounded-pill px-5 py-3 fw-bold shadow-sm" 
               style="background-color: #FFC107; color: #212529; border: none;">
                <i class="fas fa-upload me-2"></i> Upload Bukti Donasi
            </a>
        </div>
    </div>
</section>

    <section class="py-5 px-4 bg-white">
        <div class="container-xl">
            <div class="text-center mb-5" data-aos="fade-up">
                <h2 class="fw-bold text-dark mb-2">Manfaat Berbagi</h2>
                <p class="text-muted col-lg-8 mx-auto">Keutamaan menunaikan Zakat, Infaq, dan Sedekah bagi kehidupan</p>
            </div>

            @php
                $manfaat = [
                    [
                        'judul' => 'Membersihkan Harta',
                        'deskripsi' => 'Menyucikan harta dan menjadikannya halal serta penuh berkah.',
                        'image' => 'harta.png',
                        'bg' => 'linear-gradient(to bottom right, #0d6efd, #0dcaf0)',
                    ],
                    [
                        'judul' => 'Membersihkan Jiwa',
                        'deskripsi' => 'Menghilangkan sifat kikir dan menumbuhkan empati sosial.',
                        'image' => 'jiwa.png',
                        'bg' => 'linear-gradient(to bottom right, #198754, #20c997)',
                    ],
                    [
                        'judul' => 'Menyempurnakan Ibadah',
                        'deskripsi' => 'Melengkapi rukun Islam dan mendekatkan diri kepada Allah.',
                        'image' => 'ibadah.png',
                        'bg' => 'linear-gradient(to bottom right, #ffc107, #fd7e14)',
                    ],
                    [
                        'judul' => 'Melipatgandakan Rezeki',
                        'deskripsi' => 'Allah menjanjikan balasan berlipat bagi orang yang berinfaq.',
                        'image' => 'rezeki.png',
                        'bg' => 'linear-gradient(to bottom right, #6f42c1, #d63384)',
                    ],
                    [
                        'judul' => 'Menghapus Dosa',
                        'deskripsi' => 'Sedekah ikhlas dapat menggugurkan dosa sebagaimana air memadamkan api.',
                        'image' => 'dosa.png',
                        'bg' => 'linear-gradient(to bottom right, #dc3545, #fd7e14)',
                    ],
                    [
                        'judul' => 'Menolak Bala',
                        'deskripsi' => 'Infaq menjadi perisai dari musibah dan keburukan.',
                        'image' => 'menolak.png',
                        'bg' => 'linear-gradient(to bottom right, #0dcaf0, #3d8bfd)',
                    ],
                ];
            @endphp

            <div class="row g-4 justify-content-center">
                @foreach ($manfaat as $index => $item)
                    <div class="col-12 col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="{{ 100 * ($index + 1) }}"
                        data-aos-duration="800">

                        <div class="card h-100 feature-card rounded-4 shadow-sm">
                            <div class="card-body p-4 text-center text-md-start">
                                <div class="icon-wrapper shadow-sm mx-auto mx-md-0"
                                    style="background-image: {{ $item['bg'] }};">
                                    <img src="{{ asset('assets/images/donasi/' . $item['image']) }}"
                                        alt="{{ $item['judul'] }}"
                                        style="width: 35px; height: 35px; object-fit: contain; filter: brightness(0) invert(1);">
                                </div>

                                <h3 class="h5 fw-bold text-dark mb-2">{{ $item['judul'] }}</h3>
                                <p class="small text-muted mb-0">{{ $item['deskripsi'] }}</p>
                            </div>
                        </div>

                </div>
            @endforeach
        </div>
    </div>
</section>

@endsection
