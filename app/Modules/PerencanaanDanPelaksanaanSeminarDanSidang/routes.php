<?php

use Illuminate\Support\Facades\Route;
use App\Modules\PerencanaanDanPelaksanaanSeminarDanSidang\Controllers\VerifikasiPengajuanJadwalController;

Route::group(['prefix' => 'kelola-pengajuan', 'as' => 'kelola.'], function () {
    Route::group(['prefix' => '{tipe}', 'as' => 'jadwal.'], function () {
        Route::get('/', [VerifikasiBerkasController::class, 'index'])->name('list');
        Route::post('/verifikasi/{id}', [VerifikasiBerkasController::class, 'verifikasi'])->name('verifikasi');
    });
});
