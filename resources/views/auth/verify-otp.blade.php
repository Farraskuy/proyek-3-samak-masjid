@extends('auth.layout')

@section('title', 'Verifikasi Kode - Digital Masjid')

@section('content')
    <div class="auth-wrapper p-4">
        <div class="form-container d-flex align-items-center justify-content-center h-100">
            <div class="w-100 bg-white rounded-4 shadow-sm p-4 d-flex flex-column gap-3 auth-card">
                <div class="mb-3 px-3 py-2 rounded-4 fw-semibold text-white" style="width: fit-content; background-color: #CE9138">
                    Verifikasi
                </div>

                <h4>Masukkan Kode Verifikasi</h4>

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('auth.verifyOtp') }}">
                    @csrf

                    <input type="hidden" name="destination" value="{{ $destination }}">
                    <input type="hidden" name="type" value="{{ $type }}">

                    {{-- Honeypot field (should remain empty) --}}
                    <input type="text" name="hp_field" value="" style="display:none;">

                    <div class="mb-3">
                        <label for="code" class="form-label">Kode Verifikasi</label>
                        <input id="code" name="code" type="text" autocomplete="one-time-code" class="form-control p-3 rounded-3 @error('code') is-invalid @enderror" required>
                        @error('code')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary w-100">
                        Verifikasi
                        <span class="spinner-border spinner-border-sm ms-2 d-none" role="status" aria-hidden="true"></span>
                    </button>
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
        (function(){
            const siteKey = '{{ $siteKey ?? '' }}';
            const recaptchaType = '{{ $recaptchaType }}';
            const form = document.querySelector('form[action="{{ route('auth.verifyOtp') }}"]');
            if (!form) return;

            const btn = form.querySelector('button[type="submit"]');
            const spinner = btn ? btn.querySelector('.spinner-border') : null;

            function showSpinner(show) {
                if (!spinner) return;
                if (show) {
                    spinner.classList.remove('d-none');
                    btn.setAttribute('disabled', 'disabled');
                } else {
                    spinner.classList.add('d-none');
                    btn.removeAttribute('disabled');
                }
            }

            if (!siteKey) {
                form.addEventListener('submit', function(){ showSpinner(true); });
                return;
            }

            if (recaptchaType === 'v3') {
                form.addEventListener('submit', async function(e){
                    e.preventDefault();
                    showSpinner(true);
                    try {
                        await grecaptcha.ready();
                        const token = await grecaptcha.execute(siteKey, {action: 'verify_otp'});
                        const existing = form.querySelector('input[name="g-recaptcha-response"]');
                        if (existing) existing.remove();
                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = 'g-recaptcha-response';
                        input.value = token;
                        form.appendChild(input);
                    } catch (err) {
                        console.warn('reCAPTCHA error', err);
                        showSpinner(false);
                    }
                    form.submit();
                });
            } else {
                // v2 invisible
                let widgetId = null;
                const recContainer = document.createElement('div');
                recContainer.style.display = 'none';
                recContainer.id = 'recaptcha-verify';
                document.body.appendChild(recContainer);

                window.__verifyFormSubmit = function(token) {
                    const existing = form.querySelector('input[name="g-recaptcha-response"]');
                    if (existing) existing.remove();
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'g-recaptcha-response';
                    input.value = token;
                    form.appendChild(input);
                    form.submit();
                };

                const render = function(){
                    if (typeof grecaptcha === 'undefined') return;
                    if (widgetId !== null) return;
                    widgetId = grecaptcha.render('recaptcha-verify', {
                        'sitekey': siteKey,
                        'size': 'invisible',
                        'callback': '__verifyFormSubmit'
                    });
                };
                setTimeout(render, 500);
                window.addEventListener('load', render);

                form.addEventListener('submit', function(e){
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
