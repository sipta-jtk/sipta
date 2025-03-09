<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RuanganController;
use App\Http\Controllers\GedungController;
use Illuminate\Support\Facades\File;

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

foreach (scandir($modulesPath) as $module) {
    $routesFile = "{$modulesPath}/{$module}/routes.php";

    if (is_file($routesFile)) {
        require $routesFile; 
    }
}

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', function () {
    return view('auth.login');
});

Route::get('/register', function () {
    return view('auth.register');
});

// Route untuk manajemen ruangan
Route::resource('ruangan', RuanganController::class);
Route::get('/ruangan', [RuanganController::class, 'index'])->name('ruangan.index');
Route::get('/ruangan/create', [RuanganController::class, 'create'])->name('ruangan.create');
Route::post('/ruangan', [RuanganController::class, 'store'])->name('ruangan.store');

// Route untuk manajemen gedung (jika belum ada)
Route::resource('gedung', GedungController::class);

Route::get('ruangan/{filename}', function ($filename) {
    $path = base_path('ruangan/' . $filename);
    
    if (!File::exists($path)) {
        abort(404);
    }
    
    return response()->file($path);
})->where('filename', '.*');