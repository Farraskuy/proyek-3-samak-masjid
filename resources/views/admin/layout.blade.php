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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/brands.min.css">

    {{-- Fonts --}}
    <link rel="stylesheet" href="{{ asset('assets/fonts/fonts.css') }}">

    {{-- icon --}}
    <link rel="shortcut icon" href="{{ asset('assets/images/logo.ico') }}" type="image/x-icon">

    {{-- style --}}
    <link rel="stylesheet" href="{{ asset('assets/css/admin.css') }}">

    <script src="https://js.pusher.com/8.3.0/pusher.min.js"></script>

    <script>
        const baseurl = '{{ url(' / ') }}';
        const csrf = '{{ csrf_token() }}';
        const currenturl = '{{ url()->current() }}';
    </script>

    {{-- Jquery --}}
    <script type="text/javascript" src="{{ asset('assets/js') }}/jquery.min.js"></script>

    {{-- Date range picker --}}
    <script type="text/javascript" src="{{ asset('assets/js') }}/moment.min.js"></script>
    <script type="text/javascript" src="{{ asset('assets/js') }}/daterangepicker.js"></script>
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css') }}/daterangepicker.css" />

    {{-- Chart js --}}
    <script src="{{ asset('assets/js') }}/chart.umd.min.js"></script>

    {{-- Sweet Alert --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        const Toast = Swal.mixin({
            toast: true,
            position: "top-end",
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
        });
    </script>

    @stack('styles')

    <title>@yield('title', 'Dashboard') - Samak Masjid</title>
</head>

<body class="bg-light d-flex overflow-hidden"
    style="font-family: Poppins, 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">

    <div class="main w-100 active position-relative">

        @include('components.sidebar-admin')

        <main class="w-100 overflow-y-scroll" style="height: 100vh">

            @include('components.navbar-admin')



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
                title: @json($errors->first())
            });
        @endif
    </script>

    {{-- custom script --}}
    <script src="{{ asset('assets/js') }}/admin.js"></script>

    <script>
        /**
         * Show confirmation modal with customizable options
         * @param {Object} options - Modal configuration
         * @param {string} options.action - Form action URL
         * @param {string} options.method - HTTP method (POST, DELETE, PUT, etc.)
         * @param {string} options.type - Modal type: 'delete', 'warning', 'success', 'info', 'start', 'end'
         * @param {string} options.title - Modal title
         * @param {string} options.message - Modal message
         * @param {string} options.buttonText - Confirm button text
         */
        function showConfirmModal(options) {
            const form = document.getElementById('confirmActionForm');
            const methodInput = document.getElementById('confirmActionMethod');
            const icon = document.getElementById('confirmModalIcon');
            const title = document.getElementById('confirmModalTitle');
            const message = document.getElementById('confirmModalMessage');
            const btn = document.getElementById('confirmModalBtn');
            const btnIcon = document.getElementById('confirmModalBtnIcon');
            const btnText = document.getElementById('confirmModalBtnText');

            // Set form attributes
            form.action = options.action;
            methodInput.value = options.method || 'POST';

            // Set content
            title.textContent = options.title || 'Konfirmasi';
            message.textContent = options.message || 'Apakah Anda yakin?';
            btnText.textContent = options.buttonText || 'Ya';

            // Color schemes based on type
            const schemes = {
                delete: {
                    iconClass: 'fas fa-trash-alt',
                    iconColor: 'text-danger',
                    btnClass: 'btn-danger',
                    btnIconClass: 'fas fa-trash-alt'
                },
                warning: {
                    iconClass: 'fas fa-exclamation-triangle',
                    iconColor: 'text-warning',
                    btnClass: 'btn-warning text-white',
                    btnIconClass: 'fas fa-exclamation-triangle'
                },
                success: {
                    iconClass: 'fas fa-check-circle',
                    iconColor: 'text-success',
                    btnClass: 'btn-success',
                    btnIconClass: 'fas fa-check'
                },
                info: {
                    iconClass: 'fas fa-info-circle',
                    iconColor: 'text-primary',
                    btnClass: 'btn-primary',
                    btnIconClass: 'fas fa-check'
                },
                start: {
                    iconClass: 'fas fa-play-circle',
                    iconColor: 'text-success',
                    btnClass: 'btn-success',
                    btnIconClass: 'fas fa-play'
                },
                end: {
                    iconClass: 'fas fa-stop-circle',
                    iconColor: 'text-warning',
                    btnClass: 'btn-warning text-white',
                    btnIconClass: 'fas fa-stop'
                },
                verify: {
                    iconClass: 'fas fa-user-check',
                    iconColor: 'text-success',
                    btnClass: 'btn-success',
                    btnIconClass: 'fas fa-check'
                },
                unverify: {
                    iconClass: 'fas fa-user-times',
                    iconColor: 'text-warning',
                    btnClass: 'btn-warning text-white',
                    btnIconClass: 'fas fa-times'
                },
                accept: {
                    iconClass: 'fas fa-thumbs-up',
                    iconColor: 'text-success',
                    btnClass: 'btn-success',
                    btnIconClass: 'fas fa-check'
                },
                reject: {
                    iconClass: 'fas fa-thumbs-down',
                    iconColor: 'text-danger',
                    btnClass: 'btn-danger',
                    btnIconClass: 'fas fa-times'
                },
                restore: {
                    iconClass: 'fas fa-database',
                    iconColor: 'text-warning',
                    btnClass: 'btn-warning text-white',
                    btnIconClass: 'fas fa-undo'
                }
            };

            const scheme = schemes[options.type] || schemes.info;

            icon.className = scheme.iconClass + ' fa-2x ' + scheme.iconColor;
            btn.className = 'btn ' + scheme.btnClass + ' rounded-pill px-4';
            btnIcon.className = scheme.btnIconClass + ' me-2';

            // Show modal
            const modalEl = document.getElementById('confirmActionModal');
            const modal = new bootstrap.Modal(modalEl);
            modal.show();
        }

        // Attach delete modal behaviour for .btn-delete-article
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.btn-delete-article').forEach(function(btn) {
                btn.addEventListener('click', function(e) {
                    showConfirmModal({
                        action: btn.getAttribute('data-action'),
                        method: 'DELETE',
                        type: 'delete',
                        title: 'Konfirmasi Hapus',
                        message: 'Apakah Anda yakin ingin menghapus data ini? Tindakan ini tidak dapat dibatalkan.',
                        buttonText: 'Ya, Hapus'
                    });
                });
            });
        });
    </script>

    {{-- Logout Modal - New Style --}}
    <div class="modal fade" id="logout" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow rounded-4">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <i class="fas fa-sign-out-alt fa-2x text-warning"></i>
                        </div>
                        <h5 class="fw-bold mb-2">Konfirmasi Logout</h5>
                        <p class="text-muted mb-4">Apakah Anda yakin ingin keluar dari sistem?</p>
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-warning rounded-pill px-4 text-white">
                                <i class="fas fa-sign-out-alt me-2"></i>Ya, Keluar
                            </button>
                            <button type="button" class="btn btn-light rounded-pill px-4"
                                data-bs-dismiss="modal">Batal</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Reusable Confirmation Modal - New Style --}}
    <div class="modal fade" id="confirmActionModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow rounded-4">
                <form id="confirmActionForm" method="POST">
                    @csrf
                    <input type="hidden" id="confirmActionMethod" name="_method" value="POST">
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <i id="confirmModalIcon" class="fas fa-question-circle fa-2x text-primary"></i>
                        </div>
                        <h5 class="fw-bold mb-2" id="confirmModalTitle">Konfirmasi</h5>
                        <p class="text-muted mb-4" id="confirmModalMessage">Apakah Anda yakin?</p>
                        <div class="d-flex gap-2">
                            <button type="submit" id="confirmModalBtn" class="btn btn-primary rounded-pill px-4">
                                <i id="confirmModalBtnIcon" class="fas fa-check me-2"></i>
                                <span id="confirmModalBtnText">Ya</span>
                            </button>
                            <button type="button" class="btn btn-light rounded-pill px-4"
                                data-bs-dismiss="modal">Batal</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @stack('scripts')
</body>

</html>
