@extends('auth.layout')

@section('title', 'Daftar - Digital Masjid')

@push('styles')
    <style>
        .left-content {
            width: calc(100% - 550px);
        }

        .form-container {
            width: 100%;
            max-width: 550px;
        }

        .auth-wrapper {
            min-height: 100vh;
            display: flex;
            align-items: center;
        }

        .pw-box {
            border-radius: 12px;
            font-size: 14px;
            width: 100%;
        }

        .pw-strength-label {
            font-weight: 600;
            color: #374151;
        }

        #pw-strength-value {
            font-weight: 700;
        }

        .pw-rules {
            list-style: none;
            padding-left: 0;
            margin: 0;
            border: 1px solid lightgray;
            border-radius: 14px;
            background-color: rgba(0, 0, 0, 0.02);
            padding: 14px 18px;
        }

        .pw-rules li {
            display: flex;
            align-items: center;
            margin: 4px 0;
            color: #6b7280;
            transition: 0.2s ease;
        }

        .pw-rules li.valid {
            color: #16a34a !important;
            font-weight: 600;
        }

        .pw-rules li.invalid {
            color: #dc2626 !important;
            font-weight: 600;
        }

        /* Dot */
        .pw-rules .dot {
            display: inline-block;
            width: 10px;
            height: 10px;
            border-radius: 50%;
            margin-right: 8px;
            background: #9ca3af;
            transition: 0.25s ease;
        }

        .pw-rules li.valid .dot {
            background: #16a34a !important;
        }

        .pw-rules li.invalid .dot {
            background: #dc2626 !important;
        }
    </style>
@endpush

@section('content')
    <div class="auth-wrapper p-0 p-md-4">
        <div class="d-none d-lg-block left-content px-4 h-100">
            <div class="d-flex flex-column justify-content-between h-100">
                <div class="d-flex gap-3 align-items-center">
                    <img src="{{ asset('assets/images/logo.png') }}" alt="DIMAS Logo" class="logo"
                        style="max-width: 70px; max-height: 70px;">
                    <div class="fw-semibold  d-flex flex-column justify-content-center">
                        <p class="fs-3 mb-0 text-white">SAMAK Masjid</p>
                        <p style="margin-top: -10px; margin-bottom: 0;">Sistem Aplikasi Managemen Aktivitas dan Keuangan
                            Masjid</p>
                    </div>
                </div>
                <div>
                    <h2 class="fw-semibold text-white">Menghubungkan Umat, Memakmurkan Masjid.</h2>
                    <p class="fw-medium" style="color:lightgray;">Samak Masjid hadir untuk mendukung transparansi dan efisiensi Dewan Kemakmuran
                        Masjid (DKM) dalam mengelola
                        amanah umat dan melayani jamaah.</p>
                </div>
            </div>
        </div>

        <div class="form-container d-flex align-items-center justify-content-center h-100" style="max-height: 850px">
            <div class="w-100 h-100 bg-white rounded-4 shadow-sm p-4 d-flex flex-column gap-2 overflow-auto">
                <div class="mb-3 px-3 py-2 rounded-4 fw-semibold text-white"
                    style="width: fit-content; background-color: #CE9138">
                    Daftar
                </div>

                <h4 class="fw-semibold">Daftar Sebagai Jamaah Digital Masjid</h4>

                <form method="POST" action="{{ route('register') }}">
                    @csrf
                    <input type="hidden" id="g-recaptcha-response" name="g-recaptcha-response">

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
                            <label for="username" class="form-label">Username</label>
                            <input type="text"
                                class="p-4 py-3 rounded-4 form-control @error('username') is-invalid @enderror"
                                id="username" name="username" placeholder="Contoh: budisusanto123"
                                value="{{ old('username') }}" required>
                            @error('username')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="p-4 py-3 rounded-4 form-control @error('email') is-invalid @enderror"
                            id="email" name="email" placeholder="Contoh: budisusanto123@gmail.com"
                            value="{{ old('email') }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="phone_number" class="form-label">Nomor Telepon</label>
                        <input type="tel"
                            class="p-4 py-3 rounded-4 form-control @error('phone_number') is-invalid @enderror"
                            id="phone_number" name="phone_number" placeholder="Contoh: 012345678910"
                            value="{{ old('phone_number') }}" required>
                        @error('phone_number')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <div class="position-relative">
                            <label for="password" class="form-label">Password</label>
                            <input type="password"
                                class="p-4 py-3 rounded-4 form-control @error('password') is-invalid @enderror"
                                id="password" name="password" placeholder="Masukan Password" value="{{ old('password') }}"
                                required>
                        </div>

                        @error('password')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror

                        <div class="pw-box mt-2 ">
                            <div class="pw-strength-label">
                                Password Strength: <span id="pw-strength-value">–</span>
                            </div>

                            <ul class="pw-rules mt-2" style="display: none;">
                                <li id="rule-length"><span class="dot"></span> Minimal 8 karakter</li>
                                <li id="rule-upper"><span class="dot"></span> Ada huruf besar (A–Z)</li>
                                <li id="rule-lower"><span class="dot"></span> Ada huruf kecil (a–z)</li>
                                <li id="rule-number"><span class="dot"></span> Ada angka (0–9)</li>
                                <li id="rule-symbol"><span class="dot"></span> Ada simbol (!@#$%^&*)</li>
                            </ul>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="position-relative">
                            <label for="password_confirmation" class="form-label">Ulangi Password</label>
                            <input type="password"
                                class="p-4 py-3 rounded-4 form-control @error('password_confirmation') is-invalid @enderror"
                                id="password_confirmation" name="password_confirmation" placeholder="Ulangi Password Anda"
                                required>
                        </div>
                        <div id="pw-match-message" class="form-text mt-1"></div>
                        @error('password_confirmation')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>


                    <button type="submit" class="btn btn-submit text-white w-100 fw-semibold  rounded-3"
                        id="submit-btn">
                        <span id="spinner" class="spinner-border spinner-border-sm d-none me-2 "
                            aria-hidden="true"></span>
                        Daftar
                    </button>
                    <hr class="my-4">

                    <div class="text-center">
                        <span class="text-muted">Sudah Punya akun? <a href="{{ route('login') }}"
                                class="text-decoration-none">Masuk Disini</a></span>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://www.google.com/recaptcha/api.js?render={{ config('services.recaptcha.key') }}"></script>
    <script>
        const form = document.querySelector('form');
        const btn = document.querySelector('.btn-submit');
        const spinner = btn.querySelector('#spinner');

        form.addEventListener('submit', function(e) {
            e.preventDefault();
            btn.disabled = true;
            spinner.classList.remove('d-none');
            grecaptcha.ready(function() {
                grecaptcha.execute('{{ config('services.recaptcha.key') }}', {
                    action: 'reset_password'
                }).then(function(token) {
                    document.getElementById('g-recaptcha-response').value = token;
                    form.submit();
                });
            });
        });
    </script>
    
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const pass = document.getElementById("password");
            const passRepeat = document.getElementById("password_confirmation");

            const rules = {
                length: document.getElementById("rule-length"),
                upper: document.getElementById("rule-upper"),
                lower: document.getElementById("rule-lower"),
                number: document.getElementById("rule-number"),
                symbol: document.getElementById("rule-symbol"),
            };

            const pwRulesList = document.querySelector(".pw-rules");
            const pwMatchMessage = document.getElementById("pw-match-message");

            const strengthValue = document.getElementById("pw-strength-value");

            function checkPasswordMatch() {
                const v1 = pass.value;
                const v2 = passRepeat.value;

                // Jangan tampilkan pesan jika field ulangi password kosong
                if (v2.length === 0) {
                    pwMatchMessage.textContent = "";
                    pwMatchMessage.className = "form-text mt-1"; // Reset class
                    return;
                }

                // Jika cocok
                if (v1 === v2) {
                    pwMatchMessage.textContent = "✔ Password sudah cocok";
                    pwMatchMessage.className = "form-text mt-1 text-success fw-semibold";
                } else {
                    // Jika tidak cocok
                    pwMatchMessage.textContent = "✖ Password tidak cocok";
                    pwMatchMessage.className = "form-text mt-1 text-danger fw-semibold";
                }
            }

            function updatePasswordUI() {
                const v = pass.value;

                const hasLength = v.length >= 8;
                const hasUpper = /[A-Z]/.test(v);
                const hasLower = /[a-z]/.test(v);
                const hasNumber = /[0-9]/.test(v);
                const hasSymbol = /[\W_]/.test(v); // \W sama dengan [^A-Za-z0-9_]

                const ruleMap = {
                    length: hasLength,
                    upper: hasUpper,
                    lower: hasLower,
                    number: hasNumber,
                    symbol: hasSymbol
                };

                let score = 0;

                for (const key in ruleMap) {
                    if (ruleMap[key]) {
                        rules[key].classList.add("valid");
                        rules[key].classList.remove("invalid");
                        score++;
                    } else {
                        rules[key].classList.add("invalid");
                        rules[key].classList.remove("valid");
                    }
                }

                if (v.length === 0) {
                    strengthValue.textContent = "–";
                    strengthValue.style.color = "#374151";
                    // Reset semua rules jika password kosong
                    for (const key in rules) {
                        rules[key].classList.remove("valid", "invalid");
                    }
                } else if (score <= 2) {
                    strengthValue.textContent = "Weak";
                    strengthValue.style.color = "#dc2626";
                } else if (score === 3) {
                    strengthValue.textContent = "Medium";
                    strengthValue.style.color = "#d97706";
                } else if (score >= 4) {
                    strengthValue.textContent = "Strong";
                    strengthValue.style.color = "#16a34a";
                }

                checkPasswordMatch();
            }

            pass.addEventListener("input", updatePasswordUI);

            passRepeat.addEventListener("input", checkPasswordMatch);

            pass.addEventListener("focus", () => {
                pwRulesList.style.display = "block";
            });

            pass.addEventListener("blur", () => {
                pwRulesList.style.display = "none";
            });
        });
    </script>
@endpush
