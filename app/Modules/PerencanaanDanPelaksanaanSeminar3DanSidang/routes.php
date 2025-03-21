<?php

use Illuminate\Support\Facades\Route;
use App\Modules\PerencanaanDanPelaksanaanSeminar3DanSidang\Controllers\BeritaAcaraPelaksanaanSeminarDanSidangController;

// Rekap berita acara seminar 3 dan sidang TA Koordinator TA
Route::middleware(['auth', 'can:koordinator_ta'])->group(function () {
    // Route untuk halaman rekap berita acara seminar 3
    Route::get('/rekap-berita-acara-seminar-3', [BeritaAcaraPelaksanaanSeminarDanSidangController::class, 'rekapBeritaAcaraSeminar3'])
        ->name('rekap.presensi.seminar3');

    // Route untuk halaman rekap berita acara Sidang TA
    Route::get('/rekap-berita-acara-sidang-ta', [BeritaAcaraPelaksanaanSeminarDanSidangController::class, 'rekapBeritaAcaraSidangTa'])
        ->name('rekap.presensi.sidang.ta');
});

Route::middleware(['auth', 'can:all_mahasiswa'])->group(function () {
    Route::get('/berita-acara-pelaksanaan-seminar3', [BeritaAcaraPelaksanaanSeminarDanSidangController::class, 'indexBeritaAcaraSeminar3'])
        ->name('presensi.seminar3');

    Route::get('/berita-acara-pelaksanaan-sidang-ta', [BeritaAcaraPelaksanaanSeminarDanSidangController::class, 'indexBeritaAcaraSidangTA'])
        ->name('presensi.sidangta');

    Route::post('/presensi/hadir', [BeritaAcaraPelaksanaanSeminarDanSidangController::class, 'simpanKehadiran'])
        ->name('presensi.hadir');

    Route::post('/presensi/dokumentasi', [BeritaAcaraPelaksanaanSeminarDanSidangController::class, 'simpanDokumentasi'])
        ->name('presensi.dokumentasi');

    Route::post('/simpan-batas-revisi', [BeritaAcaraPelaksanaanSeminarDanSidangController::class, 'simpanBatasRevisi'])
        ->name('simpan.batas.revisi');

    Route::post('/simpan-status-kelulusan', [BeritaAcaraPelaksanaanSeminarDanSidangController::class, 'simpanStatusKelulusan'])
        ->name('simpan.status.kelulusan');
});

?>