<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Repository\Controllers\RepositoryController;

Route::group(['prefix' => 'repository'], function(){   
    Route::get('/', action: [RepositoryController::class, 'index']);
    Route::get('/kategori', action: [RepositoryController::class, 'v1']);
    Route::get('/dokumen', action: [RepositoryController::class, 'v2']);
    Route::get('/uji', action: [RepositoryController::class, 'p1']);

});