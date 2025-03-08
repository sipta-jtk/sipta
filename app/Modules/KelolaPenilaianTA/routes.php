<?php

use Illuminate\Support\Facades\Route;
use App\Modules\KelolaPenilaianTA\Controllers\KelolaPenilaianTAController;

Route::get('/KelolaPenilaianTA', [KelolaPenilaianTAController::class, 'index']);

Route::group(['prefix' => 'KelolaPenilaianTA'], function () {
    Route::get('/monitoring-mahasiswa', [KelolaPenilaianTAController::class, 'indexMonitoringMahasiswa']);
    Route::get('/monitoring-feedback', [KelolaPenilaianTAController::class, 'indexMonitoringFeedback']);
    Route::get('/monitoring-rubrik', [KelolaPenilaianTAController::class, 'indexMonitoringRubrik']);
});
