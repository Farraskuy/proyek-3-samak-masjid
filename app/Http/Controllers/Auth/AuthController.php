<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Role;
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
use Session;

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
            'role' => Role::where('name', 'jamaah')->first()->id,
        ]);

        Session::put('otp_return_url', route('home'));

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

        // Redirect directly to verify form with encrypted email (hash)
        $hash = \Illuminate\Support\Facades\Crypt::encryptString($user->email);
        return redirect()->route('auth.showVerifyForm', ['hash' => $hash]);
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
        $destination = null;
        if ($request->has('hash')) {
            try {
                $destination = \Illuminate\Support\Facades\Crypt::decryptString($request->hash);
            } catch (\Exception $e) {
                abort(404, 'Invalid Link');
            }
        } else {
            abort(404);
        }

        $lastOtp = Otp::where('destination', $destination)->latest()->first();
        $secondsRemaining = 0;
        if ($lastOtp) {
            $secondsSinceCreated = $lastOtp->created_at->diffInSeconds(now(), false);

            if ($secondsSinceCreated < 0) {
                $secondsSinceCreated = 0;
            }

            if ($secondsSinceCreated < 60) {
                $secondsRemaining = 60 - $secondsSinceCreated;
            }
        }

        return view('auth.verify-otp', [
            'destination' => $destination,
            'hash' => $request->hash,
            'type' => 'email',
            'secondsRemaining' => (int) $secondsRemaining
        ]);
    }

    /**
     * SEND OTP
     */
    public function sendOtp(Request $request)
    {
        $data = $request->validate([
            'destination' => 'required',
            'g-recaptcha-response' => 'required',
        ]);

        $destination = $data['destination'];

        if ($request->has('return_url')) {
            session(['otp_return_url' => $request->return_url]);
        }

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

        try {
            Mail::to($destination)->queue(new OtpMail($code, $destination));
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Gagal mengirim email OTP.']);
        }

        $hash = \Illuminate\Support\Facades\Crypt::encryptString($destination);
        return redirect()->route('auth.showVerifyForm', ['hash' => $hash])->with('success', 'OTP telah dikirim ke ' . $destination);
    }

    /**
     * ReSEND
     */
    public function reSendOtp(Request $request)
    {
        $data = $request->validate([
            'hash' => 'required',
            'g-recaptcha-response' => 'required',
        ]);

        try {
            $destination = \Illuminate\Support\Facades\Crypt::decryptString($data['hash']);
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Invalid Request']);
        }

        if (!$this->validateCaptcha($request['g-recaptcha-response'], $request->ip())) {
            return back()->withErrors(['error' => 'Verifikasi reCAPTCHA gagal.'])->withInput();
        }

        // Backend Timer Check
        $lastOtp = Otp::where('destination', $destination)->latest()->first();
        if ($lastOtp) {
            $secondsSinceCreated = $lastOtp->created_at->diffInSeconds(now(), false);

            if ($secondsSinceCreated < 0) {
                $secondsSinceCreated = 0;
            }

            if ($secondsSinceCreated < 60) {
                $wait = (int) (60 - $secondsSinceCreated);
                return back()->withErrors([
                    'error' => "Tunggu {$wait} detik sebelum mengirim ulang."
                ]);
            }
        }

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

        Mail::to($destination)->queue(new OtpMail($code, $destination));

        return redirect()->back()->with('success', 'OTP berhasil dikirim kembali.');
    }

    /**
     * VERIFY OTP
     */
    public function verifyOtp(Request $request)
    {
        $data = $request->validate([
            'hash' => 'required',
            'code' => 'required|numeric',
            'g-recaptcha-response' => 'required',
        ]);

        try {
            $email = \Illuminate\Support\Facades\Crypt::decryptString($data['hash']);
        } catch (\Exception $e) {
            return back()->withErrors(['code' => 'Invalid Request']);
        }

        if (!$this->validateCaptcha($request['g-recaptcha-response'], $request->ip())) {
            return back()->withErrors(['error' => 'Verifikasi reCAPTCHA gagal.'])->withInput();
        }

        $inputCode = $data['code'];

        $otp = Otp::where('destination', $email)->first();

        if (!$otp) {
            return back()->withErrors(['code' => 'OTP tidak ditemukan.']);
        }

        if (now()->greaterThan($otp->expires_at)) {
            $otp->delete();
            return back()->withErrors(['code' => 'OTP sudah kadaluarsa.']);
        }

        if (!Hash::check($inputCode, $otp->code)) {
            $otp->increment('attempts');
            return back()->withErrors(['code' => 'Kode OTP salah.']);
        }

        $otp->delete();

        $user = User::where('email', $email)->first();

        if ($user) {
            if (!$user->email_verified_at) {
                $user->email_verified_at = now();
                $user->save();
            }

            if (Auth::check()) {
                $returnUrl = session('otp_return_url');
                if ($returnUrl) {
                    session()->forget('otp_return_url');
                    return redirect($returnUrl)->with('success', 'Email berhasil diverifikasi.');
                }
                return redirect()->route('admin.profile.index')->with('success', 'Email berhasil diverifikasi.');
            }

            Auth::login($user);
            return redirect('/')->with('success', 'Verifikasi email berhasil!');
        }

        return redirect()->route('home')->with('success', 'Verifikasi berhasil! Silakan login.');
    }
}
