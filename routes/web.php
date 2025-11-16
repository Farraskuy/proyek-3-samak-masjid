<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Keuangan\ZISController;
use App\Http\Controllers\Layanan\LostFoundController;
use App\Http\Controllers\News\HalamanPostinganController;
use App\Http\Controllers\News\Postingan;
use App\Http\Controllers\News\ShowNews;
use App\Http\Controllers\News\AddNewsController;
use App\Http\Controllers\News\DetailNewsController;
use Illuminate\Support\Facades\Mail;

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




// News Routes
Route::prefix('postingan')->group(function () {
    Route::get('/', [HalamanPostinganController::class, 'return_resource']);
    Route::get('/{id}', [DetailNewsController::class, 'return_resource']);
});

// Admin News Management
Route::prefix('admin/artikel')->name('artikel.')->group(function () {
    Route::get('/', [ShowNews::class, 'getEditArtikel']);
    Route::get('/tambah', [AddNewsController::class, 'return_resource']);
    Route::post('/posts', [AddNewsController::class, 'upload']);
    Route::delete('/delete/{id}', [ShowNews::class, 'deleteArtikel'])->name('delete');
    // Route::get('/delete/storage/{id}', [ShowNews::class, 'search_delete_featured_image']); // test only
});

// Other Pages
Route::get('/donasi', [ZISController::class, 'index'])->name('informasi.rekening');
Route::get('/layanan/barang-hilang', [LostFoundController::class, 'index'])->name('layanan.barang-hilang');

// Admin Panel
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Lost and Found Management
    Route::get('/barang-hilang', [LostFoundController::class, 'adminIndex'])->name('barang-hilang');
    Route::get('/barang-hilang/tambah', [LostFoundController::class, 'create'])->name('barang-hilang.tambah');
    Route::post('/barang-hilang', [LostFoundController::class, 'store']);
});

// Temporary/Test route
Route::get('/aku/ini/test', [DetailNewsController::class, 'return_resource']);
Route::get('/aku/ini/test-email', function () {
    // return view('emails.reset-password', ['resetUrl' => 'https://example.com/reset-password']);
    Mail::to("gensinkn@gmail.com")->queue(new \App\Mail\ResetPasswordMail("1", "gensinkn@gmail.com"));
});
