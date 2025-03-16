<?php

use Illuminate\Support\Facades\Route;
use App\Modules\PerencanaanDanPelaksanaanSeminarDanSidang\Controllers\VerifikasiPengajuanJadwalController;

Route::group(['prefix' => 'kelola-pengajuan-jadwal', 'as' => 'kelola.'], function () {
    Route::group(['prefix' => '{tipe}', 'as' => 'jadwal.'], function () {
        Route::get('/', [VerifikasiPengajuanJadwalController::class, 'getList'])->name('list');
        Route::post('/verifikasi/{id}', [VerifikasiPengajuanJadwalController::class, 'verifikasi'])->name('verifikasi');
    });
});