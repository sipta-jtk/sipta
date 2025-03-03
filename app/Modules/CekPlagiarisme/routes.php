<?php

use Illuminate\Support\Facades\Route;
use App\Modules\CekPlagiarisme\Controllers\CekPlagiarismeController;

Route::get('/CekPlagiarisme', [CekPlagiarismeController::class, 'index']);