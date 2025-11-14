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
    <img src="{{ asset('assets/images/auth-bg.png') }}" alt="Background" class="position-absolute w-100 h-100"
        style="filter: brightness(0.8); object-fit: cover; z-index: -1;">

    <div class="container d-flex justify-content-center h-100">
        @yield('content')
    </div>

    <script src="{{ asset('assets/js/bootstrapt/bootstrap.bundle.min.js') }}"></script>
    @stack('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const passwordInput = document.querySelectorAll('input[type="password"]');
            const togglePassword = document.createElement('div');
            togglePassword.innerHTML = '<i class="fa-regular fa-eye-slash"></i>';
            togglePassword.classList.add('position-absolute', 'bottom-0', 'end-0', 'p-4', 'py-3', 'text-muted');
            togglePassword.style.cursor = 'pointer';

            togglePassword.addEventListener('click', function() {
                passwordInput.forEach(function(input) {
                    if (input.type === 'password') {
                        input.type = 'text';
                        togglePassword.innerHTML = '<i class="fa-regular fa-eye"></i>';
                    } else {
                        input.type = 'password';
                        togglePassword.innerHTML = '<i class="fa-regular fa-eye-slash"></i>';
                    }
                });
            });

            passwordInput.forEach(function(input) {
                input.parentElement.prepend(togglePassword);
            });
        });
    </script>
</body>

</html>
