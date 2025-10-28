<?php

use Illuminate\Support\Facades\Route;

Route::get('admin/artikel/tambah', function () {
    return view('tambah_artikel');
});



Route::post('/posts', [PostController::class, 'store']);
Route::post('/upload-image', [UploadController::class, 'store']);

Route::get('admin/artikel/tambah/dummy',function () 
{
    return view('dummy_artikel');
} )


?>
