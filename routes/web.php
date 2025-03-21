<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\EmailController;
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

// routes/web.php
// Route::get('/settings', function() {
//     return view('settings'); // Sesuaikan dengan nama view yang kamu buat
// });

Route::get('/seeallnotif', function() {
    return view('NotificationAndReminder::LogUser.logUser'); // Sesuaikan dengan nama view yang kamu buat
});

Route::get('/log-admin', function() {
    return view('NotificationAndReminder::LogAdmin.logAdmin'); // Sesuaikan dengan nama view yang kamu buat
});

Route::get('/edit-notif', function() {
    return view('NotificationAndReminder::SettingNotification.SettingAwalNotif'); // Sesuaikan dengan nama view yang kamu buat
});

// Array statis untuk menyimpan data
$dataStore = [];

Route::post('/create-data', function (\Illuminate\Http\Request $request) use (&$dataStore) {
    // Buat ID unik untuk data baru
    $id = uniqid();

    // Simpan data baru ke array statis
    $dataStore[$id] = [
        'id' => $id,
        'value' => $request->input('value'),
    ];

    return response()->json($dataStore[$id]);
});

Route::put('/update-data/{id}', function ($id, \Illuminate\Http\Request $request) use (&$dataStore) {
    // Cek apakah data dengan ID tertentu ada
    if (!isset($dataStore[$id])) {
        return response()->json(['error' => 'Data tidak ditemukan.'], 404);
    }

    // Perbarui nilai data
    $dataStore[$id]['value'] = $request->input('value');

    return response()->json([
        'message' => 'Data berhasil diperbarui.',
        'new_value' => $dataStore[$id]['value'],
    ]);
});


// Route::post('/logout', function () {
//     auth()->logout();
//     return redirect('/login');
// })->name('logout');

Route::post('/kirim-email', [EmailController::class, 'kirimEmail']);

Route::get('/penentuan-ambang-batas', function () {
    return view('CekPlagiarisme.views.PenentuanAmbangBatas');
});
Route::get('/cek-plagiarisme', function () {
    return view('CekPlagiarisme.views.DaftarDokumen');
});
