<?php

use Illuminate\Support\Facades\Route;
use App\Modules\DosenTabelPenilaian\Controllers\DosenTabelPenilaianController;

Route::get('/DosenTabelPenilaian', [DosenTabelPenilaianController::class, 'index']);