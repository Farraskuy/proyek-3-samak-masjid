<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Traits\VerifyCaptcha;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http as HttpClient;
use App\Mail\ResetPasswordMail;
use App\Models\User;
use Carbon\Carbon;

class ForgotPasswordController extends Controller
{
    use VerifyCaptcha;

    // Tampilkan form lupa password
    public function showForgotForm()
    {
        return view('auth.forgot-password');
    }

    public function showPasswordEmailsent()
    {
        return view('auth.password-sent');
    }

    // Proses request reset password
    public function sendResetLinkEmail(Request $request)
    {

        $request->validate([
            'email' => 'required|email|exists:users,email',
            'g-recaptcha-response' => 'required',
        ]);

        if (! $this->validateCaptcha($request['g-recaptcha-response'], $request->ip())) {
            return back()->withErrors(['error' => 'Verifikasi reCAPTCHA gagal.'])->withInput();
        }

        // Anti-spam: rate limit, honeypot, user-agent, reCAPTCHA
        $ip = $request->ip();

        $email = $request->input('email');

        $userAgent = $request->header('User-Agent');

        $key = 'forgot-password:' . sha1($ip . '|' . $email);


        if (RateLimiter::tooManyAttempts($key, 5)) {

            return back()->withErrors(['error' => 'Terlalu banyak percobaan. Silakan coba lagi nanti.']);
        }


        RateLimiter::hit($key, 60);
        if ($request->filled('website')) {

            return back()->withErrors(['error' => 'Permintaan tidak valid.']);
        }


        if (!$userAgent || strlen($userAgent) < 10) {

            return back()->withErrors(['error' => 'Permintaan tidak valid.']);
        }

        // Kirim link reset password dengan custom mailable (queued)
        $user = User::where('email', $email)->first();
        if ($user) {
            $token = app('auth.password.broker')->createToken($user);
            Mail::to($user->email)->queue(new \App\Mail\ResetPasswordMail($token, $user->email));
            return redirect()->route('password.sent');
        }
        return back()->withErrors(['error' => 'Gagal mengirim link reset password.']);
    }
}
