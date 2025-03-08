<?php

use Illuminate\Support\Facades\Route;
use App\Modules\KelolaPenilaianTA\Controllers\KelolaPenilaianTAController;

Route::group(['prefix' => 'KelolaPenilaianTA'], function () {
    Route::get('/rekapitulasi-nilai', [KelolaPenilaianTAController::class, 'index']);
    Route::post('/rekapitulasi-nilai/export', [KelolaPenilaianTAController::class, 'exportExcel'])->name('rekapitulasi-nilai.export');
    Route::get('/rekapitulasi/export', [KelolaPenilaianTAController::class, 'exportExcel'])->name('rekapitulasi.export');
});