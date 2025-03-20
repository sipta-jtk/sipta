<?php

use Illuminate\Support\Facades\Route;
use App\Modules\PerencanaanDanPelaksanaanSeminar3DanSidang\Controllers\PembatalanJadwalSeminarSidangController;
use App\Modules\PerencanaanDanPelaksanaanSeminar3DanSidang\Controllers\PengajuanJadwalKotaSeminar3DanSidang;

// PENGAJUAN JADWAL
Route::middleware(['auth', 'can:mahasiswa_ta'])->group(function () {
    //daftar pengajuan 
    Route::get('pengajuan', [PengajuanJadwalKotaSeminar3DanSidang::class, 'indexPengajuan'])
        ->middleware(['auth'])
        ->name('pengajuan');

    //pengajuan jadwal seminar 3
    Route::get('pengajuan-seminar3', [PengajuanJadwalKotaSeminar3DanSidang::class, 'indexPengajuanSeminar3'])
        ->middleware(['auth'])
        ->name('pengajuan-seminar3');

    //pengajuan jadwal sidang
    Route::get('pengajuan-sidang', [PengajuanJadwalKotaSeminar3DanSidang::class, 'indexPengajuanSidang'])
        ->middleware(['auth'])
        ->name('pengajuan-sidang');

    //tambah pengajuan baru
    Route::post('pengajuan-tambah/{id_kota}', [PengajuanJadwalKotaSeminar3DanSidang::class, 'tambahPengajuanPenjadwalan'])
        ->middleware(['auth'])
        ->name('pengajuan-tambah');
});

// PEMBATALAN JADWAL
//jadwal seminar
Route::get('/jadwal-seminar', [PembatalanJadwalSeminarSidangController::class, 'indexJadwalSeminar']);

//jadwal sidang
Route::get('/jadwal-sidang', [PembatalanJadwalSeminarSidangController::class, 'indexJadwalSidang']);

//pembatalan jadwal seminar
Route::get('/persetujuan-pembatalan-jadwal-seminar', [PembatalanJadwalSeminarSidangController::class, 'indexPersetujuanPembatalanJadwalSeminar']);

//pembatalan jadwal sidang
Route::get('/persetujuan-pembatalan-jadwal-sidang', [PembatalanJadwalSeminarSidangController::class, 'indexPersetujuanPembatalanJadwalSidang']);