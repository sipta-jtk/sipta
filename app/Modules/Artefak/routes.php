<?php

use App\Modules\Artefak\Controllers\SubmissionController;
use Illuminate\Support\Facades\Route;
use App\Modules\Artefak\Controllers\ArtefakController;

//Artefak
Route::get('/artefak', [ArtefakController::class, 'index'])->middleware(['auth'])->name('artefak');
Route::get('/artefak/detail/{id}', [ArtefakController::class, 'detail'])->middleware(['auth'])->name('artefak.detail');
Route::get('/artefak/create', [ArtefakController::class, 'create'])->middleware(['auth'])->name('artefak.create'); //menambahkan data
Route::get('/artefak/{id}', [ArtefakController::class, 'detail'])->middleware(['auth'])->name('artefak.detail');
Route::post('/artefak/store', [ArtefakController::class, 'store'])->middleware(['auth'])->name('artefak.store');
Route::get('/artefak/edit/{id}', [ArtefakController::class, 'edit'])->middleware(['auth'])->name('artefak.edit');
Route::put('/artefak/update{id}', [ArtefakController::class, 'update'])->middleware(['auth'])->name('artefak.update');
Route::post('/artefak/search', [ArtefakController::class, 'search'])->middleware(['auth'])->name('artefak.search');
Route::delete('/artefak/{id}', [ArtefakController::class, 'destroy'])->middleware(['auth'])->name('artefak.destroy');
//Pengumpulan Artefak
Route::get('/artefak/{artefak_id}/submit', [SubmissionController::class, 'create'])->middleware(['auth'])->name('submissions.create');
Route::post('/artefak/{artefak_id}/submit', [SubmissionController::class, 'store'])->middleware(['auth'])->name('submissions.store');
Route::delete('submissions/{id}', [SubmissionController::class, 'destroy'])->middleware(['auth'])->name('submissions.destroy');

