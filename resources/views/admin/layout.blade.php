<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- bootstrap --}}
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap/bootstrap.min.css') }}">
    
    {{-- bootstrap js --}}
    <script src="{{ asset('assets/js/bootstrapt/bootstrap.bundle.min.js') }}"></script>

    {{-- FontAwesome CSS --}}
    <link rel="stylesheet" href="{{ asset('assets/css/font-awesome/solid.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/font-awesome/regular.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/font-awesome/light.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/font-awesome/duotone.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/font-awesome/fontawesome.css') }}">

    {{-- Fonts --}}
    <link rel="stylesheet" href="{{ asset('assets/fonts/fonts.css') }}">

    {{-- icon --}}
    <link rel="shortcut icon" href="{{ asset('assets/image') }}/logo.png" type="image/x-icon">

    {{-- style --}}
    <link rel="stylesheet" href="{{ asset('assets/css/admin.css') }}">

    <script>
        const baseurl = '{{ url('/') }}';
        const csrf = '{{ csrf_token() }}';
        const currenturl = '{{ url()->current() }}';
    </script>

    {{-- Jquery --}}
    <script type="text/javascript" src="{{ asset('assets/js') }}/jquery.min.js"></script>

    {{-- Date range picker --}}
    <script type="text/javascript" src="{{ asset('assets/js') }}/moment.min.js"></script>
    <script type="text/javascript" src="{{ asset('assets/js') }}/daterangepicker.js"></script>
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css') }}/daterangepicker.css"/>

    {{-- Chart js --}}
    <script src="{{ asset('assets/js') }}/chart.umd.min.js"></script>

    {{-- Sweet Alert --}}
    <script src="{{ asset('assets/js') }}/sweetalert2.js"></script>
    <script>
        const Toast = Swal.mixin({
            toast: true,
            position: "top-end",
            showConfirmButton: false,
            timer: 5000,
            width: '33em',
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.onmouseenter = Swal.stopTimer;
                toast.onmouseleave = Swal.resumeTimer;
            },
            customClass: {
                htmlContainer: 'my-0',
            }
        });
    </script>

    @stack('styles')

    <title>@yield('title')</title>
</head>

<body class="bg-light d-flex overflow-hidden"
    style="font-family: Poppins, 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">

    <div class="main w-100 active position-relative">

        @include('components.sidebar-admin')

        <main class="w-100 overflow-y-scroll" style="height: 100vh">

            @include('components.navbar-admin')

            <div class="modal fade" id="logout" tabindex="-1" aria-hidden="true">
                <form action="{{ route('logout') }}" method="POST" class="modal-dialog modal-dialog-centered">
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header">
                            <p class="modal-title fw-semibold" id="exampleModalLabel">Konfirmasi Logout</p>
                        </div>
                        <div class="modal-body">
                            <p class="mb-0 fs-15px">Apakah anda yain ingin logout?</p>
                        </div>
                        <div class="modal-footer">
                            <div class="d-flex gap-1 justify-content-end">
                                <button type="button" class="fw-semibold btn btn-sm btn-secondary"
                                    data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="fw-semibold btn btn-sm btn-danger"><i
                                        class="fa-regular fa-right-from-bracket"></i> Ya Keluar</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Reusable Delete Confirmation Modal (used by admin index pages) -->
            <div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-hidden="true">
                <form id="confirmDeleteForm" method="POST" class="modal-dialog modal-dialog-centered">
                    @csrf
                    @method('DELETE')
                    <div class="modal-content">
                        <div class="modal-header">
                            <p class="modal-title fw-semibold">Konfirmasi Hapus</p>
                        </div>
                        <div class="modal-body">
                            <p class="mb-0 fs-15px">Apakah anda yakin ingin menghapus data ini? Tindakan ini tidak dapat dibatalkan.</p>
                        </div>
                        <div class="modal-footer">
                            <div class="d-flex gap-1 justify-content-end">
                                <button type="button" class="fw-semibold btn btn-sm btn-secondary" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="fw-semibold btn btn-sm btn-danger">Ya, Hapus</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            @yield('content')
        </main>
    </div>

    {{-- development test clockwork --}}
    {{-- <script src="https://cdn.jsdelivr.net/gh/underground-works/clockwork-browser@1/dist/toolbar.js"></script> --}}


    {{-- Auto Show Alerts --}}
    <script>
        @if (session('success'))
            Toast.fire({
                icon: 'success',
                title: @json(session('success'))
            });
        @endif

        @if (session('error'))
            Toast.fire({
                icon: 'error',
                title: @json(session('error'))
            });
        @endif

        @if (session('warning'))
            Toast.fire({
                icon: 'warning',
                title: @json(session('warning'))
            });
        @endif

        @if (session('info'))
            Toast.fire({
                icon: 'info',
                title: @json(session('info'))
            });
        @endif

        // Jika ada error validasi (multiple errors)
        @if ($errors->any())
            Toast.fire({
                icon: 'error',
                title: @json($errors->first()) // tampilkan error pertama
            });
        @endif
    </script>

    {{-- custom script --}}
    <script src="{{ asset('assets/js') }}/admin.js"></script>

    <script>
        // Attach delete modal behaviour: any .btn-delete-article sets form action and shows modal
        document.addEventListener('DOMContentLoaded', function () {
            var deleteButtons = document.querySelectorAll('.btn-delete-article');
            deleteButtons.forEach(function (btn) {
                btn.addEventListener('click', function (e) {
                    var action = btn.getAttribute('data-action');
                    var form = document.getElementById('confirmDeleteForm');
                    if (form) {
                        form.action = action;
                        var modalEl = document.getElementById('confirmDeleteModal');
                        var modal = new bootstrap.Modal(modalEl);
                        modal.show();
                    }
                });
            });
        });
    </script>

    @stack('scripts')
</body>

</html>
