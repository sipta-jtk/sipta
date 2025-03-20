<?php

use Illuminate\Support\Facades\Route;
use App\Modules\DosenTabelPenilaian\Controllers\DosenTabelPenilaianController;

Route::middleware(['auth', 'can:dosen'])->group(function () {
    Route::get('/DosenTabelPenilaian', [DosenTabelPenilaianController::class, 'index'])->middleware(['auth']);
});