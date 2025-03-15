<?php

use Illuminate\Support\Facades\Route;
use App\Modules\PerencanaanDanPelaksanaanSeminarDanSidang\Controllers\VerifikasiBerkasController;

Route::group(['prefix' => 'kelola-pengajuan', 'as' => 'kelola.'], function () {
    Route::group(['prefix' => '{tipe}', 'as' => 'berkas.'], function () {
        Route::get('/', [VerifikasiBerkasController::class, 'index'])->name('list');
        Route::get('/ditolak', [VerifikasiBerkasController::class, 'pengajuanDitolak'])->name('ditolak');
        Route::get('/diterima', [VerifikasiBerkasController::class, 'pengajuanDiterima'])->name('diterima');
        Route::get('/detail/{id}', [VerifikasiBerkasController::class, 'show'])->name('detail');
        Route::post('/verifikasi/{id}', [VerifikasiBerkasController::class, 'verifikasi'])->name('verifikasi');
    });
});

