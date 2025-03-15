<?php

// use Illuminate\Support\Facades\Route;
// use App\Modules\PerencanaanDanPelaksanaanSeminarDanSidang\Controllers\VerifikasiBerkasSeminarController as BerkasSeminar;
// use App\Modules\PerencanaanDanPelaksanaanSeminarDanSidang\Controllers\VerifikasiBerkasSidangController as BerkasSidang;

// Route::group(['prefix' => 'kelola-pengajuan', 'as' => 'kelola.'], function () {
//     Route::group(['prefix' => 'berkas-seminar-3', 'as' => 'seminar.'], function () {
//         Route::get('/', [BerkasSeminar::class, 'index'])->name('list');
//         Route::get('/ditolak', [BerkasSeminar::class, 'pengajuanDitolak'])->name('ditolak');
//         Route::get('/diterima', [BerkasSeminar::class, 'pengajuanDiterima'])->name('diterima');
//         Route::get('/detail/{id}', [BerkasSeminar::class, 'show'])->name('detail');
        
//         // Route untuk proses verifikasi (setuju/tolak)
//         Route::post('/verifikasi/{id}', [BerkasSeminar::class, 'verifikasi'])->name('verifikasi');
//     });

//     Route::group(['prefix' => 'berkas-sidang-akhir', 'as' => 'sidang.'], function () {
//         Route::get('/', [BerkasSidang::class, 'index'])->name('list');
//         Route::get('/ditolak', [BerkasSidang::class, 'pengajuanDitolak'])->name('ditolak');
//         Route::get('/diterima', [BerkasSidang::class, 'pengajuanDiterima'])->name('diterima');
//         Route::get('/detail/{id}', [BerkasSidang::class, 'show'])->name('detail');
        
//         // Route untuk proses verifikasi (setuju/tolak)
//         Route::post('/verifikasi/{id}', [BerkasSidang::class, 'verifikasi'])->name('verifikasi');
//     });
// });

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

