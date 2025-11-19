<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Donasi\ZISController;
use App\Http\Controllers\Postingan\PostinganController;
use App\Http\Controllers\ManagementController;
use App\Http\Controllers\LostFoundController;
use App\Http\Controllers\KeuanganController;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\Donasi\Admin\BankController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Home
Route::get('/', [HomeController::class, 'index'])->name('home');

// Auth Routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');

Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,1');

Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');

Route::post('/register', [AuthController::class, 'register'])->middleware('throttle:3,1');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


// OTP Verification
Route::get('/auth/send-otp/sent/{destination}', [AuthController::class, 'sentOtp'])->name('auth.sentOtp');

Route::post('/auth/send-otp', [AuthController::class, 'sendOtp'])->middleware('throttle:3,1')->name('auth.sendOtp');

Route::get('/auth/verify', [AuthController::class, 'showVerifyForm'])->name('auth.showVerifyForm');

Route::post('/auth/verify', [AuthController::class, 'verifyOtp'])->middleware('throttle:5,1')->name('auth.verifyOtp');


// Forgot Password
Route::get('/forgot-password', [ForgotPasswordController::class, 'showForgotForm'])->name('password.request');

Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->middleware('throttle:3,1')->name('password.email');

Route::get('/forgot-password/sent', [ForgotPasswordController::class, 'showPasswordEmailsent'])->name('password.sent');


// Reset password routes
Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');

Route::post('/reset-password', [ResetPasswordController::class, 'reset'])->middleware('throttle:5,1') ->name('password.update');












// Home
Route::get('/', [HomeController::class, 'index'])->name('home');




// News Routes (client)
Route::prefix('postingan')->group(function () {
    Route::get('/', [PostinganController::class, 'index']);
    Route::get('/{slug}', [PostinganController::class, 'showDetail']);
});

// Admin News Management (use existing PostinganController)
Route::prefix('admin/postingan')->name('postingan.admin.')->group(function () {
    Route::get('/', [PostinganController::class, 'getEditArtikel'])->name('index');
    Route::get('/tambah', [PostinganController::class, 'create'])->name('create');
    Route::post('/posts', [PostinganController::class, 'store'])->name('store');
    Route::delete('/delete/{id}', [PostinganController::class, 'deleteArtikel'])->name('delete');

    Route::get('/edit/{id}',[PostinganController::class,'edit'])->name('edit');

    Route::put('/update/{id}', [PostinganController::class, 'update'])->name('update');
});

// Other Pages
Route::get('/donasi', [ZISController::class, 'index'])->name('donasi.informasi');
Route::get('/donasi/sekarang', [ZISController::class, 'donasi'])->name('donasi.sekarang');
Route::get('/layanan/barang-hilang', [LostFoundController::class, 'index'])->name('layanan.barang-hilang');

// Admin Panel
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Lost and Found Management
    Route::get('/barang-hilang', [LostFoundController::class, 'adminIndex'])->name('barang-hilang');
    Route::get('/barang-hilang/tambah', [LostFoundController::class, 'create'])->name('barang-hilang.tambah');
    Route::post('/barang-hilang', [LostFoundController::class, 'store'])->name('barang-hilang.store');
    Route::get('/barang-hilang/{id}/edit', [LostFoundController::class, 'edit'])->name('barang-hilang.edit');
    Route::put('/barang-hilang/{id}', [LostFoundController::class, 'update'])->name('barang-hilang.update');
    Route::delete('/barang-hilang/{id}', [LostFoundController::class, 'destroy'])->name('barang-hilang.destroy');

    //Donasi (Bank Controller)
    Route::resource('banks', BankController::class);
});

// Temporary/Test route
// Route::get('/aku/ini/test', [NewsController::class, 'index']);
Route::get('/aku/ini/test-email', function () {
    // return view('emails.reset-password', ['resetUrl' => 'https://example.com/reset-password']);
    Mail::to("gensinkn@gmail.com")->queue(new \App\Mail\ResetPasswordMail("1", "gensinkn@gmail.com"));
});
