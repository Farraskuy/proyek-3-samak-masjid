@extends('auth.layout')

@section('title', 'Masuk - Digital Masjid')

@push('styles')
    <style>
        .left-content {
            width: calc(100% - 550px);
        }

        .form-container {
            width: 100%;
            max-width: 550px;
            /* Ensures it never exceeds 550px */
        }

        .auth-wrapper {
            min-height: 100vh;
            display: flex;
            align-items: center;
        }
    </style>
@endpush

@section('content')
    <div class="auth-wrapper p-0 p-md-4">
        <!-- Left informational content (hidden on small screens) -->
        <div class="d-none d-lg-block left-content px-4 h-100">
            <div class="d-flex flex-column justify-content-between h-100">
                <div class="d-flex gap-3 align-items-center">
                    <img src="{{ asset('assets/images/logo.png') }}" alt="DIMAS Logo" class="logo bg-white rounded-4 p-1"
                        style="max-width: 70px; max-height: 70px;">
                    <div class="fw-semibold  d-flex flex-column justify-content-center">
                        <p class="fs-3 mb-0 text-white">SAMAK Masjid</p>
                        <p style="margin-top: -10px; margin-bottom: 0;">Sistem Aplikasi Managemen Aktivitas dan Keuangan
                            Masjid</p>
                    </div>
                </div>
                <div>
                    <h2 class="fw-semibold text-white">Menghubungkan Umat, Memakmurkan Masjid.</h2>
                    <p class="fw-semibold">Samak Masjid hadir untuk mendukung transparansi dan efisiensi Dewan Kemakmuran
                        Masjid (DKM) dalam mengelola
                        amanah umat dan melayani jamaah.</p>
                </div>
            </div>
        </div>

        <!-- Login Form -->
        <div class="form-container d-flex align-items-center justify-content-center h-100" style="max-height: 850px">
            <div class="w-100 h-100 bg-white rounded-4 shadow-sm p-4 d-flex flex-column gap-2 overflow-auto">
                <div class="mb-3 px-3 py-2 rounded-4 fw-semibold text-white"
                    style="width: fit-content; background-color: #CE9138">
                    Daftar
                </div>

                <h4 class="fw-semibold">Daftar Sebagai Jamaah Digital Masjid</h4>

                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    <div class="d-flex gap-3 mb-3 flex-wrap flex-lg-nowrap">
                        <div class="flex-grow-1">
                            <label for="full_name" class="form-label">Nama Lengkap</label>
                            <input type="text"
                                class="p-4 py-3 rounded-4 form-control @error('full_name') is-invalid @enderror"
                                id="full_name" name="full_name" placeholder="Contoh: Budiono Susanto"
                                value="{{ old('full_name') }}" required autofocus>
                            @error('full_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="flex-grow-1">
                            <label for="username" class="form-label">Nama Unik Pengguna</label>
                            <input type="text"
                                class="p-4 py-3 rounded-4 form-control @error('username') is-invalid @enderror"
                                id="username" name="username" placeholder="Contoh: budisusanto123"
                                value="{{ old('username') }}" required autofocus>
                            @error('username')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="p-4 py-3 rounded-4 form-control @error('email') is-invalid @enderror"
                            id="email" name="email" placeholder="Contoh: budisusanto123@gmail.com"
                            value="{{ old('email') }}" required autofocus>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="phone_number" class="form-label">Nomor Telepon</label>
                        <input type="tel"
                            class="p-4 py-3 rounded-4 form-control @error('phone_number') is-invalid @enderror"
                            id="phone_number" name="phone_number" placeholder="Contoh: 012345678910"
                            value="{{ old('phone_number') }}" required autofocus>
                        @error('phone_number')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3 position-relative">

                        <label for="password" class="form-label">Password</label>
                        <input type="password"
                            class="p-4 py-3 rounded-4 form-control @error('password') is-invalid @enderror" id="password"
                            name="password" placeholder="Masukan Password" value="{{ old('password') }}" required
                            autofocus>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3 position-relative">
                        <label for="password-repeat" class="form-label">Password</label>
                        <input type="password"
                            class="p-4 py-3 rounded-4 form-control @error('password-repeat') is-invalid @enderror"
                            id="password-repeat" name="password-repeat" placeholder="Masukan Password"
                            value="{{ old('password-repeat') }}" required autofocus>
                        @error('password-repeat')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" style="background-color: #175C9E"
                        class="text-white btn fw-semibold rounded-4 py-2 w-100">Masuk</button>

                    <hr class="my-4">

                    <div class="text-center">
                        <span class="text-muted">Sudah Punya akun? <a href="{{ route('login') }}"
                                class="text-decoration-none">Masuk Disini</a></span>
                    </div>
                </form>

                {{-- Quick OTP send (uses current email or phone inputs) --}}
                <div class="mt-3">
                    <div class="text-muted mb-2">Ingin verifikasi sekarang? Kirim kode ke:</div>
                    <div class="d-flex gap-2">
                        <button id="send-email-otp" class="btn btn-outline-primary flex-grow-1">
                            Kirim ke Email
                            <span class="spinner-border spinner-border-sm ms-2 d-none" role="status" aria-hidden="true"></span>
                        </button>
                        <button id="send-phone-otp" class="btn btn-outline-primary">
                            Kirim ke HP
                            <span class="spinner-border spinner-border-sm ms-2 d-none" role="status" aria-hidden="true"></span>
                        </button>
                    </div>
                </div>

                <form id="otp-send-form" method="POST" action="{{ route('auth.sendOtp') }}" style="display:none;">
                    @csrf
                    <input type="hidden" name="destination" id="otp-destination">
                    <input type="hidden" name="type" id="otp-type">
                    {{-- honeypot --}}<input type="text" name="hp_field" style="display:none;" autocomplete="off">
                </form>

                @push('scripts')
                    @php
                        $siteKey = env('RECAPTCHA_SITE_KEY');
                        $recaptchaType = env('RECAPTCHA_TYPE', 'v3');
                    @endphp

                    @if($siteKey && $recaptchaType === 'v3')
                        <script src="https://www.google.com/recaptcha/api.js?render={{ $siteKey }}"></script>
                    @elseif($siteKey && $recaptchaType === 'v2')
                        <script src="https://www.google.com/recaptcha/api.js" async defer></script>
                    @endif

                    <script>
                        (function(){
                            const siteKey = '{{ $siteKey ?? '' }}';
                            const recaptchaType = '{{ $recaptchaType }}';
                            const form = document.getElementById('otp-send-form');

                            function showSpinnerFor(button, show) {
                                const spinner = button.querySelector('.spinner-border');
                                if (!spinner) return;
                                if (show) {
                                    spinner.classList.remove('d-none');
                                    button.setAttribute('disabled', 'disabled');
                                } else {
                                    spinner.classList.add('d-none');
                                    button.removeAttribute('disabled');
                                }
                            }

                            if (!siteKey) {
                                // no recaptcha configured, simple submit handlers
                                document.getElementById('send-email-otp').addEventListener('click', function(e){
                                    e.preventDefault();
                                    const email = document.getElementById('email').value || '';
                                    if (!email) { alert('Silakan masukkan email terlebih dahulu.'); return; }
                                    document.getElementById('otp-destination').value = email;
                                    document.getElementById('otp-type').value = 'email';
                                    showSpinnerFor(this, true);
                                    form.submit();
                                });
                                document.getElementById('send-phone-otp').addEventListener('click', function(e){
                                    e.preventDefault();
                                    const phone = document.getElementById('phone_number').value || '';
                                    if (!phone) { alert('Silakan masukkan nomor telepon terlebih dahulu.'); return; }
                                    document.getElementById('otp-destination').value = phone;
                                    document.getElementById('otp-type').value = 'phone';
                                    showSpinnerFor(this, true);
                                    form.submit();
                                });
                                return;
                            }

                            if (recaptchaType === 'v3') {
                                async function attachV3AndSubmit(button, destination, type) {
                                    showSpinnerFor(button, true);
                                    try {
                                        await grecaptcha.ready();
                                        const token = await grecaptcha.execute(siteKey, {action: type === 'email' ? 'send_email_otp' : 'send_phone_otp'});
                                        const existing = form.querySelector('input[name="g-recaptcha-response"]');
                                        if (existing) existing.remove();
                                        const input = document.createElement('input');
                                        input.type = 'hidden';
                                        input.name = 'g-recaptcha-response';
                                        input.value = token;
                                        form.appendChild(input);
                                        form.submit();
                                    } catch (err) {
                                        console.warn('reCAPTCHA error', err);
                                        showSpinnerFor(button, false);
                                        alert('Terjadi kesalahan reCAPTCHA. Coba lagi.');
                                    }
                                }

                                document.getElementById('send-email-otp').addEventListener('click', function(e){
                                    e.preventDefault();
                                    const email = document.getElementById('email').value || '';
                                    if (!email) { alert('Silakan masukkan email terlebih dahulu.'); return; }
                                    document.getElementById('otp-destination').value = email;
                                    document.getElementById('otp-type').value = 'email';
                                    attachV3AndSubmit(this, email, 'email');
                                });

                                document.getElementById('send-phone-otp').addEventListener('click', function(e){
                                    e.preventDefault();
                                    const phone = document.getElementById('phone_number').value || '';
                                    if (!phone) { alert('Silakan masukkan nomor telepon terlebih dahulu.'); return; }
                                    document.getElementById('otp-destination').value = phone;
                                    document.getElementById('otp-type').value = 'phone';
                                    attachV3AndSubmit(this, phone, 'phone');
                                });
                            } else {
                                // Invisible reCAPTCHA v2 flow: render a widget and execute it on click
                                let widgetId = null;
                                const recaptchaContainer = document.createElement('div');
                                recaptchaContainer.style.display = 'none';
                                recaptchaContainer.id = 'recaptcha-otp-send';
                                document.body.appendChild(recaptchaContainer);

                                window.__otpFormSubmit = function(token) {
                                    const existing = form.querySelector('input[name="g-recaptcha-response"]');
                                    if (existing) existing.remove();
                                    const input = document.createElement('input');
                                    input.type = 'hidden';
                                    input.name = 'g-recaptcha-response';
                                    input.value = token;
                                    form.appendChild(input);
                                    form.submit();
                                };

                                const renderRecaptcha = function(){
                                    if (typeof grecaptcha === 'undefined') return;
                                    if (widgetId !== null) return;
                                    widgetId = grecaptcha.render('recaptcha-otp-send', {
                                        'sitekey': siteKey,
                                        'size': 'invisible',
                                        'callback': '__otpFormSubmit'
                                    });
                                };

                                // attempt to render (script might not be ready instantly)
                                setTimeout(renderRecaptcha, 500);
                                window.addEventListener('load', renderRecaptcha);

                                document.getElementById('send-email-otp').addEventListener('click', function(e){
                                    e.preventDefault();
                                    const email = document.getElementById('email').value || '';
                                    if (!email) { alert('Silakan masukkan email terlebih dahulu.'); return; }
                                    document.getElementById('otp-destination').value = email;
                                    document.getElementById('otp-type').value = 'email';
                                    showSpinnerFor(this, true);
                                    if (widgetId !== null) {
                                        grecaptcha.execute(widgetId);
                                    } else {
                                        // fallback: submit form (without token)
                                        form.submit();
                                    }
                                });

                                document.getElementById('send-phone-otp').addEventListener('click', function(e){
                                    e.preventDefault();
                                    const phone = document.getElementById('phone_number').value || '';
                                    if (!phone) { alert('Silakan masukkan nomor telepon terlebih dahulu.'); return; }
                                    document.getElementById('otp-destination').value = phone;
                                    document.getElementById('otp-type').value = 'phone';
                                    showSpinnerFor(this, true);
                                    if (widgetId !== null) {
                                        grecaptcha.execute(widgetId);
                                    } else {
                                        form.submit();
                                    }
                                });
                            }
                        })();
                    </script>
                @endpush
            </div>
        </div>
    </div>
@endsection
