<?php

use Illuminate\Support\Facades\Route;
use App\Modules\PerencanaanDanPelaksanaanSeminar3DanSidang\Controllers\PerencanaanDanPelaksanaanSeminar3DanSidangController;

// Route::get('/PerencanaanDanPelaksanaanSeminar3DanSidang', [PerencanaanDanPelaksanaanSeminar3DanSidangController::class, 'index']);

Route::get('/pengajuan', [PerencanaanDanPelaksanaanSeminar3DanSidangController::class, 'indexPengajuan']);

Route::get('/pengajuan-seminar3', [PerencanaanDanPelaksanaanSeminar3DanSidangController::class, 'indexPengajuanSeminar3'])->name('pengajuan-seminar3');

Route::get('/pengajuan-sidang', [PerencanaanDanPelaksanaanSeminar3DanSidangController::class, 'indexPengajuanSidang'])->name('pengajuan-sidang');
