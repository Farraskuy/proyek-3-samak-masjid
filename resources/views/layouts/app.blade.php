<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Bootstrap CSS --}}
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap/bootstrap.min.css') }}">

    {{-- FontAwesome CSS --}}
    <link rel="stylesheet" href="{{ asset('assets/css/font-awesome/solid.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/font-awesome/regular.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/font-awesome/fontawesome.css') }}">

    {{-- Fonts --}}
    <link rel="stylesheet" href="{{ asset('assets/fonts/fonts.css') }}">

    {{-- Icon --}}
    <link rel="shortcut icon" href="{{ asset('assets/images/logo.png') }}" type="image/x-icon">

    {{-- Bootstrap JS --}}
    <script src="{{ asset('assets/js/bootstrapt/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.min.js') }}"></script>

    @stack('styles')

    <title>@yield('title', 'SAMAK Masjid')</title>

    <style>
        body {
            font-family: 'Poppins', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
    </style>
</head>
<body class="bg-light">
    {{-- Navbar --}}
    @include('components.navbar')

    {{-- Main Content --}}
    <main class="min-vh-100">
        @yield('content')
    </main>

    {{-- Footer --}}
    @include('components.footer')

    @stack('scripts')
</body>
</html>
