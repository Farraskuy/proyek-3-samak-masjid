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

    <link rel="stylesheet" href="{{ asset('assets/css/auth.css') }}">
    <!-- Custom CSS -->
    @stack('styles')
</head>

<body>
    <img src="{{ asset("assets/images/auth-bg.png") }}" alt="Background" class="position-absolute w-100 h-100" style="filter: brightness(0.8); object-fit: cover; z-index: -1;">

    <div class="container d-flex justify-content-center h-100">
        @yield('content')
    </div>

    <script src="{{ asset("assets/js/bootstrapt/bootstrap.bundle.min.js") }}"></script>
    @stack('scripts')
</body>

</html>
