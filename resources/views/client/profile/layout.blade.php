@extends('client.layout')

@section('title', 'Pengaturan Akun')

@push('styles')
    <style>
        .profile-wrapper {
            max-width: 1450px;
            margin: 0 auto;
        }

        .input-lg {
            padding: .85rem 1rem !important;
            font-size: .95rem !important;
        }

        .btn-primary-custom {
            background-color: #175C9E !important;
            color: white !important;
            border: none !important;
        }

        .btn-primary-custom:hover {
            background-color: #134a7f !important;
        }

        .file-uploader {
            padding: 2rem;
            border-radius: 1rem;
            border: 2px dashed #dee2e6;
            background: #fafafa;
            text-align: center;
            cursor: pointer;
            color: #666;
            transition: .2s ease-in-out;
            display: block !important;
        }

        .file-uploader:hover {
            background: #f1f1f1;
            border-color: #175C9E;
        }

        #image-preview-container {
            display: none;
            position: relative;
            width: 150px;
            height: 150px;
        }

        #image-preview {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 50%;
            border: 3px solid #fff;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        }

        #remove-image-btn {
            position: absolute;
            top: 0;
            right: 0;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            border: none;
            background: #dc3545;
            color: white;
            font-size: 14px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
        }
    </style>
@endpush

@section('content')
    <div class="min-vh-100 d-flex flex-column">
        <!-- Hero Section (Admin Style) -->
        <section class="py-5 bg-pattern" style="background-color: #175C9E; height: 250px; display:flex; align-items:center;">
            <div class="container-fluid px-5">
                <h1 class="display-5 fw-bold text-white mb-2">Profil Saya</h1>
                <p class="text-white-50 lead mb-0">Kelola informasi akun dan preferensi Anda</p>
            </div>
        </section>

        <div class="container-fluid px-5 py-5 flex-grow-1" style="margin-top: -50px;">
            <div class="row g-4">
                <!-- Sidebar -->
                @include('client.profile.partials.sidebar')

                <!-- Main Content -->
                <div class="col-lg-9">
                    @yield('profile-content')
                </div>
            </div>
        </div>
    </div>


@endsection
