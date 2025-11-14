<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http as HttpClient;
use App\Models\User; // Pastikan model User sudah ada
use App\Models\Otp;
use App\Services\NotificationService;
use App\Mail\OtpMail;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    // Tampilkan halaman login
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // Proses login
    public function login(Request $request)
    {
        $rules = [
            'login' => ['required'],
            'password' => ['required'],
        ];

        $messages = [
            'login.required' => 'Email atau username wajib diisi.',
            'password.required' => 'Kata sandi wajib diisi.',
        ];

        $attributes = [
            'login' => 'email atau username',
            'password' => 'kata sandi',
        ];

        $credentials = $request->validate($rules, $messages, $attributes);

        $field = filter_var($credentials['login'], FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        $request->merge([$field => $credentials['login']]);

        if (Auth::attempt($request->only($field, 'password'))) {
            $request->session()->regenerate();
            if (Auth::user()->role === 'super admin' || Auth::user()->role === 'admin') {
                return redirect()->intended('/admin');
            }
            return redirect()->intended('/');
        }

        return back()->withErrors([
            'error' => 'Kredensial yang Anda masukkan tidak valid.',
        ])->onlyInput('login');
    }

    // Tampilkan halaman register
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    // Proses registrasi
    public function register(Request $request)
    {
        $rules = [
            'full_name' => ['required', 'string', 'max:255'],
            'phone_number' => ['required', 'string', 'max:20'],
            'username' => ['required', 'string', 'max:32', 'unique:users,username'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'min:8', 'same:password-repeat'],
        ];

        $messages = [
            'full_name.required' => 'Nama lengkap wajib diisi.',
            'phone_number.required' => 'Nomor telepon wajib diisi.',
            'username.required' => 'Username wajib diisi.',
            'username.unique' => 'Username sudah digunakan, pilih yang lain.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah terdaftar.',
            'password.required' => 'Kata sandi wajib diisi.',
            'password.min' => 'Kata sandi minimal :min karakter.',
            'password.same' => 'Konfirmasi kata sandi tidak cocok.',
        ];

        $attributes = [
            'full_name' => 'nama lengkap',
            'phone_number' => 'nomor telepon',
            'username' => 'username',
            'email' => 'email',
            'password' => 'kata sandi',
            'password-repeat' => 'ulangi kata sandi',
        ];

        $validated = $request->validate($rules, $messages, $attributes);

        $user = User::create([
            'full_name' => $validated['full_name'],
            'role' => 'jamaah',
            'phone_number' => $validated['phone_number'],
            'username' => $validated['username'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        // After registration, require email verification: create OTP and queue email
        $code = random_int(100000, 999999);
        Otp::create([
            'user_id' => $user->id,
            'destination' => $user->email,
            'type' => 'email',
            'code' => (string) $code,
            'expires_at' => Carbon::now()->addMinutes(10),
        ]);

        // Queue email OTP
        // NotificationService::sendEmailOtp($user->email, $code);

        // Redirect user to OTP entry page
        // return view('auth.otp-sent', ['destination' => $user->email, 'type' => 'email']);

        return redirect()->to('login')->with('success', 'Registrasi berhasil. Silakan login.');
    }

    // Logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    // Show OTP verify form
    public function showVerifyForm(Request $request)
    {
        $destination = $request->get('destination');
        $type = $request->get('type');
        return view('auth.verify-otp', ['destination' => $destination, 'type' => $type]);
    }

    // Send OTP to email or phone
    public function sendOtp(Request $request)
    {
        $data = $request->validate([
            'destination' => 'required|string',
            'type' => 'required|in:email,phone',
            'hp_field' => 'nullable|prohibited', // honeypot: should not be present/filled
        ]);

        $destination = $data['destination'];
        $type = $data['type'];

        // If reCAPTCHA is configured, verify it server-side
        $recaptchaSecret = env('RECAPTCHA_SECRET');
        if ($recaptchaSecret) {
            $recaptchaResp = $request->input('g-recaptcha-response');
            if (! $recaptchaResp) {
                return back()->withErrors(['error' => 'reCAPTCHA verification required'])->withInput();
            }
            try {
                $verify = HttpClient::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
                    'secret' => $recaptchaSecret,
                    'response' => $recaptchaResp,
                    'remoteip' => $request->ip(),
                ]);
                $payload = $verify->json();
                if (empty($payload['success']) || $payload['success'] !== true) {
                    return back()->withErrors(['error' => 'reCAPTCHA verification failed'])->withInput();
                }
            } catch (\Exception $e) {
                Log::warning('reCAPTCHA verification failed: ' . $e->getMessage());
                return back()->withErrors(['error' => 'reCAPTCHA verification error'])->withInput();
            }
        }

        // Basic user-agent check to reduce bot abuse
        $ua = strtolower($request->header('User-Agent', ''));
        $botSignatures = ['bot', 'curl', 'spider', 'crawler', 'wget', 'python-requests'];
        foreach ($botSignatures as $sig) {
            if ($ua !== '' && strpos($ua, $sig) !== false) {
                return back()->withErrors(['error' => 'Permintaan tidak valid.'])->withInput();
            }
        }

        // Throttle by destination + ip (5 per hour)
        $key = 'otp-send:' . sha1($destination) . '|' . $request->ip();
        $max = 5;
        $decaySeconds = 3600; // 1 hour
        if (RateLimiter::tooManyAttempts($key, $max)) {
            $retry = RateLimiter::availableIn($key);
            return back()->withErrors(['error' => "Terlalu banyak permintaan. Coba lagi setelah {$retry} detik."])->withInput();
        }

        RateLimiter::hit($key, $decaySeconds);

        // generate 6-digit code
        $code = random_int(100000, 999999);

        Otp::create([
            'user_id' => null,
            'destination' => $destination,
            'type' => $type,
            'code' => (string) $code,
            'expires_at' => Carbon::now()->addMinutes(10),
        ]);

        $sent = false;
        if ($type === 'email') {
            // NotificationService will dispatch a SendOtpEmailJob
            $sent = NotificationService::sendEmailOtp($destination, $code);
        } else {
            $message = "Kode verifikasi Samak Masjid: {$code} (berlaku 10 menit)";
            $sent = NotificationService::sendSms($destination, $message);
        }

        if (! $sent) {
            return back()->withErrors(['error' => 'Gagal mengirim kode verifikasi. Coba lagi nanti.']);
        }

        return view('auth.otp-sent', ['destination' => $destination, 'type' => $type]);
    }

    // Verify OTP
    public function verifyOtp(Request $request)
    {
        $data = $request->validate([
            'destination' => 'required|string',
            'type' => 'required|in:email,phone',
            'code' => 'required|string',
            'hp_field' => 'nullable|prohibited',
        ]);

        // server-side reCAPTCHA verification (if configured)
        $recaptchaSecret = env('RECAPTCHA_SECRET');
        if ($recaptchaSecret) {
            $recaptchaResp = $request->input('g-recaptcha-response');
            if (! $recaptchaResp) {
                return back()->withErrors(['error' => 'reCAPTCHA verification required'])->withInput();
            }
            try {
                $verify = HttpClient::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
                    'secret' => $recaptchaSecret,
                    'response' => $recaptchaResp,
                    'remoteip' => $request->ip(),
                ]);
                $payload = $verify->json();
                if (empty($payload['success']) || $payload['success'] !== true) {
                    return back()->withErrors(['error' => 'reCAPTCHA verification failed'])->withInput();
                }
            } catch (\Exception $e) {
                Log::warning('reCAPTCHA verification failed: ' . $e->getMessage());
                return back()->withErrors(['error' => 'reCAPTCHA verification error'])->withInput();
            }
        }

        $destination = $data['destination'];
        $type = $data['type'];
        $code = trim($data['code']);

        $otp = Otp::where('destination', $destination)
            ->where('type', $type)
            ->where('used', false)
            ->orderByDesc('created_at')
            ->first();

        if (! $otp) {
            return back()->withErrors(['code' => 'Kode tidak ditemukan atau sudah digunakan.']);
        }

        if ($otp->isExpired()) {
            return back()->withErrors(['code' => 'Kode telah kadaluarsa. Silakan minta kode baru.']);
        }

        // lock after 5 wrong attempts for this OTP
        if ($otp->attempts >= 5) {
            return back()->withErrors(['code' => 'Terlalu banyak percobaan. Kode diblokir.']);
        }

        if (! hash_equals($otp->code, $code)) {
            $otp->attempts = $otp->attempts + 1;
            $otp->save();
            return back()->withErrors(['code' => 'Kode tidak cocok.']);
        }

        // success
        $otp->markUsed();

        // If user exists with this destination and type=email, mark email_verified_at
        if ($type === 'email') {
            $user = User::where('email', $destination)->first();
            if ($user) {
                if (Schema::hasColumn('users', 'email_verified_at')) {
                    $user->email_verified_at = Carbon::now();
                    $user->save();
                }
                Auth::login($user);
            }
        } else {
            // phone verification: update phone_verified_at (migration adds column)
            $user = User::where('phone_number', $destination)->first();
            if ($user) {
                $user->phone_verified_at = Carbon::now();
                $user->save();
            }
        }

        return redirect('/')->with('success', 'Verifikasi berhasil.');
    }
}
