@extends('auth.layout')

@section('title', 'Reset Password')

@push('styles')
    <style>
        .auth-card {
            max-width: 450px;
        }
    </style>
@endpush

@section('content')
    <div class="auth-wrapper p-0 p-md-4" style="min-height:100vh;">
        <div class="form-container d-flex align-items-center justify-content-center h-100">
            <div class="w-100 bg-white rounded-4 shadow-sm p-4 d-flex flex-column gap-3 overflow-auto auth-card">

                <div class="mb-3 px-3 py-2 rounded-4 fw-semibold text-white"
                    style="width: fit-content; background-color: #CE9138">
                    Reset Password
                </div>

                <h4 class="fw-semibold mb-1">Masukkan Email Anda</h4>
                <p class="text-muted mb-3">Masukkan email akun Anda untuk menerima tautan reset password.</p>

                @if (session('status'))
                    <div class="alert alert-success">{{ session('status') }}</div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('password.email') }}" id="forgot-form">
                    @csrf
                    <div class="mb-3">
                        <label for="email" class="form-label small text-muted">Email address</label>
                        <input type="email" class="form-control form-control-lg rounded-4 p-3 fs-6" id="email"
                            name="email" required autofocus placeholder="Masukkan email Anda">
                    </div>

                    <input type="hidden" id="g-recaptcha-response" name="g-recaptcha-response">

                    <button type="submit" class="btn btn-submit text-white w-100 fw-semibold  rounded-3" id="submit-btn">
                        <span id="spinner" class="spinner-border spinner-border-sm d-none me-2 "
                            aria-hidden="true"></span>
                        Reset Password
                    </button>
                    <a href="{{ route('login') }}" class="btn btn-light border w-100 fw-semibold mt-2 rounded-3">Kembali ke
                        Login</a>
                </form>

            </div>
        </div>
    </div>

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
                        action: 'forgot_password'
                    }).then(function(token) {
                        document.getElementById('g-recaptcha-response').value = token;
                        form.submit();
                    });
                });
            });
        </script>
    @endpush
@endsection
