<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>News Card Template</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- FontAwesome for icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    

@extends('client.layout')

@section('title', 'Beranda - SAMAK-Kampus')

@push('styles')
    <style>
        * {
            font-family: 'Poppins', "Lexend", Geneva, Verdana, sans-serif;
        }

      .card {
            border: 1px solid #ddd;
            border-radius: 5px;
            overflow: hidden;
            margin-bottom: 20px;
            transition: box-shadow 0.2s;
        }

        .card:hover {
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .news-picture {
            width: 100%;
            height: 180px;
            background-size: cover;
            background-position: center;
            background-color: #ccc; /* fallback color */
        }

        .card-body.card-news {
            padding: 15px;
        }

        .card-title {
            font-size: 1.2rem;
            margin: 0;
        }

        .card-footer.no-bg {
            background: transparent;
            padding: 5px 15px;
            font-size: 0.9rem;
        }

        .text-72 {
            font-size: 0.9rem;
            margin: 0;
        }
        .card a {
        text-decoration: none; /* hilangkan garis bawah */
        color: inherit; /* biar warnanya ikut teks aslinya */
        }

        .card a:hover {
        text-decoration: none; /* tetap tanpa garis bawah saat hover */
        }



        .bg-pattern {
            background-image: radial-gradient(circle at 2px 2px, rgba(255, 255, 255, 0.5) 1px, transparent 1px);
            background-size: 100% 100%;
        }

        .feature-card:hover {
            border-color: #2dd4bf !important;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
        }

        .btn-amber {
            background-color: #f59e0b;
            border-color: #f59e0b;
            color: white;
        }

        .btn-amber:hover {
            background-color: #d97706;
            border-color: #d97706;
        }

        .feature-icon {
            width: 3rem;
            height: 3rem;
        }

        .feature-card {
            transition: all 0.3s ease-in-out;
        }

        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 1rem 3rem rgba(0, 0, 0, 0.175) !important;
        }
    </style>
@endpush

@section('content')
    <!-- ini untuk body -->


</head>
<body>
    <div class="container mt-4">
        <div class="row">
          
          
          @foreach ($data_posts as $row)
            <!-- Card Start -->
            <div class="col-12 col-sm-6 col-lg-4">
                <div class="card">
                    <a href= "{{   url('postingan' , [$row->slug]) }}">
                        <!-- Gambar Thumbnail -->
                        <div class="news-picture" style="background-image: url('{{ asset('storage/' . $row->featured_image_url) }}');">
                        </div>

                        <!-- Judul Berita -->
                        <div class="card-body card-news">
                            <h3 class="card-title">
                                <strong>{{  $row->title }}</strong>
                            </h3>

                            <!-- Keterangan / Deskripsi Singkat (ditempatkan di bawah judul) -->
                            <p class="card-text text-muted">
                                {{ $row->keterangan  }}
                            </p>
                            <!-- Akhir keterangan -->
                        </div>

                        <!-- Footer: Tanggal & Kategori -->
                        <div class="card-footer no-bg">
                            <div class="row">
                                <div class="col-6">
                                    <h5 class="text-72">
                                        <span class="fa fa-calendar-alt text-primary"></span>
                                        {{$row->created_at}}
                                    </h5>
                                </div>
                                <div class="col-6 text-end">
                                    <h5 class="text-72">
                                        <span class="text-primary">{{$row->kategori}}</span>
                                    </h5>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
                      @endforeach
            <!-- Card End -->

        </div>
    </div>
</body>
</html>


@endsection
