<?php

use App\Http\Controllers\News\HalamanPostinganController;
use App\Http\Controllers\News\ShowNews;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Keuangan\ZISController;
use App\Http\Controllers\Layanan\LostFoundController;
use App\Http\Controllers\News\AddNewsController;
use App\Http\Controllers\News\DetailNewsController;

Route::get('admin/artikel/tambah', function () {
    return view('tambah_artikel',);
});


Route::get('/postingan',[HalamanPostinganController::class,'return_resource']);

Route::get('/postingan/{id}',[DetailNewsController::class,'return_resource']);


Route::get('/aku/ini/test',[DetailNewsController::class,'return_resource']);

Route::post('/posts', [AddNewsController::class, 'upload']);

Route::get('/admin/artikel', [ShowNews::class, 'getEditArtikel']);

// route ini hanya untuk test saja
// Route::get('/admin/artikel/delete/storage/{id}', [ShowNews::class, 'search_delete_featured_image']);

Route::delete('/admin/artikel/delete/{id}', [ShowNews::class, 'deleteArtikel'])
     ->name('artikel.delete');




Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/donasi', [ZISController::class, 'index'])->name('Informasi Rekening');

Route::get('/layanan/barang-hilang', [LostFoundController::class, 'index'])->name('layanan.barang-hilang');

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // LOST AND FOUND MANAGEMENT
    Route::get('/barang-hilang', [LostFoundController::class, 'adminIndex'])->name('barang-hilang');
    Route::get('/barang-hilang/tambah', [LostFoundController::class, 'create'])->name('barang-hilang.tambah');
    Route::post('/barang-hilang', [LostFoundController::class, 'store']);
});



?>
