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
                        <input type="hidden" name="question_subject" value="Pertanyaan dari Halaman Konsultasi">

                        <div class="col-12">
                            <label for="questionInput" class="form-label fw-bold small">Pertanyaan / Pesan</label>
                            <textarea id="questionInput" name="question_text" class="form-control auth-check" rows="4"
                                placeholder="Tuliskan pertanyaan atau permasalahan Anda di sini..."></textarea>
                        </div>

                        <div class="col-12">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="is_anonymous" id="anonymousCheck"
                                    value="1">
                                <label class="form-check-label small" for="anonymousCheck">
                                    <i class="fas fa-user-secret me-1 text-muted"></i>
                                    Kirim sebagai Anonim (nama tidak ditampilkan)
                                </label>
                            </div>
                        </div>

                        <div class="col-12 mt-4">
                            <button type="button" class="btn btn-submit w-100 auth-check-btn">Kirim Pertanyaan</button>
                        </div>
                    </div>
                </form>
            </div>

            <div class="col-lg-6">
                <div class="image-section p-4">
                    <div class="circle-bg"></div>
                    <img src="{{ asset('assets/images/undraw_online-messaging_gjnh.png') }}" alt="Konsultasi Ustadz"
                        class="img-fluid rounded-3 mb-4" style="object-fit: cover;">
                </div>
            </div>
        </div>
    </div>

    <!-- Login Modal -->
    <div class="modal fade" id="loginModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow rounded-4">
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <i class="fas fa-lock fa-2x" style="color: #175C9E;"></i>
                    </div>
                    <h5 class="fw-bold mb-2">Login Diperlukan</h5>
                    <p class="text-muted mb-4">Silakan login untuk melanjutkan konsultasi dengan ustadz kami.</p>
                    <div class="d-flex gap-2">
                        <a href="{{ route('login') }}" class="btn btn-primary rounded-pill px-4 w-100">
                            <i class="fas fa-sign-in-alt me-2"></i>Login
                        </a>
                        <button type="button" class="btn btn-light rounded-pill px-4"
                            data-bs-dismiss="modal">Batal</button>
                    </div>
                    <div class="mt-3">
                        <span class="text-muted">Belum punya akun? </span>
                        <a href="{{ route('register') }}" class="fw-bold text-decoration-none">Daftar Sekarang</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Verification Modal -->
    <div class="modal fade" id="verificationModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow rounded-4">
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <i class="fas fa-envelope fa-2x text-warning"></i>
                    </div>
                    <h5 class="fw-bold mb-2">Verifikasi Email</h5>
                    <p class="text-muted mb-4">Anda harus memverifikasi email terlebih dahulu untuk dapat melakukan
                        konsultasi.</p>
                    <div class="d-flex gap-2">
                        <a href="{{ route('profile.show') }}" class="btn btn-warning rounded-pill px-4 text-white">
                            <i class="fas fa-user me-2"></i>Ke Profil Saya
                        </a>
                        <button type="button" class="btn btn-light rounded-pill px-4"
                            data-bs-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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

                    const questionText = formData.get('question_text');
                    if (!questionText || questionText.trim().length < 10) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Pertanyaan terlalu pendek',
                            text: 'Mohon tuliskan pertanyaan minimal 10 karakter.',
                            confirmButtonColor: '#175C9E'
                        });
                        return;
                    }

                    let originalBtnText = submitBtn.innerHTML;
                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Mengirim...';
                    submitBtn.disabled = true;

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
                            if (data.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Konsultasi diterima',
                                    text: 'Silakan mulai chat.',
                                    showConfirmButton: false,
                                    timer: 2000,
                                    timerProgressBar: true
                                }).then(() => {
                                    window.location.href = data.redirect;
                                });

                            } else if (data.error) {
                                // Handle Error Spesifik
                                if (data.error === 'Email belum diverifikasi') {
                                    verificationModal.show();
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Gagal',
                                        text: data.error,
                                        confirmButtonColor: '#175C9E'
                                    });
                                }
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            Swal.fire({
                                icon: 'error',
                                title: 'Terjadi Kesalahan',
                                text: 'Gagal mengirim pesan. Silakan coba lagi.',
                                confirmButtonColor: '#175C9E'
                            });
                        })
                        .finally(() => {
                            submitBtn.innerHTML = originalBtnText;
                            submitBtn.disabled = false;
                        });
                });
            }
        });
    </script>
@endpush
