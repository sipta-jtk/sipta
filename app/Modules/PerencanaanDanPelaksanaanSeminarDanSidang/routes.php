<?php

use Illuminate\Support\Facades\Route;
use App\Modules\PerencanaanDanPelaksanaanSeminarDanSidang\Controllers\PerencanaanDanPelaksanaanSeminarDanSidangController;

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
