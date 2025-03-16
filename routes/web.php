<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

$modulesPath = base_path('app/Modules');

if (is_dir($modulesPath)) {
    foreach (scandir($modulesPath) as $module) {
        if ($module === '.' || $module === '..') {
            continue;
        }
        $routesFile = "{$modulesPath}/{$module}/routes.php";

        if (is_file($routesFile)) {
            require $routesFile; 
        }
    }
}

Route::get('/', function () {
    return view('welcome');
})->middleware('auth'); // Hanya user login yang bisa akses

Route::post('/logout', function () {
    auth()->logout();
    return redirect('/login');
})->name('logout');

Route::get('/penentuan-ambang-batas', function () {
    return view('CekPlagiarisme.views.PenentuanAmbangBatas');
});
Route::get('/cek-plagiarisme', function () {
    return view('CekPlagiarisme.views.DaftarDokumen');
});



