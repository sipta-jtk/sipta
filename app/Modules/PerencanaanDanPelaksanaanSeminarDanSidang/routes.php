<?php

use Illuminate\Support\Facades\Route;
use App\Modules\PerencanaanDanPelaksanaanSeminarDanSidang\Controllers\VerifikasiPengajuanJadwalController;
use App\Modules\PerencanaanDanPelaksanaanSeminarDanSidang\Controllers\PerencanaanDanPelaksanaanSeminarDanSidangController;

// Route untuk koordinator menampilkan list pengajuan jadwal
Route::group(['prefix' => 'koordinator-kelola-pengajuan-jadwal', 'as' => 'kelola.'], function () {
    Route::group(['prefix' => '{tipe}', 'as' => 'jadwal.'], function () {
        Route::middleware(['auth', 'can:koordinator_ta'])->get('/', [VerifikasiPengajuanJadwalController::class, 'getListAsKoordinatorTA'])->name('list');
        Route::middleware(['auth', 'can:koordinator_ta'])->put('/verifikasi/{id}', [VerifikasiPengajuanJadwalController::class, 'verifikasiAsKoordinatorTA'])->name('verifikasi');
    });
});

// Route untuk dosen pembimbing menampilkan list pengajuan jadwal
Route::group(['prefix' => 'kelola-pengajuan-jadwal-pembimbing', 'as' => 'kelola-pembimbing.'], function () {
    Route::group(['prefix' => '{tipe}', 'as' => 'jadwal.'], function () {
        Route::middleware(['auth', 'can:akses-dosen-kelola-pengajuan-jadwal'])->get('/', [VerifikasiPengajuanJadwalController::class, 'getListAsDosenPembimbing'])->name('list');
        Route::middleware(['auth', 'can:akses-dosen-kelola-pengajuan-jadwal'])->put('/verifikasi/{id}', [VerifikasiPengajuanJadwalController::class, 'verifikasiAsPembimbing'])->name('verifikasi');
    });
});

// Route untuk dosen penguji menampilkan list pengajuan jadwal
Route::group(['prefix' => 'kelola-pengajuan-jadwal-penguji', 'as' => 'kelola-penguji.'], function () {
    Route::group(['prefix' => '{tipe}', 'as' => 'jadwal.'], function () {
        Route::middleware(['auth', 'can:akses-dosen-kelola-pengajuan-jadwal'])->get('/', [VerifikasiPengajuanJadwalController::class, 'getListAsDosenPenguji'])->name('list');
        Route::middleware(['auth', 'can:akses-dosen-kelola-pengajuan-jadwal'])->put('/verifikasi/{id}', [VerifikasiPengajuanJadwalController::class, 'verifikasiAsPenguji'])->name('verifikasi');
    });
});

// Route untuk menampilkan halaman presensi
Route::get('/PerencanaanDanPelaksanaanSeminarDanSidang', [PerencanaanDanPelaksanaanSeminarDanSidangController::class, 'index']);

// Route untuk menangani form submission absensi
Route::post('/presensi/hadir', [PerencanaanDanPelaksanaanSeminarDanSidangController::class, 'simpanKehadiran'])->name('presensi.hadir');

// Route untuk halaman rekap presensi koordinator TA
Route::get('/rekap-presensi-seminar-3', [PerencanaanDanPelaksanaanSeminarDanSidangController::class, 'rekapPresensi'])->name('rekap.presensi.seminar3');

// Route untuk menyimpan dokumentasi
Route::post('/presensi/dokumentasi', [PerencanaanDanPelaksanaanSeminarDanSidangController::class, 'simpanDokumentasi'])->name('presensi.dokumentasi');

// Route untuk rekap presensi Sidang TA
Route::get('/rekap-presensi-sidang-ta', [PerencanaanDanPelaksanaanSeminarDanSidangController::class, 'rekapPresensiSidangTA'])->name('rekap.presensi.sidang.ta');
