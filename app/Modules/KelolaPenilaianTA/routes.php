<?php

use Illuminate\Support\Facades\Route;
use App\Modules\KelolaPenilaianTA\Controllers\KelolaPenilaianTAController;

Route::get('/KelolaPenilaianTA', [KelolaPenilaianTAController::class, 'index']);

Route::group(['prefix' => 'KelolaPenilaianTA'], function () {
    Route::get('/nilai-seminar-2', [KelolaPenilaianTAController::class, 'pengisianNilaiSeminar2']);
});

Route::group(['prefix' => 'KelolaPenilaianTA'], function () {
    Route::get('/nilai-seminar-2/feedback-seminar-2', [KelolaPenilaianTAController::class, 'pengisianFeedbackSeminar2']);
});

Route::post('/KelolaPenilaianTA/feedback-seminar-2', 'FeedbackController@store')->name('feedback.store');
