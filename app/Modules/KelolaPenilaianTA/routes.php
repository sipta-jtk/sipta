<?php

use Illuminate\Support\Facades\Route;
use App\Modules\KelolaPenilaianTA\Controllers\KelolaPenilaianTAController;

Route::get('/KelolaPenilaianTA', [KelolaPenilaianTAController::class, 'index']);

Route::group(['prefix' => 'KelolaPenilaianTA'], function () {
    Route::get('/masukan-seminar-1', [KelolaPenilaianTAController::class, 'pengisianMasukanSeminar1']);
});

Route::group(['prefix' => 'KelolaPenilaianTA'], function () {
    Route::get('/nilai-seminar-II', [KelolaPenilaianTAController::class, 'pengisianNilaiSeminarII']);
});

Route::group(['prefix' => 'KelolaPenilaianTA'], function () {
    Route::get('/nilai-seminar-II/masukan-seminar-II', [KelolaPenilaianTAController::class, 'pengisianMasukanSeminarII']);
});

Route::group(['prefix' => 'KelolaPenilaianTA'], function () {
    Route::get('/nilai-seminar-III', [KelolaPenilaianTAController::class, 'pengisianNilaiSeminarIII']);
});

Route::group(['prefix' => 'KelolaPenilaianTA'], function () {
    Route::get('/nilai-seminar-III/masukan-seminar-III', [KelolaPenilaianTAController::class, 'pengisianMasukanSeminarIII']);
});

Route::group(['prefix' => 'KelolaPenilaianTA'], function () {
    Route::get('/nilai-sidang-akhir', [KelolaPenilaianTAController::class, 'pengisianNilaiSidangAkhir']);
});

Route::group(['prefix' => 'KelolaPenilaianTA'], function () {
    Route::get('/nilai-sidang-akhir/masukan-sidang-akhir', [KelolaPenilaianTAController::class, 'pengisianMasukanSidangAkhir']);
});

// Route::post('/KelolaPenilaianTA/masukan-seminar-II', 'FeedbackController@store')->name('feedback.store');
