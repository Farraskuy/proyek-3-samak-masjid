@extends('auth.layout')

@section('title', 'Verifikasi Kode - Digital Masjid')

@push('styles')
    <style>
        .auth-card {
            max-width: 450px;
        }

        .auth-wrapper {
            min-height: 100vh;
            display: flex;
            align-items: center;
        }

        .otp-input {
            width: 48px;
            height: 55px;
            font-size: 22px;
            text-align: center;
            border-radius: 12px;
            border: 1px solid #d1d5db;
        }

        .otp-input:focus {
            border-color: #175C9E;
            box-shadow: 0 0 4px rgba(23, 92, 158, 0.4);
            outline: none;
        }

        .btn-submit {
            background-color: #175C9E;
            border: none;
            border-radius: 12px;
            padding: 12px 18px;
            font-weight: 600;
            width: 100%;
            color: white;
        }

        .btn-submit:hover {
            background-color: #134570;
        }

        .resend-link {
            color: #175C9E;
            text-decoration: none;
            font-weight: 600;
            cursor: pointer;
        }

        .resend-link.disabled {
            color: #9ca3af;
            cursor: not-allowed;
            pointer-events: none;
        }
    </style>
@endpush


@section('content')
    <div class="auth-wrapper p-0 p-md-4">
        <div class="form-container d-flex align-items-center justify-content-center h-100">

            <div class="w-100 bg-white rounded-4 shadow-sm p-4 d-flex flex-column gap-3 auth-card">

                <h4 class="fw-semibold mb-1">Masukkan Kode Verifikasi</h4>
                <p class="text-muted mb-2">
                    Kami telah mengirimkan kode verifikasi ke <strong>{{ $destination }}</strong>
                </p>

                {{-- FORM --}}
                <form method="POST" action="{{ route('auth.verifyOtp') }}" id="otpForm">
                    @csrf
                    <input type="hidden" name="hash" value="{{ $hash }}">
                    <input type="hidden" name="code" id="code-full">
                    <input type="hidden" id="g-recaptcha-response" name="g-recaptcha-response">


                    {{-- OTP 6 DIGIT --}}
                    <div class="d-flex justify-content-center gap-2 my-3">
                        @for ($i = 1; $i <= 6; $i++)
                            <input maxlength="1" class="otp-input" type="text" inputmode="numeric" pattern="[0-9]*"
                                id="otp{{ $i }}" autocomplete="off">
                        @endfor
                    </div>

                    <button type="submit" class="btn-submit mt-2">
                        <span class="spinner-border spinner-border-sm d-none me-2" id="spinner"></span>
                        Verifikasi
                    </button>
                </form>

                {{-- RESEND OTP FORM --}}
                <div class="text-center mt-3">
                    <p class="mb-0 text-muted small">Tidak menerima kode?</p>
                    <form method="POST" action="{{ route('auth.resendOtp') }}" id="resendForm" class="d-inline">
                        @csrf
                        <input type="hidden" name="hash" value="{{ $hash }}">
                        <input type="hidden" id="g-recaptcha-resend" name="g-recaptcha-response">

                        <button type="submit" class="btn btn-link p-0 resend-link disabled" id="resendBtn" disabled>
                            Kirim Ulang (<span id="timer">{{ $secondsRemaining }}</span>s)
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection


@push('scripts')
    <script src="https://www.google.com/recaptcha/api.js?render={{ config('services.recaptcha.key') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // === OTP Logic (Focus, Paste, Backspace) ===
            const inputs = document.querySelectorAll('.otp-input');

            inputs.forEach((input, index) => {
                // Auto Focus Next
                input.addEventListener('input', function() {
                    if (this.value.length === 1 && index < inputs.length - 1) {
                        inputs[index + 1].focus();
                    }
                });

                // Backspace
                input.addEventListener('keydown', function(e) {
                    if (e.key === "Backspace" && this.value === "" && index > 0) {
                        inputs[index - 1].focus();
                    }
                });

                // Paste Event
                input.addEventListener('paste', function(e) {
                    e.preventDefault();
                    const pasteData = e.clipboardData.getData('text').trim();

                    if (!/^\d+$/.test(pasteData)) return; // Only numbers

                    const digits = pasteData.split('').slice(0, 6);

                    digits.forEach((digit, i) => {
                        if (inputs[i]) {
                            inputs[i].value = digit;
                        }
                    });

                    // Focus last filled input or next empty
                    const focusIndex = Math.min(digits.length, inputs.length - 1);
                    inputs[focusIndex].focus();
                });
            });

            // === Submit Verify Form ===
            const otpForm = document.getElementById('otpForm');
            otpForm.addEventListener('submit', function(e) {
                e.preventDefault();

                let code = "";
                inputs.forEach(i => code += i.value);
                document.getElementById('code-full').value = code;

                const btn = this.querySelector('button[type="submit"]');
                const spinner = document.getElementById('spinner');

                btn.disabled = true;
                spinner.classList.remove('d-none');

                grecaptcha.ready(function() {
                    grecaptcha.execute('{{ config('services.recaptcha.key') }}', {
                        action: 'verify_otp'
                    }).then(function(token) {
                        document.getElementById('g-recaptcha-response').value = token;
                        otpForm.submit();
                    });
                });
            });

            // === Resend Timer ===
            let timeLeft = {{ $secondsRemaining }};
            const timerSpan = document.getElementById('timer');
            const resendBtn = document.getElementById('resendBtn');

            function updateTimer() {
                if (timeLeft <= 0) {
                    resendBtn.classList.remove('disabled');
                    resendBtn.disabled = false;
                    resendBtn.innerHTML = 'Kirim Ulang';
                } else {
                    resendBtn.classList.add('disabled');
                    resendBtn.disabled = true;
                    resendBtn.innerHTML = `Kirim Ulang (<span id="timer">${timeLeft}</span>s)`;
                }
            }

            updateTimer(); // Initial check

            if (timeLeft > 0) {
                const countdown = setInterval(() => {
                    timeLeft--;
                    if (document.getElementById('timer')) {
                        document.getElementById('timer').textContent = timeLeft;
                    }

                    if (timeLeft <= 0) {
                        clearInterval(countdown);
                        updateTimer();
                    }
                }, 1000);
            }

            // === Resend Submit ===
            const resendForm = document.getElementById('resendForm');
            resendForm.addEventListener('submit', function(e) {
                e.preventDefault();
                resendBtn.disabled = true;
                resendBtn.innerHTML = 'Mengirim...';

                grecaptcha.ready(function() {
                    grecaptcha.execute('{{ config('services.recaptcha.key') }}', {
                        action: 'resend_otp'
                    }).then(function(token) {
                        document.getElementById('g-recaptcha-resend').value = token;
                        resendForm.submit();
                    });
                });
            });
        });
    </script>
@endpush
