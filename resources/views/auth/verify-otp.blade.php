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

        .label-header {
            width: fit-content;
            background-color: #CE9138;
            color: white;
            padding: 10px 16px;
            border-radius: 14px;
            font-weight: 600;
            font-size: 15px;
        }
    </style>
@endpush


@section('content')
    <div class="auth-wrapper p-0 p-md-4">
        <div class="form-container d-flex align-items-center justify-content-center h-100">

            <div class="w-100 bg-white rounded-4 shadow-sm p-4 d-flex flex-column gap-3 auth-card">

                <h4 class="fw-semibold mb-1">Masukkan Kode Verifikasi</h4>
                <p class="text-muted mb-2">
                    Kami telah mengirimkan kode verifikasi ke <strong>{{ $destination }}</strong>.
                </p>

                {{-- ERROR MESSAGE --}}
                @if ($errors->any())
                    <script>
                        setTimeout(() => {
                            Toast.fire({
                                icon: "error",
                                title: "{{ $errors->first() }}"
                            });
                        }, 300);
                    </script>
                @endif

                {{-- FORM --}}
                <form method="POST" action="{{ route('auth.verifyOtp') }}" id="otpForm">
                    @csrf
                    <input type="hidden" name="destination" value="{{ $destination }}">
                    <input type="hidden" name="type" value="{{ $type }}">
                    <input type="hidden" name="code" id="code-full">

                    {{-- OTP 6 DIGIT --}}
                    <div class="d-flex justify-content-center gap-2 my-3">
                        @for ($i = 1; $i <= 6; $i++)
                            <input maxlength="1" class="otp-input" type="text" inputmode="numeric" pattern="[0-9]*"
                                id="otp{{ $i }}">
                        @endfor
                    </div>

                    <button type="submit" class="btn-submit mt-2">
                        <span class="spinner-border spinner-border-sm d-none me-2" id="spinner"></span>
                        Verifikasi
                    </button>

                    <a href="{{ route('login') }}" class="btn btn-light border w-100 fw-semibold mt-3 rounded-3">
                        Kembali ke Login
                    </a>
                </form>

            </div>
        </div>
    </div>
@endsection


@push('scripts')
    <script>
        // === OTP Auto Move ===
        const inputs = document.querySelectorAll('.otp-input');

        inputs.forEach((input, index) => {
            input.addEventListener('input', function() {
                if (this.value.length === 1 && index < inputs.length - 1) {
                    inputs[index + 1].focus();
                }
            });

            input.addEventListener('keydown', function(e) {
                if (e.key === "Backspace" && this.value === "" && index > 0) {
                    inputs[index - 1].focus();
                }
            });
        });

        // === Submit Form with Combined OTP ===
        const form = document.getElementById('otpForm');
        form.addEventListener('submit', function(e) {
            let code = "";
            inputs.forEach(i => code += i.value);

            document.getElementById('code-full').value = code;

            // Loading spinner
            document.getElementById('spinner').classList.remove('d-none');
        });
    </script>
@endpush
