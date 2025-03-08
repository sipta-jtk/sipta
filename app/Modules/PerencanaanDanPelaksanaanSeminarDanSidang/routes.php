<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Response;
use App\Modules\PerencanaanDanPelaksanaanSeminarDanSidang\Controllers\PerencanaanDanPelaksanaanSeminarDanSidangController;

Route::group(['prefix' => 'PerencanaanDanPelaksanaanSeminarDanSidang', 'as' => 'perencanaan.'], function () {
    Route::group(['prefix' => 'pengajuan-seminar-sidang', 'as' => 'kelola-pengajuan.'], function () {
        Route::get('/', [PerencanaanDanPelaksanaanSeminarDanSidangController::class, 'index'])->name('list');
        Route::get('/ditolak', [PerencanaanDanPelaksanaanSeminarDanSidangController::class, 'pengajuanDitolak'])->name('ditolak');
        Route::get('/diterima', [PerencanaanDanPelaksanaanSeminarDanSidangController::class, 'pengajuanDiterima'])->name('diterima');
        Route::get('/detail/{id}', [PerencanaanDanPelaksanaanSeminarDanSidangController::class, 'show'])->name('detail');
    });
});