<?php

use Illuminate\Support\Facades\Route;
use App\Modules\CekPlagiarisme\Controllers\CekPlagiarismeController;
use App\Modules\CekPlagiarisme\Controllers\AmbangBatasController;
use App\Modules\CekPlagiarisme\Controllers\CekPlagiarismeDetailController;

Route::get('/api/cek-plagiarisme', [cekplagiarismeController::class, 'getData']);
Route::get('/cek-plagiarisme/{id}/detail-dokumen', [CekPlagiarismeDetailController::class, 'show'])->name('plagiarism.detail');
Route::get('/cek-plagiarisme-catatan', [CekPlagiarismeDetailController::class, 'povMahasiswa'])->name('povMahasiswa');
Route::post('/cekplagiarisme/process', [CekPlagiarismeController::class, 'process'])->name('cekplagiarisme.process');

Route::get('/cek-plagiarisme', function () {
    return view('CekPlagiarisme.views.DaftarDokumen');
});


/**********************************
 * Penentuan Ambang Batas
 ***********************************/
Route::middleware(['auth', 'can:koordinator_ta'])->group(function () {
    Route::get('/penentuan-ambang-batas', function () {
        return view('CekPlagiarisme.views.PenentuanAmbangBatas');
    });
    Route::get('/api/ambang-batas', [AmbangBatasController::class, 'getData']);
    Route::post('/api/ambang-batas', [AmbangBatasController::class, 'store']);
});