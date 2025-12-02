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

        if (!$this->validateCaptcha($request['g-recaptcha-response'], $request->ip())) {
            return back()->withErrors(['error' => 'Verifikasi reCAPTCHA gagal.'])->withInput();
        }

        $field = filter_var($data['login'], FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        $request->merge([$field => $data['login']]);


        if (Auth::attempt($request->only($field, 'password'))) {
            $request->session()->regenerate();
            if (Auth::user()->role == 'admin' || Auth::user()->role == 'super admin') {
                return redirect()->intended('/admin');
            }

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
            'username' => 'required|alpha_dash|max:32|unique:users,username',
            'email' => 'required|email|max:255|unique:users,email',
            'phone_number' => 'required|string|max:20|unique:users,phone_number',
            'password' => 'required|min:8',
            'password_confirmation' => 'required|same:password',
            'g-recaptcha-response' => 'required'
        ]);

        if (!$this->validateCaptcha($request['g-recaptcha-response'], $request->ip())) {
            return back()->withErrors(['error' => 'Verifikasi reCAPTCHA gagal.'])->withInput();
        }

        $user = User::create([
            'full_name' => $data['full_name'],
            'username' => $data['username'],
            'email' => $data['email'],
            'phone_number' => $data['phone_number'],
            'password' => Hash::make($data['password']),
            'role' => 'jamaah',
        ]);

        Auth::login($user);

        Otp::where('destination', $user->email)->delete();

        $code = rand(111111, 999999);

        Otp::create([
            'user_id' => $user->id,
            'destination' => $user->email,
            'type' => 'email',
            'code' => Hash::make($code),
            'attempts' => 0,
            'expires_at' => now()->addMinutes(10),
        ]);

        Mail::to($user->email)->queue(new OtpMail($code, $user->email));

        return view('auth.otp-sent', [
            'destination' => $user->email,
            'type' => 'email',
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
     * SEND OTP
     */
    public function sendOtp(Request $request)
    {
        $data = $request->validate([
            'destination' => 'required',
            'g-recaptcha-response' => 'required', // Temporarily disabled for profile verification flow or handle conditionally
        ]);

        $destination = $data['destination'];

        // Check if return URL is in request (e.g. from hidden input) or session
        if ($request->has('return_url')) {
            session(['otp_return_url' => $request->return_url]);
        }

        // OTP REPLACE (hapus OTP lama dan buat baru)
        Otp::where('destination', $destination)->delete();

        $code = rand(111111, 999999);

        Otp::create([
            'destination' => $destination,
            'user_id' => null,
            'type' => 'email',
            'code' => Hash::make($code),
            'attempts' => 0,
            'expires_at' => now()->addMinutes(10)
        ]);

        // Kirim OTP via Email
        try {
            Mail::to($destination)->queue(new OtpMail($code, $destination));
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Gagal mengirim email OTP.']);
        }

        return redirect()->route('auth.showVerifyForm', ['destination' => $destination])
            ->with('success', 'OTP telah dikirim ke ' . $destination);
    }

    /**
     * ReSEND
     */
    public function reSendOtp(Request $request)
    {
        $data = $request->validate([
            'destination' => 'required|email',
            'g-recaptcha-response' => 'required',
            'hp_field' => 'nullable|prohibited'
        ]);

        if (!$this->validateCaptcha($request['g-recaptcha-response'], $request->ip())) {
            return back()->withErrors(['error' => 'Verifikasi reCAPTCHA gagal.'])->withInput();
        }

        /*
        |--------------------------------------------------------------------------
        | SPAM PROTECTION (Rate Limit)
        |--------------------------------------------------------------------------
        */

        $spamKey = "otp:send:" . sha1($data['destination'] . '|' . $request->ip());

        if (RateLimiter::tooManyAttempts($spamKey, 3)) {
            $wait = RateLimiter::availableIn($spamKey);
            return back()->withErrors([
                'error' => "Terlalu banyak permintaan OTP. Coba lagi dalam {$wait} detik."
            ]);
        }

        RateLimiter::hit($spamKey, 60); // reset per 1 menit

        // OTP REPLACE (hapus OTP lama dan buat baru)
        Otp::where('destination', $data['destination'])->delete();

        $code = rand(111111, 999999);

        Otp::create([
            'destination' => $data['destination'],
            'user_id' => null,
            'type' => 'email',
            'code' => Hash::make($code),
            'attempts' => 0,
            'expires_at' => now()->addMinutes(10)
        ]);

        Mail::to($data['destination'])->queue(new OtpMail($code, $data['destination']));

        return redirect()->back()->with('success', 'OTP berhasil dikirim kembali.');
    }

    /**
     * VERIFY OTP
     */
    public function verifyOtp(Request $request)
    {
        $data = $request->validate([
            'destination' => 'required|email',
            'code' => 'required|numeric',
            'g-recaptcha-response' => 'required',
        ]);

        if (!$this->validateCaptcha($request['g-recaptcha-response'], $request->ip())) {
            return back()->withErrors(['error' => 'Verifikasi reCAPTCHA gagal.'])->withInput();
        }

        $email = $data['destination'];
        $inputCode = $data['code'];

        /*
        |--------------------------------------------------------------------------
        | BRUTE FORCE PROTECTION
        |--------------------------------------------------------------------------
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

        if (!$otp) {
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

        if (!Hash::check($inputCode, $otp->code)) {
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
            // Mark email as verified
            if (!$user->email_verified_at) {
                $user->email_verified_at = now();
                $user->save();
            }

            // If user is already logged in (e.g. verifying from profile)
            if (Auth::check()) {
                $returnUrl = session('otp_return_url');
                if ($returnUrl) {
                    session()->forget('otp_return_url');
                    return redirect($returnUrl)->with('success', 'Email berhasil diverifikasi.');
                }
                return redirect()->route('admin.profile.index')->with('success', 'Email berhasil diverifikasi.');
            }

            // If not logged in, login the user
            Auth::login($user);
            return redirect('/')->with('success', 'Verifikasi email berhasil!');
        }

        return redirect()->route('home')->with('success', 'Verifikasi berhasil! Silakan login.');
    }
}
