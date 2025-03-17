<?php

use Illuminate\Support\Facades\Route;
use App\Modules\repository\Controllers\RepositoryController;

Route::get('/repository', [RepositoryController::class, 'index']);
Route::get('/log-aktifitas', [RepositoryController::class, 'logAktifitas']);
Route::get('/monitoring-penyimpanan', [RepositoryController::class, 'monitoringPenyimpanan']);
