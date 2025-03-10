<?php

use Illuminate\Support\Facades\Route;
use App\Modules\PenjadwalanRuangan\Controllers\PenjadwalanRuanganController;

Route::get('/penjadwalan-ruangan', [PenjadwalanRuanganController::class, 'index']);
Route::prefix('services/v1')->group(function () {
    Route::get("schedules", [PenjadwalanController::class, 'getEvent']);
    Route::post('schedule/action', [PenjadwalanController::class, 'action']);
});