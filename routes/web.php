<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Donasi\ZISController;
use App\Http\Controllers\PostinganController;
use App\Http\Controllers\ManagementController;
use App\Http\Controllers\LostFoundController;
use App\Http\Controllers\KeuanganController;
use App\Http\Controllers\DonasiController;
use App\Http\Controllers\GaleriController;
use App\Http\Controllers\KegiatanController;
use App\Http\Controllers\KajianController;
use App\Http\Controllers\PenggunaController;
use App\Http\Controllers\KonsultasiController;
use App\Http\Controllers\ClientConsultationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\FormBuilderController;
use App\Http\Controllers\StaticPageController;
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

Route::post('/reset-password', [ResetPasswordController::class, 'reset'])->middleware('throttle:5,1')->name('password.update');












// Home
Route::get('/', [HomeController::class, 'index'])->name('home');




// News Routes (client)
Route::prefix('postingan')->name('client.')->group(function () {
    Route::get('/', [PostinganController::class, 'index'])->name('berita');
    Route::get('/{slug}', [PostinganController::class, 'showDetail'])->name('berita.detail');
});

// Admin News Management (use existing PostinganController)
Route::prefix('admin/postingan')->name('postingan.admin.')->group(function () {
    Route::get('/', [PostinganController::class, 'indexAdmin'])->name('index');
    Route::get('/tambah', [PostinganController::class, 'create'])->name('create');
    Route::post('/posts', [PostinganController::class, 'store'])->name('store');
    Route::delete('/delete/{id}', [PostinganController::class, 'deleteArtikel'])->name('delete');

    Route::get('/edit/{id}', [PostinganController::class, 'edit'])->name('edit');

    Route::put('/update/{id}', [PostinganController::class, 'update'])->name('update');
    // Approval workflow for super-admin
    Route::get('/approval', [PostinganController::class, 'approvalIndex'])->name('approval.index');
    Route::get('/approval/{id}', [PostinganController::class, 'approvalShow'])->name('approval.show');
    Route::post('/approval/{id}', [PostinganController::class, 'approvalUpdate'])->name('approval.update');
});

// Other Pages
Route::get('/donasi', [ZISController::class, 'index'])->name('donasi.informasi');
Route::get('/donasi/sekarang', [ZISController::class, 'donasi'])->name('donasi.sekarang');
Route::get('/layanan/barang-hilang', [LostFoundController::class, 'index'])->name('layanan.barang-hilang');
Route::get('/tentang-kami', [StaticPageController::class, 'showAboutUs'])->name('client.tentang-kami');

// Public Form Routes (client)
Route::get('/form/{slug}', [FormBuilderController::class, 'show'])->name('form.show');
Route::post('/form/{slug}/submit', [FormBuilderController::class, 'submit'])->name('form.submit');

// Client Consultation Routes
Route::middleware('auth')->prefix('konsultasi-saya')->name('client.consultations.')->group(function () {
    Route::get('/', [ClientConsultationController::class, 'index'])->name('index');
    Route::get('/buat', [ClientConsultationController::class, 'create'])->name('create');
    Route::post('/', [ClientConsultationController::class, 'store'])->name('store');
    Route::get('/{id}', [ClientConsultationController::class, 'show'])->name('show');
    Route::post('/{id}/pesan', [ClientConsultationController::class, 'sendMessage'])->name('send-message');
    Route::get('/{id}/pesan', [ClientConsultationController::class, 'getMessages'])->name('get-messages');
    Route::post('/{id}/tutup', [ClientConsultationController::class, 'close'])->name('close');
    Route::delete('/{id}', [ClientConsultationController::class, 'delete'])->name('delete');
});

// User Profile Routes
Route::middleware('auth')->prefix('profil')->name('profile.')->group(function () {
    Route::get('/', [ProfileController::class, 'show'])->name('show');
    Route::get('/edit', [ProfileController::class, 'edit'])->name('edit');
    Route::put('/', [ProfileController::class, 'update'])->name('update');
    Route::put('/password', [ProfileController::class, 'changePassword'])->name('change-password');
    Route::get('/preferensi', [ProfileController::class, 'preferences'])->name('preferences');
    Route::put('/preferensi', [ProfileController::class, 'updatePreferences'])->name('update-preferences');
});

// Admin Panel
Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Lost and Found Management
    Route::get('/barang-hilang', [LostFoundController::class, 'adminIndex'])->name('barang-hilang');
    Route::get('/barang-hilang/tambah', [LostFoundController::class, 'create'])->name('barang-hilang.tambah');
    Route::post('/barang-hilang', [LostFoundController::class, 'store'])->name('barang-hilang.store');
    Route::get('/barang-hilang/{id}/edit', [LostFoundController::class, 'edit'])->name('barang-hilang.edit');
    Route::put('/barang-hilang/{id}', [LostFoundController::class, 'update'])->name('barang-hilang.update');
    Route::delete('/barang-hilang/{id}', [LostFoundController::class, 'destroy'])->name('barang-hilang.destroy');

    // Admin feature indexes (sidebar)
    Route::get('/galeri', [GaleriController::class, 'index'])->name('galeri');
    Route::get('/kegiatan', [KegiatanController::class, 'index'])->name('kegiatan');
    Route::get('/donasi/verifikasi', [DonasiController::class, 'index'])->name('donasi.verifikasi');
    Route::get('/keuangan', [KeuanganController::class, 'index'])->name('keuangan');
    Route::get('/kajian', [KajianController::class, 'index'])->name('kajian');
    Route::get('/pengguna', [PenggunaController::class, 'index'])->name('pengguna');
    Route::get('/konsultasi', [KonsultasiController::class, 'index'])->name('konsultasi');
    Route::get('/konsultasi/{id}', [KonsultasiController::class, 'show'])->name('konsultasi.show');
    Route::post('/konsultasi/{id}/answer', [KonsultasiController::class, 'answer'])->name('konsultasi.answer');
    Route::post('/konsultasi/{id}/reject', [KonsultasiController::class, 'reject'])->name('konsultasi.reject');
    Route::post('/konsultasi/{id}/close', [KonsultasiController::class, 'close'])->name('konsultasi.close');
    Route::post('/konsultasi/{id}/status', [KonsultasiController::class, 'updateStatus'])->name('konsultasi.status');
    Route::delete('/konsultasi/{id}', [KonsultasiController::class, 'destroy'])->name('konsultasi.destroy');

    // Static Pages Management
    Route::get('/halaman-statis', [StaticPageController::class, 'indexAdmin'])->name('static-pages.index');
    Route::get('/halaman-statis/{id}/edit', [StaticPageController::class, 'edit'])->name('static-pages.edit');
    Route::put('/halaman-statis/{id}', [StaticPageController::class, 'update'])->name('static-pages.update');

    // Form Builder / Form Management
    Route::get('/forms', [FormBuilderController::class, 'index'])->name('forms.index');
    Route::get('/forms/create', [FormBuilderController::class, 'create'])->name('forms.create');
    Route::post('/forms', [FormBuilderController::class, 'store'])->name('forms.store');
    Route::get('/forms/{id}/edit', [FormBuilderController::class, 'edit'])->name('forms.edit');
    Route::put('/forms/{id}', [FormBuilderController::class, 'update'])->name('forms.update');
    Route::delete('/forms/{id}', [FormBuilderController::class, 'destroy'])->name('forms.destroy');

    // Responses
    Route::get('/forms/{id}/responses', [FormBuilderController::class, 'responses'])->name('forms.responses');
    Route::get('/forms/{formId}/responses/{responseId}', [FormBuilderController::class, 'responseShow'])->name('forms.responses.show');
    Route::delete('/forms/{formId}/responses/{responseId}', [FormBuilderController::class, 'responseDelete'])->name('forms.responses.delete');

    //Donasi (Bank Controller)
    Route::resource('banks', BankController::class);
});

// Temporary/Test route
// Route::get('/aku/ini/test', [NewsController::class, 'index']);
Route::get('/aku/ini/test-email', function () {
    // return view('emails.reset-password', ['resetUrl' => 'https://example.com/reset-password']);
    Mail::to("gensinkn@gmail.com")->queue(new \App\Mail\ResetPasswordMail("1", "gensinkn@gmail.com"));
});
