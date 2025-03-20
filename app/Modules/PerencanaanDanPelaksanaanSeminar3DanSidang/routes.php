<?php

use Illuminate\Support\Facades\Route;
use App\Modules\PerencanaanDanPelaksanaanSeminar3DanSidang\Controllers\PembatalanJadwalSeminarSidangController;
use App\Modules\PerencanaanDanPelaksanaanSeminar3DanSidang\Controllers\PengajuanJadwalKotaSeminar3DanSidang;

// PENGAJUAN JADWAL
Route::middleware(['auth', 'can:all_mahasiswa'])->group(function () {
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
Route::middleware(['auth', 'can:dosen'])->group(function () {
    //jadwal seminar
    Route::get('/batal-jadwal-seminar', [PembatalanJadwalSeminarSidangController::class, 'indexJadwalSeminar'])->name('jadwal.seminar');
    Route::post('/pembatalan-jadwal-seminar', [PembatalanJadwalSeminarSidangController::class, 'pembatalanJadwalSeminar'])->name('pembatalan.seminar');

    //jadwal sidang
    Route::get('/batal-jadwal-sidang', [PembatalanJadwalSeminarSidangController::class, 'indexJadwalSidang']);
    Route::post('/pembatalan-jadwal-sidang', [PembatalanJadwalSeminarSidangController::class, 'pembatalanJadwalSidang'])->name('pembatalan.sidang');


});

Route::middleware(['auth', 'can:koordinator_ta'])->group(function () {
    //pembatalan jadwal seminar
    Route::get('/persetujuan-pembatalan-jadwal-seminar', [PembatalanJadwalSeminarSidangController::class, 'indexPersetujuanPembatalanJadwalSeminar'])->name('view.persetujuan.pembatalan.seminar');
    Route::post('/persetujuan-pembatalan-jadwal-seminar/{pembatalan_id}/{status}', [PembatalanJadwalSeminarSidangController::class, 'persetujuanPembatalanSeminar'])->name('persetujuan.pembatalan.seminar');

    //pembatalan jadwal sidang
    Route::get('/persetujuan-pembatalan-jadwal-sidang', [PembatalanJadwalSeminarSidangController::class, 'indexPersetujuanPembatalanJadwalSidang'])->name('view.persetujuan.pembatalan.sidang');
    Route::post('/persetujuan-pembatalan-jadwal-sidang/{pembatalan_id}/{status}', [PembatalanJadwalSeminarSidangController::class, 'persetujuanPembatalanSidang'])->name('persetujuan.pembatalan.sidang');
});