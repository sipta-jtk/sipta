<?php

use Illuminate\Support\Facades\Route;
use App\Modules\PerencanaanDanPelaksanaanSeminar3DanSidang\Controllers\PembatalanJadwalSeminarSidangController;
use App\Modules\PerencanaanDanPelaksanaanSeminar3DanSidang\Controllers\VerifikasiBerkasController;


//jadwal seminar
Route::get('/jadwal-seminar', [PembatalanJadwalSeminarSidangController::class, 'indexJadwalSeminar']);

//jadwal sidang
Route::get('/jadwal-sidang', [PembatalanJadwalSeminarSidangController::class, 'indexJadwalSidang']);

//pembatalan jadwal seminar
Route::get('/persetujuan-pembatalan-jadwal-seminar', [PembatalanJadwalSeminarSidangController::class, 'indexPersetujuanPembatalanJadwalSeminar']);


//pembatalan jadwal sidang
Route::get('/persetujuan-pembatalan-jadwal-sidang', [PembatalanJadwalSeminarSidangController::class, 'indexPersetujuanPembatalanJadwalSidang']);
use App\Modules\PerencanaanDanPelaksanaanSeminar3DanSidang\Controllers\PerencanaanDanPelaksanaanSeminar3DanSidangController;

// Route::get('/PerencanaanDanPelaksanaanSeminar3DanSidang', [PerencanaanDanPelaksanaanSeminar3DanSidangController::class, 'index']);

Route::get('/pengajuan', [PerencanaanDanPelaksanaanSeminar3DanSidangController::class, 'indexPengajuan']);

Route::get('/pengajuan-seminar3', [PerencanaanDanPelaksanaanSeminar3DanSidangController::class, 'indexPengajuanSeminar3'])->name('pengajuan-seminar3');

Route::get('/pengajuan-sidang', [PerencanaanDanPelaksanaanSeminar3DanSidangController::class, 'indexPengajuanSidang'])->name('pengajuan-sidang');

// Kelola Verifikasi Berkas Pengajuan
Route::group(['prefix' => 'kelola-pengajuan-berkas', 'as' => 'kelola.', 'middleware' => ['auth', 'can:koordinator_ta']], function () {
    Route::group(['prefix' => '{tipe}', 'as' => 'berkas.'], function () {
        Route::get('/', [VerifikasiBerkasController::class, 'listPengajuan'])->name('list');
        Route::get('/ditolak', [VerifikasiBerkasController::class, 'pengajuanDitolak'])->name('ditolak');
        Route::get('/diterima', [VerifikasiBerkasController::class, 'pengajuanDiterima'])->name('diterima');
        Route::get('/detail/{id}', [VerifikasiBerkasController::class, 'show'])->name('detail');
        Route::put('/verifikasi/{id}', [VerifikasiBerkasController::class, 'verifikasi'])->name('verifikasi');
    });
});