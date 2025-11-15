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
            // Password toggle functionality
            const passwordInput = document.querySelectorAll('input[type="password"]');
            passwordInput.forEach(function(input) {
                const togglePassword = document.createElement('div');
                togglePassword.innerHTML = '<i class="fa-regular fa-eye-slash"></i>';
                togglePassword.classList.add('position-absolute', 'bottom-0', 'end-0', 'p-4', 'py-3', 'text-muted');
                togglePassword.style.cursor = 'pointer';

                togglePassword.addEventListener('click', function(e) {
                    if (input.type === 'password') {
                        input.type = 'text';
                        togglePassword.innerHTML = '<i class="fa-regular fa-eye"></i>';
                    } else {
                        input.type = 'password';
                        togglePassword.innerHTML = '<i class="fa-regular fa-eye-slash"></i>';
                    }
                });

                input.parentElement.prepend(togglePassword);
            });

            // Scroll to bottom functionality for overflow-auto form containers
            const scrollBtnContainer = document.body;
            const scrollBtn = document.createElement('button');
            scrollBtn.className = 'scroll-to-bottom-btn';
            scrollBtn.innerHTML = '<i class="fa-solid fa-chevron-down"></i>';
            scrollBtn.setAttribute('type', 'button');
            scrollBtn.setAttribute('aria-label', 'Scroll ke bawah form');
            scrollBtnContainer.appendChild(scrollBtn);

            const formCard = document.querySelector('.overflow-auto');
            if (formCard) {
                // Check if form is scrollable
                function updateScrollButtonVisibility() {
                    if (formCard.scrollHeight > formCard.clientHeight) {
                        const isAtBottom = formCard.scrollHeight - formCard.scrollTop <= formCard.clientHeight + 10;
                        if (!isAtBottom) {
                            scrollBtn.classList.add('show');
                        } else {
                            scrollBtn.classList.remove('show');
                        }
                    } else {
                        scrollBtn.classList.remove('show');
                    }
                }

                // Listen to scroll events
                formCard.addEventListener('scroll', updateScrollButtonVisibility);
                window.addEventListener('resize', updateScrollButtonVisibility);

                // Click handler: scroll to bottom smoothly
                scrollBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    formCard.scrollTo({
                        top: formCard.scrollHeight,
                        behavior: 'smooth'
                    });
                });

                // Initial check
                updateScrollButtonVisibility();
            }
        });
    </script>
</body>

</html>
