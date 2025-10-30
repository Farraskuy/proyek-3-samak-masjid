<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Digital Masjid')</title>

    {{-- Bootstrap CSS --}}
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap/bootstrap.min.css') }}">

    {{-- FontAwesome CSS --}}
    <link rel="stylesheet" href="{{ asset('assets/css/font-awesome/solid.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/font-awesome/regular.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/font-awesome/light.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/font-awesome/duotone.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/font-awesome/fontawesome.css') }}">

    {{-- Fonts --}}
    <link rel="stylesheet" href="{{ asset('assets/fonts/fonts.css') }}">

    <style>
        .footer-link {
            color: rgba(255, 255, 255, 0.5);
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .footer-link:hover {
            color: rgba(255, 255, 255, 1);
            padding-left: 5px;
        }

        .social-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: rgba(255, 255, 255, 0.1);
            color: #fff;
            font-size: 1.1rem;
            text-decoration: none;
            margin-right: 10px;
            transition: all 0.3s ease;
        }

        .social-icon:hover {
            background-color: #ffc107;
            color: #175C9E;
        }


        .article-card,
        .event-card {
            transition: all 0.3s ease;
            border: 0;
        }

        .card-link:hover .article-card,
        .card-link:hover .event-card {
            transform: translateY(-5px);
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, .15) !important;
        }

        .card-thumbnail-wrapper {
            position: relative;
            height: 200px;
            background-color: #e9ecef;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            border-radius: var(--bs-card-inner-border-radius);
        }

        .card-thumbnail-wrapper img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .card-thumbnail-wrapper .fallback-icon {
            display: none;
            font-size: 3rem;
            color: #adb5bd;
        }
    </style>

    <!-- Custom CSS -->
    @stack('styles')
</head>

<body>
    @include('components.navbar')

    <main class="flex-grow-1">
        @yield('content')
    </main>

    @include('components.footer')

    <!-- Bootstrap JS -->
    <script src="{{ asset('assets/js/bootstrapt/bootstrap.bundle.min.js') }}"></script>

    @stack('scripts')

    @stack('scripts')
</body>

</html>
