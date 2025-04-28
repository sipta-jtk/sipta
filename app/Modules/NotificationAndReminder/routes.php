<?php

use Illuminate\Support\Facades\Route;
use App\Modules\NotificationAndReminder\Controllers\SettingNotification\SettingAwalNotif as SettingAwalNotifController;
use App\Modules\NotificationAndReminder\Controllers\LogModal\LogModalNotifController;
use App\Modules\NotificationAndReminder\Controllers\LogAdmin\LogAdminController;
use App\Modules\NotificationAndReminder\Controllers\Preferensi\PreferensiNotifikasiController;
use App\Modules\NotificationAndReminder\Controllers\EmailController;
use App\Modules\NotificationAndReminder\Controllers\PendaftaranController;


Route::prefix('/notification/admin')->middleware('auth', 'can:admin')->group(function () {

    // Rute untuk menampilkan halaman pengaturan notifikasi
    Route::get('/settingawal', [SettingAwalNotifController::class, 'index'])
        ->name('notification_reminder.admin.notifikasi');

    // Rute untuk menyimpan notifikasi
    Route::post('/settingawal/store', [SettingAwalNotifController::class, 'store'])
        ->name('notifikasi.store');

    // Rute untuk menampilkan halaman edit notifikasi berdasarkan ID
    Route::get('/settingawal/edit/{id}', [SettingAwalNotifController::class, 'edit'])
        ->name('notifikasi.edit');

    // Rute untuk memperbarui notifikasi berdasarkan ID
    Route::post('/settingawal/update/{id}', [SettingAwalNotifController::class, 'update'])
        ->name('notifikasi.update');

    // Rute untuk menghapus notifikasi berdasarkan ID
    Route::delete('/settingawal/delete/{id}', [SettingAwalNotifController::class, 'destroy'])
        ->name('notifikasi.delete');
});

Route::get('/sipta/api/notifications', [LogModalNotifController::class, 'getNotifications']);
Route::get('/sipta/api/notification/{id}', [LogModalNotifController::class, 'show']);
Route::get('/api/logAdmin', [LogAdminController::class, 'getLogNotifications']);
//hrs login dl dan sebagai admin
Route::middleware(['auth', 'can:admin'])->get('/api/logAdmin', [LogAdminController::class, 'getLogNotifications'])->name('logAdmin');

Route::group(['prefix' => 'user/log-user', 'middleware' => (['auth', 'can:mahasiswa'])], function () {
    Route::get('/', function() {
        return view('NotificationAndReminder::LogUser.logUser');
    });
});

Route::get('/api/notification/{id}', [LogModalNotifController::class, 'show']);

Route::middleware(['auth'])->group(function () {
    Route::get('/preferensi-notifikasi/get', [PreferensiNotifikasiController::class, 'getPreferences'])->name('preferensi.notifikasi.get');
    Route::post('/preferensi-notifikasi', [PreferensiNotifikasiController::class, 'store'])->name('preferensi.notifikasi.store');
});

Route::post('/kirim-email', [EmailController::class, 'kirimEmail']);
Route::post('/daftar-user', [PendaftaranController::class, 'daftarUser']);
