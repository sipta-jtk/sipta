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
    Route::get('/cek-plagiarisme/detail/{encrypted_id}', [CekPlagiarismeController::class, 'showEncrypted']);
    Route::post('/cek-plagiarisme/process', [CekPlagiarismeController::class, 'process'])->name('cekplagiarisme.process');
    Route::get('/sipta-dev/cek-plagiarisme/process', function () {
        return redirect('/sipta-dev/cek-plagiarisme');
    });
    Route::get('/cek-plagiarisme', function () {
        return view('CekPlagiarisme.views.DaftarDokumen');
    });
    Route::post('/cek-plagiarisme/encrypt-id', [CekPlagiarismeController::class, 'encryptId']);
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
