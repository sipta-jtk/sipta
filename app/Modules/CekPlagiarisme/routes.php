<?php

use Illuminate\Support\Facades\Route;
use App\Modules\CekPlagiarisme\Controllers\CekPlagiarismeController;
<<<<<<< HEAD
use App\Modules\CekPlagiarisme\Controllers\CekPlagiarismeDetailController;
=======
>>>>>>> ad3bca3528ad83c3bfba889b109e4dca15abb64c
use App\Modules\CekPlagiarisme\Controllers\AmbangBatasController;

Route::get('/api/cek-plagiarisme', [cekplagiarismeController::class, 'getData']);
Route::get('/cek-plagiarisme/{id}', [CekPlagiarismeController::class, 'show'])->name('plagiarism.detail');
Route::get('/api/ambang-batas', [AmbangBatasController::class, 'getData']);
Route::post('/api/ambang-batas', [AmbangBatasController::class, 'store']);
// Route::middleware('auth')->post('/api/ambang-batas', [AmbangBatasController::class, 'store'])->name('cekplagiarisme.index');
Route::post('/cekplagiarisme/process', [CekPlagiarismeController::class, 'process'])->name('cekplagiarisme.process');

<<<<<<< HEAD
Route::get('/cek-plagiarisme', [CekPlagiarismeController::class, 'index']);
Route::get('/cek-plagiarisme/{id}', [CekPlagiarismeDetailController::class, 'show'])->name('plagiarism.detail');
Route::get('/penentuan-ambang-batas', [CekPlagiarismeController::class, 'PenentuanAmbangBatas']);
Route::get('/cek-plagiarisme-catatan', [CekPlagiarismeDetailController::class, 'povMahasiswa'])->name('povMahasiswa');
=======
>>>>>>> ad3bca3528ad83c3bfba889b109e4dca15abb64c
Route::get('/penentuan-ambang-batas', function () {
    return view('CekPlagiarisme.views.PenentuanAmbangBatas');
});
Route::get('/cek-plagiarisme', function () {
    return view('CekPlagiarisme.views.DaftarDokumen');
});