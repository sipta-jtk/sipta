<?php

use Illuminate\Support\Facades\Route;
use App\Modules\KelolaPenilaianTA\Controllers\KelolaPenilaianTAController;

Route::group(['prefix' => 'KelolaPenilaianTA'], function () {
    Route::get('/rekapitulasi-nilai', [KelolaPenilaianTAController::class, 'index']);
});
