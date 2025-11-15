<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Http as HttpClient;
use Illuminate\Support\Str;
use App\Models\User;
use App\Traits\VerifyCaptcha;

class ResetPasswordController extends Controller
{
    use VerifyCaptcha;

    // Tampilkan form reset password
    public function showResetForm(Request $request, $token = null)
    {
        $email = $request->query('email');
        return view('auth.reset-password', ['token' => $token, 'email' => $email]);
    }

    // Proses reset password
    public function reset(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email|exists:users,email',
            'password' => 'required|min:8|confirmed',
            'g-recaptcha-response' => 'required',
        ]);

        if (! $this->validateCaptcha($request['g-recaptcha-response'], $request->ip())) {
            return back()->withErrors(['error' => 'Verifikasi reCAPTCHA gagal.'])->withInput();
        }

        // Anti-spam: rate limit, honeypot, user-agent, reCAPTCHA
        $ip = $request->ip();

        $email = $request->input('email');

        $userAgent = $request->header('User-Agent');

        $key = 'reset-password:' . sha1($ip . '|' . $email);

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

        // Reset password
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->password = Hash::make($password);
                $user->setRememberToken(Str::random(60));
                $user->save();
            }
        );

        if ($status === Password::PASSWORD_RESET) {

            return redirect()->route('login')->with('status', 'Password berhasil direset. Silakan login.');
        }
        return back()->withErrors(['error' => 'Gagal reset password. Token tidak valid atau sudah kadaluarsa.']);
    }
}
