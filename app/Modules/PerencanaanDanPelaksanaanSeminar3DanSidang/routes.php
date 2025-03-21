<?php

use Illuminate\Support\Facades\Route;
use App\Modules\PerencanaanDanPelaksanaanSeminar3DanSidang\Controllers\PembatalanJadwalSeminarSidangController;
use App\Modules\PerencanaanDanPelaksanaanSeminar3DanSidang\Controllers\VerifikasiPengajuanJadwalController;


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

// Route untuk koordinator menampilkan list pengajuan jadwal
Route::group(['prefix' => 'koordinator-kelola-pengajuan-jadwal', 'as' => 'kelola.', 'middleware' => ['auth', 'can:koordinator_ta']], function () {
    Route::group(['prefix' => '{tipe}', 'as' => 'jadwal.'], function () {
        Route::get('/', [VerifikasiPengajuanJadwalController::class, 'getListAsKoordinatorTA'])->name('list');
        Route::put('/verifikasi/{id}', [VerifikasiPengajuanJadwalController::class, 'verifikasiAsKoordinatorTA'])->name('verifikasi');
    });
});

// Route untuk dosen pembimbing menampilkan list pengajuan jadwal
Route::group(['prefix' => 'kelola-pengajuan-jadwal-pembimbing', 'as' => 'kelola-pembimbing.', 'middleware' => ['auth', 'can:akses-dosen-kelola-pengajuan-jadwal']], function () {
    Route::group(['prefix' => '{tipe}', 'as' => 'jadwal.'], function () {
        Route::get('/', [VerifikasiPengajuanJadwalController::class, 'getListAsDosenPembimbing'])->name('list');
        Route::put('/verifikasi/{id}', [VerifikasiPengajuanJadwalController::class, 'verifikasiAsPembimbing'])->name('verifikasi');
    });
});

// Route untuk dosen penguji menampilkan list pengajuan jadwal
Route::group(['prefix' => 'kelola-pengajuan-jadwal-penguji', 'as' => 'kelola-penguji.', 'middleware' => ['auth', 'can:akses-dosen-kelola-pengajuan-jadwal']], function () {
    Route::group(['prefix' => '{tipe}', 'as' => 'jadwal.'], function () {
        Route::get('/', [VerifikasiPengajuanJadwalController::class, 'getListAsDosenPenguji'])->name('list');
        Route::put('/verifikasi/{id}', [VerifikasiPengajuanJadwalController::class, 'verifikasiAsPenguji'])->name('verifikasi');
    });
});