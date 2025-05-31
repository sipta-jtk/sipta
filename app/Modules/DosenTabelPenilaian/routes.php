<?php

use Illuminate\Support\Facades\Route;
use App\Modules\DosenTabelPenilaian\Controllers\DosenTabelPenilaianController;


Route::middleware(['auth', 'can:dosen'])->group(function () {
    Route::get('/dosen-tabel-penilaian/{kegiatan}', [DosenTabelPenilaianController::class, 'index'])->middleware(['auth'])->name('nilai.index');
    Route::post('/publikasikan/{id_penjadwalan}', [DosenTabelPenilaianController::class, 'publikasikan'])->middleware(['auth'])->name('nilai.publikasikan');
    Route::get('/kelola-penilaian-ta/nilai-seminar/{id}/nilai/{kota}', [DosenTabelPenilaianController::class, 'getForm'])->middleware(['auth'])->name('nilai.form');
});