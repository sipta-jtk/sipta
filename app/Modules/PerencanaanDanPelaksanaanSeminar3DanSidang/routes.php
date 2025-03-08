<?php

use Illuminate\Support\Facades\Route;
use App\Modules\PerencanaanDanPelaksanaanSeminar3DanSidang\Controllers\PembatalanJadwalSeminarSidangController;


//jadwal seminar
Route::get('/jadwal-seminar', [PembatalanJadwalSeminarSidangController::class, 'indexJadwalSeminar']);

//jadwal sidang
Route::get('/jadwal-sidang', [PembatalanJadwalSeminarSidangController::class, 'indexJadwalSidang']);

//pembatalan jadwal seminar
Route::get('/persetujuan-pembatalan-jadwal-seminar', [PembatalanJadwalSeminarSidangController::class, 'indexPersetujuanPembatalanJadwalSeminar']);


//pembatalan jadwal sidang
Route::get('/persetujuan-pembatalan-jadwal-sidang', [PembatalanJadwalSeminarSidangController::class, 'indexPersetujuanPembatalanJadwalSidang']);
