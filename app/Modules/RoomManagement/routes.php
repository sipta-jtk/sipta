<?php

use App\Modules\RoomManagement\Controllers\KelolaJadwalServiceCallController;
use Illuminate\Support\Facades\Route;

Route::get('/ruangan-service/ruangan', [KelolaJadwalServiceCallController::class, 'redirectToKelolaRuangan'])->name('ruangan.service.call')->middleware('auth');

Route::get('/ruangan-service/kalender', [KelolaJadwalServiceCallController::class, 'redirectToKalender'])->name('kalender.service.call')->middleware('auth');