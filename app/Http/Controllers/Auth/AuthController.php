<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Traits\VerifyCaptcha;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Otp;
use App\Mail\OtpMail;

class AuthController extends Controller
{
    use VerifyCaptcha;

    /**
     * LOGIN
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $data = $request->validate([
            'login' => 'required',
            'password' => 'required',
            'g-recaptcha-response' => 'required'
        ]);

        if (! $this->validateCaptcha($request['g-recaptcha-response'], $request->ip())) {

            return back()->withErrors(['error' => 'Verifikasi reCAPTCHA gagal.'])->withInput();
        }


        $field = filter_var($data['login'], FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        $request->merge([$field => $data['login']]);


        if (Auth::attempt($request->only($field, 'password'))) {

            $request->session()->regenerate();
            return redirect()->intended('/');
        }

        return back()->withErrors([
            'error' => 'Email/username atau password salah.'
        ])->onlyInput('login');
    }

    /**
     * REGISTER
     */
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'full_name' => 'required|string|max:255',
            'username'  => 'required|alpha_dash|max:32|unique:users,username',
            'email'     => 'required|email|max:255|unique:users,email',
            'phone_number' => 'required|string|max:20|unique:users,phone_number',
            'password'  => 'required|min:8',
            'password_confirmation' => 'required|same:password',
            'g-recaptcha-response' => 'required'
        ]);

        if (! $this->validateCaptcha($request['g-recaptcha-response'], $request->ip())) {
            return back()->withErrors(['error' => 'Verifikasi reCAPTCHA gagal.'])->withInput();
        }


        $ip = $request->ip();
        $key = 'register:' . sha1($ip);

        if (RateLimiter::tooManyAttempts($key, 5)) {
            return back()->withErrors([
                'error' => "Terlalu banyak percobaan. Coba lagi dalam beberapa menit."
            ]);
        }
        RateLimiter::hit($key, 60); // 1 menit

        $user = User::create([
            'full_name'     => $data['full_name'],
            'username'      => $data['username'],
            'email'         => $data['email'],
            'phone_number'  => $data['phone_number'],
            'password'      => Hash::make($data['password']),
            'role'          => 'jamaah',
        ]);

        Otp::where('destination', $user->email)->delete();


        if ($user->otp_blocked_until && now()->lt($user->otp_blocked_until)) {
            return back()->withErrors([
                'error' => 'Akun Anda diblokir sementara karena terlalu banyak percobaan OTP. Silakan coba lagi nanti.'
            ]);
        }

        $code = rand(111111, 999999);

        Otp::create([
            'user_id'     => $user->id,
            'destination' => $user->email,
            'type'        => 'email',
            'code'        => Hash::make($code),
            'attempts'    => 0,
            'expires_at'  => now()->addMinutes(10),
        ]);

        Mail::to($user->email)->queue(new OtpMail($code, $user->email));

        return view('auth.otp-sent', [
            'destination' => $user->email,
            'type'        => 'email',
        ]);
    }



    /**
     * LOGOUT
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

    /**
     * SHOW OTP FORM
     */
    public function showVerifyForm(Request $request)
    {
        return view('auth.verify-otp', [
            'destination' => $request->destination,
            'type' => 'email'
        ]);
    }

    /**
     * Show otp sent message
     */
    public function sentOtp($destination)
    {
        return view('auth.otp-sent', [
            'destination' => $destination
        ]);
    }


    /**
     * SEND / RESEND OTP
     */
    public function sendOtp(Request $request)
    {
        $data = $request->validate([
            'destination' => 'required|email',
            'g-recaptcha-response' => 'required',
            'hp_field' => 'nullable|prohibited'
        ]);

        if (! $this->validateCaptcha($request['g-recaptcha-response'], $request->ip())) {
            return back()->withErrors(['error' => 'Verifikasi reCAPTCHA gagal.'])->withInput();
        }

        /*
        |--------------------------------------------------------------------------
        | SPAM PROTECTION (Rate Limit)
        |--------------------------------------------------------------------------
        |
        | Mencegah user meminta OTP terlalu sering.
        | Limit: 3 OTP per menit per email+IP.
        |
        */

        $spamKey = "otp:send:" . sha1($data['destination'] . '|' . $request->ip());

        if (RateLimiter::tooManyAttempts($spamKey, 3)) {
            $wait = RateLimiter::availableIn($spamKey);
            return back()->withErrors([
                'error' => "Terlalu banyak permintaan OTP. Coba lagi dalam {$wait} detik."
            ]);
        }

        RateLimiter::hit($spamKey, 60); // reset per 1 menit

        /*
        |--------------------------------------------------------------------------
        | OTP REPLACE (hapus OTP lama dan buat baru)
        |--------------------------------------------------------------------------
        */

        Otp::where('destination', $data['destination'])->delete();

        $code = rand(111111, 999999);

        Otp::create([
            'destination' => $data['destination'],
            'user_id'     => null,
            'type'        => 'email',
            'code'        => Hash::make($code),
            'attempts'    => 0,
            'expires_at'  => now()->addMinutes(10)
        ]);

        Mail::to($data['destination'])->queue(new OtpMail($code, $data['destination']));

        return view('auth.otp-sent', [
            'destination' => $data['destination'],
            'type' => 'email'
        ]);
    }

    /**
     * VERIFY OTP
     */
    public function verifyOtp(Request $request)
    {
        $data = $request->validate([
            'destination' => 'required|email',
            'code'        => 'required|numeric',
            'g-recaptcha-response' => 'required',
        ]);

        if (! $this->validateCaptcha($request['g-recaptcha-response'], $request->ip())) {
            return back()->withErrors(['error' => 'Verifikasi reCAPTCHA gagal.'])->withInput();
        }

        $email = $data['destination'];
        $inputCode = $data['code'];

        /*
        |--------------------------------------------------------------------------
        | BRUTE FORCE PROTECTION
        |--------------------------------------------------------------------------
        |
        | Jika user salah OTP lebih dari 5x → blok 30 menit
        |
        */

        $blockKey = "otp:block:" . $email;

        if (RateLimiter::tooManyAttempts($blockKey, 1)) {
            return back()->withErrors([
                'code' => 'Terlalu banyak percobaan gagal. Anda diblokir 30 menit.'
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | GET OTP
        |--------------------------------------------------------------------------
        */

        $otp = Otp::where('destination', $email)->first();

        if (! $otp) {
            return back()->withErrors(['code' => 'OTP tidak ditemukan.']);
        }

        if (now()->greaterThan($otp->expires_at)) {
            $otp->delete();
            return back()->withErrors(['code' => 'OTP sudah kadaluarsa.']);
        }

        /*
        |--------------------------------------------------------------------------
        | WRONG CODE
        |--------------------------------------------------------------------------
        */

        if (! Hash::check($inputCode, $otp->code)) {
            $otp->increment('attempts');

            if ($otp->attempts >= 5) {
                RateLimiter::hit($blockKey, 1800); // block 30 minutes
                return back()->withErrors(['code' => 'Terlalu banyak percobaan salah. Anda diblokir.']);
            }

            return back()->withErrors(['code' => 'Kode OTP salah.']);
        }

        /*
        |--------------------------------------------------------------------------
        | SUCCESS: DELETE OTP & VERIFY USER
        |--------------------------------------------------------------------------
        */

        $otp->delete();

        $user = User::where('email', $email)->first();
        if ($user) {
            $user->email_verified_at = now();
            $user->save();
            Auth::login($user);
        }

        return redirect('/')->with('success', 'Verifikasi email berhasil!');
    }
}
