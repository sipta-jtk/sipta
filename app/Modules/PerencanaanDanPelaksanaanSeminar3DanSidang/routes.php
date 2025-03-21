<?php

use Illuminate\Support\Facades\Route;
use App\Modules\PerencanaanDanPelaksanaanSeminar3DanSidang\Controllers\PembatalanJadwalSeminarSidangController;
use App\Modules\PerencanaanDanPelaksanaanSeminar3DanSidang\Controllers\VerifikasiPengajuanJadwalController;
use App\Modules\PerencanaanDanPelaksanaanSeminar3DanSidang\Controllers\VerifikasiBerkasController;
use App\Modules\PerencanaanDanPelaksanaanSeminar3DanSidang\Controllers\PengajuanJadwalKotaSeminar3DanSidang;
use App\Modules\PerencanaanDanPelaksanaanSeminar3DanSidang\Controllers\VerifikasiBerkasPengajuanMahasiswaController;
use App\Modules\PerencanaanDanPelaksanaanSeminar3DanSidang\Controllers\PerencanaanDanPelaksanaanSeminar3DanSidangController;

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
Route::get('/jadwal-sidang', [PembatalanJadwalSeminarSidangController::class, 'indexJadwalSidang']);

//pembatalan jadwal seminar
Route::get('/persetujuan-pembatalan-jadwal-seminar', [PembatalanJadwalSeminarSidangController::class, 'indexPersetujuanPembatalanJadwalSeminar']);


//pembatalan jadwal sidang
Route::get('/persetujuan-pembatalan-jadwal-sidang', [PembatalanJadwalSeminarSidangController::class, 'indexPersetujuanPembatalanJadwalSidang']);

// Route::get('/PerencanaanDanPelaksanaanSeminar3DanSidang', [PerencanaanDanPelaksanaanSeminar3DanSidangController::class, 'index']);

Route::get('/pengajuan', [PerencanaanDanPelaksanaanSeminar3DanSidangController::class, 'indexPengajuan']);

Route::get('/pengajuan-seminar3', [PerencanaanDanPelaksanaanSeminar3DanSidangController::class, 'indexPengajuanSeminar3'])->name('pengajuan-seminar3');

Route::get('/pengajuan-sidang', [PerencanaanDanPelaksanaanSeminar3DanSidangController::class, 'indexPengajuanSidang'])->name('pengajuan-sidang');
