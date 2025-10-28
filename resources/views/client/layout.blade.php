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
        .bg-pattern {
            background-image: radial-gradient(circle at 2px 2px, rgba(255, 255, 255, 0.5) 1px, transparent 1px);
            background-size: 40px 40px;
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
    <script src="{{ asset("assets/js/bootstrapt/bootstrap.bundle.min.js") }}"></script>

    @stack('scripts')

    @stack('scripts')
</body>

</html>
