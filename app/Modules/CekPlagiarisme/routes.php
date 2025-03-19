<?php

use Illuminate\Support\Facades\Route;
use App\Modules\CekPlagiarisme\Controllers\CekPlagiarismeController;
use App\Modules\CekPlagiarisme\Controllers\AmbangBatasController;
use App\Modules\CekPlagiarisme\Controllers\CekPlagiarismeDetailController;

Route::get('/api/cek-plagiarisme', [cekplagiarismeController::class, 'getData']);
Route::get('/cek-plagiarisme/{id}', [CekPlagiarismeController::class, 'show'])->name('plagiarism.detail');
Route::get('/api/ambang-batas', [AmbangBatasController::class, 'getData']);
Route::post('/api/ambang-batas', [AmbangBatasController::class, 'store']);
// Route::middleware('auth')->post('/api/ambang-batas', [AmbangBatasController::class, 'store'])->name('cekplagiarisme.index');
Route::post('/cekplagiarisme/process', [CekPlagiarismeController::class, 'process'])->name('cekplagiarisme.process');

Route::get('/penentuan-ambang-batas', function () {
    return view('CekPlagiarisme.views.PenentuanAmbangBatas');
});
Route::get('/cek-plagiarisme', function () {
    return view('CekPlagiarisme.views.DaftarDokumen');
});
