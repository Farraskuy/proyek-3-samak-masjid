@extends('client.layout')

@section('title', 'Konsultasi')

@push('styles')
    <style>
        .consultation-wrapper {
            background-color: #fff;
            min-height: 80vh;
            display: flex;
            align-items: center;
        }

        .form-section {
            padding: 3rem;
        }

        .image-section {
            background-color: #f0f4f8;
            border-radius: 2rem;
            overflow: hidden;
            position: relative;
            min-height: 500px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .image-section img {
            max-width: 100%;
            height: auto;
            z-index: 2;
            position: relative;
        }

        .circle-bg {
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            background: radial-gradient(circle at center, #e2e8f0 0%, transparent 70%);
            z-index: 1;
        }

        .contact-info-card {
            background: #fff;
            border-radius: 1rem;
            padding: 1.5rem;
            margin-top: 1rem;
            width: 90%;
            z-index: 3;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }

        .contact-icon {
            width: 40px;
            height: 40px;
            background-color: #eff6ff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #3b82f6;
            margin-right: 1rem;
        }

        .section-label {
            color: #d97706;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.875rem;
            letter-spacing: 0.05em;
            margin-bottom: 0.5rem;
        }

        .main-heading {
            font-size: 2.5rem;
            font-weight: 800;
            color: #111827;
            line-height: 1.2;
            margin-bottom: 1.5rem;
        }

        .form-control {
            background-color: #f9fafb;
            border: 1px solid #e5e7eb;
            padding: 0.75rem 1rem;
            border-radius: 0.5rem;
        }

        .form-control:focus {
            background-color: #fff;
            border-color: #3b82f6;
            box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.2);
        }

        .btn-submit {
            background-color: #111827;
            color: #fff;
            padding: 0.75rem 2rem;
            border-radius: 0.5rem;
            font-weight: 600;
            border: none;
            transition: all 0.2s;
        }

        .btn-submit:hover {
            background-color: #1f2937;
            transform: translateY(-1px);
        }
    </style>
@endpush

@section('content')
    <div class="container py-5">
        <div class="row align-items-center">
            <!-- Left Side: Form -->
            <div class="col-lg-6 form-section">
                <div class="section-label">Konsultasi Syariah</div>
                <h1 class="main-heading">Tanya Jawab Agama Bersama Ustadz</h1>
                <p class="text-muted mb-5">
                    Punya pertanyaan seputar agama atau masalah kehidupan? Sampaikan kepada Ustadz kami. Insya Allah kami
                    akan membantu memberikan pandangan sesuai syariat.
                </p>

                <form id="consultationForm">
                    @csrf
                    <div class="row g-3">
                        <div class="col-12">
                            <label for="nameInput" class="form-label fw-bold small">Nama Lengkap</label>
                            <input id="nameInput" type="text" class="form-control auth-check" placeholder="Nama depan"
                                value="{{ Auth::check() ? Auth::user()->full_name : '' }}" readonly>
                        </div>


                        <div class="col-12">
                            <label for="emailInput" class="form-label fw-bold small">Alamat Email</label>
                            <input id="emailInput" type="email" class="form-control auth-check" placeholder="Alamat email"
                                value="{{ Auth::check() ? Auth::user()->email : '' }}" readonly>
                        </div>

                        <!-- Hidden Subject Field (Required by Controller) -->
                        <input type="hidden" name="question_subject" value="Pertanyaan dari Halaman Konsultasi">

                        <div class="col-12">
                            <label for="questionInput" class="form-label fw-bold small">Pertanyaan / Pesan</label>
                            <textarea id="questionInput" name="question_text" class="form-control auth-check" rows="4"
                                placeholder="Tuliskan pertanyaan atau permasalahan Anda di sini..."></textarea>
                        </div>

                        <div class="col-12 mt-4">
                            <button type="button" class="btn btn-submit w-100 auth-check-btn">Kirim Pertanyaan</button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Right Side: Image & Info -->
            <div class="col-lg-6">
                <div class="image-section p-4">
                    <div class="circle-bg"></div>
                    <!-- Placeholder Image - You might want to replace this with a real asset -->
                    <img src="{{ asset('assets/images/undraw_online-messaging_gjnh.png') }}" alt="Konsultasi Ustadz"
                        class="img-fluid rounded-3 mb-4" style="object-fit: cover;">
                </div>
            </div>
        </div>
    </div>

    <!-- Login Modal -->
    <div class="modal fade" id="loginModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content border-0 shadow-sm rounded-3">
                <div class="modal-body p-4 text-center">
                    <div class="mb-3">
                        <i class="fas fa-lock fa-2x text-muted opacity-50"></i>
                    </div>
                    <h5 class="fw-bold mb-2">Login Diperlukan</h5>
                    <p class="text-muted mb-4 small">Silakan login untuk melanjutkan konsultasi.</p>
                    <div class="d-grid gap-2">
                        <a href="{{ route('login') }}" class="btn btn-primary btn-sm rounded-pill">Login</a>
                        <button type="button" class="btn btn-light btn-sm rounded-pill text-muted"
                            data-bs-dismiss="modal">Batal</button>
                    </div>
                    <div class="mt-3">
                        <small class="text-muted">Belum punya akun? <a href="{{ route('register') }}"
                                class="fw-bold text-decoration-none">Daftar</a></small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Verification Modal -->
    <div class="modal fade" id="verificationModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content border-0 shadow-sm rounded-3">
                <div class="modal-body p-4 text-center">
                    <div class="mb-3">
                        <i class="fas fa-envelope fa-2x text-warning opacity-75"></i>
                    </div>
                    <h5 class="fw-bold mb-2">Verifikasi Email</h5>
                    <p class="text-muted mb-4 small">Anda harus memverifikasi email terlebih dahulu untuk melakukan
                        konsultasi.</p>
                    <div class="d-grid gap-2">
                        <!-- Assuming we have a route to resend verification or go to profile -->
                        <a href="{{ route('profile.show') }}" class="btn btn-warning btn-sm rounded-pill text-white">Ke
                            Profil Saya</a>
                        <button type="button" class="btn btn-light btn-sm rounded-pill text-muted"
                            data-bs-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const isAuth = {{ Auth::check() ? 'true' : 'false' }};
            const isVerified = {{ Auth::check() && Auth::user()->hasVerifiedEmail() ? 'true' : 'false' }};

            const loginModal = new bootstrap.Modal(document.getElementById('loginModal'));
            const verificationModal = new bootstrap.Modal(document.getElementById('verificationModal'));

            // Function to handle auth check
            function checkAuth(e) {
                if (!isAuth) {
                    e.preventDefault();
                    e.target.blur(); // Remove focus
                    loginModal.show();
                    return false;
                }
                if (!isVerified) {
                    e.preventDefault();
                    e.target.blur();
                    verificationModal.show();
                    return false;
                }
                return true;
            }

            // Add event listeners to all inputs with class 'auth-check'
            const inputs = document.querySelectorAll('.auth-check');
            inputs.forEach(input => {
                input.addEventListener('focus', checkAuth);
                input.addEventListener('click', checkAuth);
            });

            // Handle submit
            const submitBtn = document.querySelector('.auth-check-btn');
            if (submitBtn) {
                submitBtn.addEventListener('click', function(e) {
                    if (!checkAuth(e)) return;

                    // AJAX Submit
                    const form = document.getElementById('consultationForm');
                    const formData = new FormData(form);

                    fetch('{{ route('client.consultations.store') }}', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            },
                            body: formData
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.error) {
                                // Handle specific error for verification if backend catches it too
                                if (data.error === 'Email belum diverifikasi') {
                                    verificationModal.show();
                                } else {
                                    Toast.fire({
                                        icon: 'error',
                                        title: data.error
                                    });
                                }
                            } else if (data.success) {
                                Toast.fire({
                                    icon: 'success',
                                    title: data.success
                                });
                                setTimeout(() => {
                                    window.location.href = data.redirect;
                                }, 1000);
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            Toast.fire({
                                icon: 'error',
                                title: 'Terjadi kesalahan saat mengirim pesan'
                            });
                        });
                });
            }
        });
    </script>
@endpush
