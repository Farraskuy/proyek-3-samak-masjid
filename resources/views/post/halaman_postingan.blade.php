<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>News Card Template</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- FontAwesome for icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
 
    <!-- js -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>


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






/* untuk style filter */
.filter-container {
    
    float: right; 
    
    display: flex;
    align-items: center; 
    margin-bottom: 20px; 
}

.filter-container label {
    margin-right: 10px; 
    font-size: 1.1em;
    font-weight: bold;
    color: #333;
}

.filter-container select {
 
    -webkit-appearance: none;
    -moz-appearance: none;
    appearance: none;


    font-size: 1em;
    padding: 8px 12px;
    border: none;
    border-bottom: 2px solid #333;
    background-color: transparent; 
    cursor: pointer;

    background-image: url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%22292.4%22%20height%3D%22292.4%22%3E%3Cpath%20fill%3D%22%23333333%22%20d%3D%22M287%2069.4a17.6%2017.6%200%200%200-13-5.4H18.4c-5%200-9.3%201.8-13%205.4A17.6%2017.6%200%200%200%200%2082.2c0%205%201.8%209.3%205.4%2013l128%20128c3.5%203.5%207.8%205.4%2013%205.4s9.5-1.8%2013-5.4l128-128c3.5-3.5%205.4-7.8%205.4-13%200-5-1.8-9.3-5.4-13z%22%2F%3E%3C%2Fsvg%3E');
    background-repeat: no-repeat;
    background-position: right 10px center;
    background-size: 12px;
    padding-right: 35px; 
}







    </style>
@endpush

@section('content')
    <!-- ini untuk body -->

</head>



<body>

<!-- <form method="GET"  class="mb-4">
    <input type="text" name="search" placeholder="Cari berita..."
           value="{{ request('search') }}" class="form-control">
    </form> -->


        
    <form action="/posts" method="get"></form>
        <div class="filter-container" >
            <label for="filter-select">filter</label>
            <select name="filter" id="filter-select" onchange="this.form.submit()">
                <option value="terbaru">Terbaru</option>
                <option value="terlama">Terlama</option>
                <option value="populer">Paling Populer</option>
            </select>
        </div>
    </form>
















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

        <!-- PAGINATION -->
    <div class="mt-4 d-flex justify-content-center">
        {{ $data_posts->links() }}
    </div>


</body>
</html>


@endsection
