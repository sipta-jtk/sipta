<?php

use Illuminate\Support\Facades\Route;
use App\Modules\CekPlagiarisme\Controllers\CekPlagiarismeController;
use App\Modules\CekPlagiarisme\Controllers\CekPlagiarismeDetailController;

<<<<<<< HEAD
Route::get('/cek-plagiarisme', [CekPlagiarismeController::class, 'index']);
Route::get('/cek-plagiarisme/{id}', [CekPlagiarismeDetailController::class, 'show'])->name('plagiarism.detail');
Route::get('/penentuan-ambang-batas', [CekPlagiarismeController::class, 'PenentuanAmbangBatas']);
Route::get('/cek-plagiarisme-catatan', [CekPlagiarismeDetailController::class, 'povMahasiswa'])->name('povMahasiswa');
=======
Route::get('/penentuan-ambang-batas', function () {
    return view('CekPlagiarisme.views.PenentuanAmbangBatas');
});
Route::get('/cek-plagiarisme', function () {
    return view('CekPlagiarisme.views.DaftarDokumen');
});
>>>>>>> cb451d45482bce69a240cb275e9798d704065c57
