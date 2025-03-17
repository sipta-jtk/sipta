<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Repository\Controllers\RepositoryController;

Route::get('/repository', [RepositoryController::class, 'index']);
Route::get('/repository/home', [RepositoryController::class, 'home']);
Route::get('/repository/search', [RepositoryController::class, 'search']);
Route::get('/repository', [RepositoryController::class, 'list'])->name('repository.list');