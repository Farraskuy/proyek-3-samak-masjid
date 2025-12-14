@extends('auth.layout')

@section('title', 'Verifikasi Kode')

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
        color: white;
    }

    .btn-submit:disabled {
        opacity: 0.8;
        color: white !important;
    }

    .form-control {
        padding: 14px;
        border-radius: 12px;
    }
</style>
@endpush

@section('content')
<div class="auth-wrapper p-0 p-md-4">
    <div class="form-container d-flex align-items-center justify-content-center h-100">

        <div class="w-100 bg-white rounded-4 shadow-sm p-4 d-flex flex-column gap-3 auth-card">

            {{-- LABEL HEADER ORANYE --}}
            <div class="mb-3 px-3 py-2 rounded-4 fw-semibold text-white"
                style="width: fit-content; background-color: #CE9138">
                Verifikasi
            </div>

            <h4 class="fw-semibold mb-1">Masukkan Kode Verifikasi</h4>
            <p class="text-muted mb-2">Kami telah mengirimkan kode verifikasi ke <strong>{{ $destination }}</strong>.</p>

            {{-- Error Message --}}
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- FORM --}}
            <form method="POST" action="{{ route('auth.verifyOtp') }}">
                @csrf

                <input type="hidden" name="destination" value="{{ $destination }}">
                <input type="hidden" name="type" value="{{ $type }}">

                {{-- Honeypot --}}
                <input type="text" name="hp_field" value="" style="display:none;">

                <div class="mb-3">
                    <label for="code" class="form-label">Kode Verifikasi</label>
                    <input id="code" name="code" type="text" autocomplete="one-time-code"
                        class="form-control @error('code') is-invalid @enderror" required>

                    @error('code')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn-submit">
                    <span class="spinner-border spinner-border-sm d-none me-2" id="spinner"></span>
                    Verifikasi
                </button>

                {{-- BACK --}}
                <a href="{{ route('login') }}" class="btn btn-light border w-100 fw-semibold mt-2 rounded-3">
                    Kembali ke Login
                </a>
            </form>

        </div>
    </div>
</div>
@endsection


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
(function () {
    const siteKey = '{{ $siteKey ?? '' }}';
    const recaptchaType = '{{ $recaptchaType }}';

    const form = document.querySelector('form[action="{{ route('auth.verifyOtp') }}"]');
    const btn = form.querySelector('button[type="submit"]');
    const spinner = document.getElementById('spinner');

    function showSpinner(show) {
        if (show) {
            spinner.classList.remove('d-none');
            btn.disabled = true;
        } else {
            spinner.classList.add('d-none');
            btn.disabled = false;
        }
    }

    // Tanpa Recaptcha
    if (!siteKey) {
        form.addEventListener('submit', () => showSpinner(true));
        return;
    }

    // Recaptcha V3
    if (recaptchaType === 'v3') {
        form.addEventListener('submit', async function (e) {
            e.preventDefault();
            showSpinner(true);

            await grecaptcha.ready();
            const token = await grecaptcha.execute(siteKey, { action: 'verify_otp' });

            const existing = form.querySelector('input[name="g-recaptcha-response"]');
            if (existing) existing.remove();

            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'g-recaptcha-response';
            input.value = token;
            form.appendChild(input);

            form.submit();
        });
    }

    // Recaptcha V2 Invisible
    else {
        let widgetId = null;
        const container = document.createElement('div');
        container.id = 'recaptcha-verify';
        container.style.display = 'none';
        document.body.appendChild(container);

        window.__verifyFormSubmit = function (token) {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'g-recaptcha-response';
            input.value = token;
            form.appendChild(input);
            form.submit();
        };

        const render = function () {
            if (typeof grecaptcha === 'undefined') return;
            if (widgetId !== null) return;

            widgetId = grecaptcha.render(container.id, {
                sitekey: siteKey,
                size: 'invisible',
                callback: '__verifyFormSubmit'
            });
        };

        window.addEventListener('load', render);
        setTimeout(render, 500);

        form.addEventListener('submit', function (e) {
            e.preventDefault();
            showSpinner(true);
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
