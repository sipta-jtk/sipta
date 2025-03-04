<?php

use Illuminate\Support\Facades\Route;
use App\Modules\PerencanaanDanPelaksanaanSeminarDanSidang\Controllers\PerencanaanDanPelaksanaanSeminarDanSidangController;

Route::get('/Dashboard', [PerencanaanDanPelaksanaanSeminarDanSidangController::class, 'index']);