<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Timeline\Controllers\TimelineController;

Route::get('/timeline', [TimelineController::class, 'index'])->middleware(['auth'])->name('timeline');
Route::get('/timeline/detail/{id}', [TimelineController::class, 'detail'])->middleware(['auth'])->name('timeline.detail');
Route::get('/timeline/create', [TimelineController::class, 'create'])->middleware(['auth'])->name('timeline.create'); //menambahkan data
Route::get('/timeline/{id}', [TimelineController::class, 'detail'])->middleware(['auth'])->name('timeline.detail');
Route::post('/timeline/store', [TimelineController::class, 'store'])->middleware(['auth'])->name('timeline.store');
Route::get('/timeline/edit/{id}', [TimelineController::class, 'edit'])->middleware(['auth'])->name('timeline.edit');
Route::put('/timeline/update/{id}', [TimelineController::class, 'update'])->middleware(['auth'])->name('timeline.update');
Route::get('/timeline/search', [TimelineController::class, 'search'])->middleware(['auth'])->name('timeline.search');
Route::delete('/timeline/{id}', [TimelineController::class, 'destroy'])->middleware(['auth'])->name('timeline.destroy');
Route::get('/timeline', [TimelineController::class, 'index'])->name('timeline');
Route::get('/timeline/store', [TimelineController::class, 'store'])->name('timeline.store');
