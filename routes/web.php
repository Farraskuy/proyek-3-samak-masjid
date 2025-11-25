<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Broadcast;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PostinganController;
use App\Http\Controllers\LostFoundController;
use App\Http\Controllers\KeuanganController;
use App\Http\Controllers\FormBuilderController;
use App\Http\Controllers\StaticPageController;
use App\Http\Controllers\KonsultasiController;
use App\Http\Controllers\ClientConsultationController;
use App\Http\Controllers\ConsultationClientController;
use App\Http\Controllers\ConsultationUstadzController;
use App\Http\Controllers\Donasi\ZISController;
use App\Http\Controllers\JadwalKegiatan\JadwalKegiatanController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GaleriController;
use App\Http\Controllers\KegiatanController;
use App\Http\Controllers\KajianController;
use App\Http\Controllers\PenggunaController;
use App\Http\Controllers\Admin\AdminProfileController;
use App\Http\Controllers\JadwalKegiatan\AdminKegiatanController;
use App\Http\Controllers\Donasi\Admin\BankController;
use App\Http\Controllers\Donasi\Admin\DonationConfirmationController;
use App\Http\Controllers\DonasiController;
use App\Http\Controllers\ProfileController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// =============================== Home ===============================
Route::get('/', [HomeController::class, 'index'])->name('home');


// =============================== Authentication ===============================

// Login / Register / Logout
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,1');

Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->middleware('throttle:3,1');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// OTP
Route::get('/auth/send-otp/sent/{destination}', [AuthController::class, 'sentOtp'])->name('auth.sentOtp');
Route::post('/auth/send-otp', [AuthController::class, 'sendOtp'])->middleware('throttle:3,1')->name('auth.sendOtp');
Route::post('/auth/resend-otp', [AuthController::class, 'sendOtp'])->middleware('throttle:3,1')->name('auth.resendOtp');
Route::get('/auth/verify', [AuthController::class, 'showVerifyForm'])->name('auth.showVerifyForm');
Route::post('/auth/verify', [AuthController::class, 'verifyOtp'])->middleware('throttle:5,1')->name('auth.verifyOtp');

// Forgot Password
Route::get('/forgot-password', [ForgotPasswordController::class, 'showForgotForm'])->name('password.request');
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->middleware('throttle:3,1')->name('password.email');
Route::get('/forgot-password/sent', [ForgotPasswordController::class, 'showPasswordEmailsent'])->name('password.sent');

// Reset Password
Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [ResetPasswordController::class, 'reset'])->middleware('throttle:5,1')->name('password.update');


// =============================== Notification API ===============================
Broadcast::routes(['middleware' => ['auth:sanctum']]);

Route::middleware('auth')->get('/api/notifications', function () {
    return \App\Models\Notification::where('user_id', Auth::id())
        ->orderBy('created_at', 'desc')
        ->limit(10)
        ->get();
});


// =============================== News Routes (Client) ===============================
Route::prefix('postingan')->name('client.')->group(function () {
    Route::get('/', [PostinganController::class, 'index'])->name('berita');
    Route::get('/{slug}', [PostinganController::class, 'showDetail'])->name('berita.detail');
});


// =============================== News Routes (Admin) ===============================
Route::prefix('admin/postingan')->name('postingan.admin.')->group(function () {
    Route::get('/', [PostinganController::class, 'indexAdmin'])->name('index');
    Route::get('/tambah', [PostinganController::class, 'create'])->name('create');
    Route::post('/posts', [PostinganController::class, 'store'])->name('store');
    Route::delete('/delete/{id}', [PostinganController::class, 'deleteArtikel'])->name('delete');

    Route::get('/edit/{id}', [PostinganController::class, 'edit'])->name('edit');
    Route::put('/update/{id}', [PostinganController::class, 'update'])->name('update');

    // Approval
    Route::get('/approval', [PostinganController::class, 'approvalIndex'])->name('approval.index');
    Route::get('/approval/{id}', [PostinganController::class, 'approvalShow'])->name('approval.show');
    Route::post('/approval/{id}', [PostinganController::class, 'approvalUpdate'])->name('approval.update');
    Route::post('/store', [PostinganController::class, 'store'])->name('store');
});


// =============================== Donation (Public) ===============================
Route::get('/donasi', [ZISController::class, 'index'])->name('donasi.informasi');
Route::get('/donasi/sekarang', [ZISController::class, 'donasi'])->name('donasi.sekarang');
Route::get('/donasi/konfirmasi', [ZISController::class, 'konfirmasi'])->name('donasi.konfirmasi');
Route::post('/donasi/store', [ZISController::class, 'storeKonfirmasi'])->name('donasi.store');


// =============================== Lost & Found (Public) ===============================
Route::get('/layanan/barang-hilang', [LostFoundController::class, 'index'])->name('layanan.barang-hilang');


// =============================== Jadwal Kegiatan (Client) ===============================
Route::prefix('jadwal-kegiatan')->group(function () {
    Route::get('/', [JadwalKegiatanController::class, 'index'])->name('jadwal.index');
    Route::get('/data', [JadwalKegiatanController::class, 'getData'])->name('jadwal.data');
    Route::get('/by-date', [JadwalKegiatanController::class, 'getEventByDate']);
    Route::get('/{id}', [JadwalKegiatanController::class, 'show'])->name('jadwal.detail');
});


// =============================== Static Pages & Keuangan ===============================
Route::get('/tentang-kami', [StaticPageController::class, 'showAboutUs'])->name('client.tentang-kami');
Route::get('/laporan-keuangan', [KeuanganController::class, 'clientIndex'])->name('client.keuangan');


// =============================== Public Forms ===============================
Route::get('/form/{slug}', [FormBuilderController::class, 'show'])->name('form.show');
Route::post('/form/{slug}/submit', [FormBuilderController::class, 'submit'])->name('form.submit');


// =============================== User Profile (Auth) ===============================
Route::middleware('auth')->prefix('profil')->name('profile.')->group(function () {
    Route::get('/', [ProfileController::class, 'show'])->name('show');
    Route::get('/general', [ProfileController::class, 'general'])->name('general');
    Route::get('/edit', [ProfileController::class, 'edit'])->name('edit');
    Route::put('/', [ProfileController::class, 'update'])->name('update');

    Route::get('/password', [ProfileController::class, 'password'])->name('password');
    Route::put('/password', [ProfileController::class, 'changePassword'])->name('change-password');

    Route::get('/preferensi', [ProfileController::class, 'preferences'])->name('preferences');
    Route::put('/preferensi', [ProfileController::class, 'updatePreferences'])->name('update-preferences');
});


// =============================== Konsultasi (Public + Client Auth) ===============================
Route::get('/konsultasi', [ConsultationClientController::class, 'index'])
    ->name('client.consultations.index');

Route::middleware('auth')->prefix('konsultasi-saya')->name('client.consultations.')->group(function () {
    Route::get('/buat', [ConsultationClientController::class, 'create'])->name('create');
    Route::post('/', [ConsultationClientController::class, 'store'])->name('store');
    Route::get('/riwayat', [ConsultationClientController::class, 'history'])->name('history');
    Route::get('/{id}', [ConsultationClientController::class, 'show'])->name('show');
    Route::post('/{id}/pesan', [ConsultationClientController::class, 'sendMessage'])->name('send-message');
});


// =============================== ADMIN PANEL ===============================
Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin|super admin|ustadz'])->group(function () {

    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Lost Found
    Route::get('/barang-hilang', [LostFoundController::class, 'adminIndex'])->name('barang-hilang');
    Route::get('/barang-hilang/tambah', [LostFoundController::class, 'create'])->name('barang-hilang.tambah');
    Route::post('/barang-hilang', [LostFoundController::class, 'store'])->name('barang-hilang.store');
    Route::get('/barang-hilang/{id}/edit', [LostFoundController::class, 'edit'])->name('barang-hilang.edit');
    Route::put('/barang-hilang/{id}', [LostFoundController::class, 'update'])->name('barang-hilang.update');
    Route::delete('/barang-hilang/{id}', [LostFoundController::class, 'destroy'])->name('barang-hilang.destroy');

    // Jadwal Kegiatan
    Route::prefix('jadwal-kegiatan')->name('kegiatan.')->group(function () {
        Route::get('/', [AdminKegiatanController::class, 'index'])->name('index');
        Route::get('/tambah', [AdminKegiatanController::class, 'create'])->name('create');
        Route::post('/tambah', [AdminKegiatanController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [AdminKegiatanController::class, 'edit'])->name('edit');
        Route::put('/edit/{id}', [AdminKegiatanController::class, 'update'])->name('update');
        Route::delete('/delete/{id}', [AdminKegiatanController::class, 'destroy'])->name('destroy');
    });

    // Sidebar Indexes
    Route::get('/galeri', [GaleriController::class, 'index'])->name('galeri');
    Route::get('/kegiatan', [KegiatanController::class, 'index'])->name('kegiatan');
    Route::get('/donasi/verifikasi', [DonasiController::class, 'index'])->name('donasi.verifikasi');
    Route::get('/kajian', [KajianController::class, 'index'])->name('kajian');
    Route::get('/pengguna', [PenggunaController::class, 'index'])->name('pengguna');

    // Static Pages
    Route::get('/halaman-statis', [StaticPageController::class, 'indexAdmin'])->name('static-pages.index');
    Route::get('/halaman-statis/{id}/edit', [StaticPageController::class, 'edit'])->name('static-pages.edit');
    Route::put('/halaman-statis/{id}', [StaticPageController::class, 'update'])->name('static-pages.update');

    // Konsultasi Admin/Ustadz
    Route::prefix('konsultasi')->name('consultations.')->group(function () {
        Route::get('/', [ConsultationUstadzController::class, 'index'])->name('index');
        Route::get('/{id}', [ConsultationUstadzController::class, 'show'])->name('show');
        Route::post('/{id}/accept', [ConsultationUstadzController::class, 'accept'])->name('accept');
        Route::post('/{id}/reject', [ConsultationUstadzController::class, 'reject'])->name('reject');
        Route::post('/{id}/close', [ConsultationUstadzController::class, 'close'])->name('close');
        Route::post('/{id}/pesan', [ConsultationUstadzController::class, 'sendMessage'])->name('send-message');
    });

    // Form Builder Admin
    Route::get('/forms', [FormBuilderController::class, 'index'])->name('forms.index');
    Route::get('/forms/create', [FormBuilderController::class, 'create'])->name('forms.create');
    Route::post('/forms', [FormBuilderController::class, 'store'])->name('forms.store');
    Route::get('/forms/{id}/edit', [FormBuilderController::class, 'edit'])->name('forms.edit');
    Route::put('/forms/{id}', [FormBuilderController::class, 'update'])->name('forms.update');
    Route::delete('/forms/{id}', [FormBuilderController::class, 'destroy'])->name('forms.destroy');

    // Form Responses
    Route::get('/forms/{id}/responses', [FormBuilderController::class, 'responses'])->name('forms.responses');
    Route::get('/forms/{formId}/responses/{responseId}', [FormBuilderController::class, 'responseShow'])->name('forms.responses.show');
    Route::delete('/forms/{formId}/responses/{responseId}', [FormBuilderController::class, 'responseDelete'])->name('forms.responses.delete');

    // Bank
    Route::resource('banks', BankController::class);

    // Donasi Confirmation
    Route::get('/donasi/verifikasi', [DonationConfirmationController::class, 'index'])->name('donasi.index');
    Route::post('/donasi/{id}/approve', [DonationConfirmationController::class, 'approve'])->name('donasi.approve');
    Route::post('/donasi/{id}/reject', [DonationConfirmationController::class, 'reject'])->name('donasi.reject');

    //Keuangan
    Route::get('/keuangan', [KeuanganController::class, 'index'])->name('keuangan');
    Route::post('/keuangan', [KeuanganController::class, 'store'])->name('keuangan.store');
    Route::delete('/keuangan/{id}', [KeuanganController::class, 'destroy'])->name('keuangan.destroy');

    // Admin Profile
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [AdminProfileController::class, 'index'])->name('index');
        Route::get('/edit', [AdminProfileController::class, 'edit'])->name('edit');
        Route::get('/password', [AdminProfileController::class, 'password'])->name('password');
        Route::put('/update', [AdminProfileController::class, 'update'])->name('update');
        Route::put('/change-password', [AdminProfileController::class, 'changePassword'])->name('change-password');
    });
});


// =============================== TESTING ROUTE ===============================
Route::get('/aku/ini/test-email', function () {
    Mail::to("gensinkn@gmail.com")->queue(new \App\Mail\ResetPasswordMail("1", "gensinkn@gmail.com"));
});
