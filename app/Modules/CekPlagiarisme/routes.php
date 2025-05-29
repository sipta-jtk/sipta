<?php

use Illuminate\Support\Facades\Route;
use App\Modules\CekPlagiarisme\Controllers\CekPlagiarismeController;
use App\Modules\CekPlagiarisme\Controllers\AmbangBatasController;
use App\Modules\CekPlagiarisme\Controllers\CekPlagiarismeDetailController;

Route::middleware(['auth', 'can:dosen'])->get(
    '/api/kotas',
    [CekPlagiarismeController::class, 'getKota']
);

Route::middleware(['auth', 'can:user'])->group(function () {
   
    Route::get('/api/cek-plagiarisme', [cekplagiarismeController::class, 'getData']);
    Route::get('/cek-plagiarisme/{id}/detail-dokumen', [CekPlagiarismeDetailController::class, 'show'])->name('plagiarism.detail');
    Route::post('/cek-plagiarisme/process', [CekPlagiarismeController::class, 'process'])->name('cekplagiarisme.process');
    Route::get('/cek-plagiarisme/process', function () {
        return redirect('/cek-plagiarisme');
    });
    Route::get('/cek-plagiarisme', function () {
        return view('CekPlagiarisme.views.DaftarDokumen');
    });
});

/**********************************
 * Penentuan Ambang Batas
 ***********************************/
Route::middleware(['auth', 'can:koordinator_ta'])->group(function () {
    Route::get('/penentuan-ambang-batas', function () {
        return view('CekPlagiarisme.views.PenentuanAmbangBatas');
    });
    Route::get('/api/ambang-batas', [AmbangBatasController::class, 'getData']);
    Route::post('/api/ambang-batas', [AmbangBatasController::class, 'store']);
});

/**********************************
 * Catatan oleh Dosen Pembimbing
 ***********************************/
Route::group(['prefix' => 'cek-plagiarisme', 'as' => 'cek-plagiarisme.', 'middleware' => ['auth', 'can:dosen']], function () {
    Route::post('catatan-store/{id_dokumen}', [CekPlagiarismeDetailController::class, 'storeCatatan'])->name('catatan.store');
    Route::put('catatan-store/{id_dokumen}/{id}', [CekPlagiarismeDetailController::class, 'updateCatatan'])->name('catatan.put');
    Route::delete('catatan-store/{id_dokumen}/{id}', [CekPlagiarismeDetailController::class, 'deleteCatatan'])->name('catatan.delete');
});
