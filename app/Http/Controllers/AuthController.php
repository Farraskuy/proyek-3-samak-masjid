<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User; // Pastikan model User sudah ada

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

        Auth::login($user);

        return redirect()->route('/')->with('success', 'Registrasi berhasil! Selamat datang di Digital Masjid.');
    }

    // Logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
