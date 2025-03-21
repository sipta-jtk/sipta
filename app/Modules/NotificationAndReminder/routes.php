<?php

use Illuminate\Support\Facades\Route;
use App\Modules\NotificationAndReminder\Controllers\SettingNotification\SettingAwalNotif as SettingAwalNotifController;
use App\Modules\NotificationAndReminder\Controllers\LogModal\LogModalNotifController;
use App\Modules\NotificationAndReminder\Controllers\LogAdmin\LogAdminController;
use App\Modules\NotificationAndReminder\Controllers\Preferensi\PreferensiNotifikasiController;
use App\Modules\NotificationAndReminder\Controllers\EmailController;
use App\Modules\NotificationAndReminder\Controllers\PendaftaranController;

Route::get('/notification/admin/settingawal', [SettingAwalNotifController::class, 'index'])->name('notification_reminder.admin.notifikasi');
Route::post('/notification/admin/settingawal/store', [SettingAwalNotifController::class, 'store'])->name('notifikasi.store');
Route::get('/notification/admin/settingawal/edit/{id}', [SettingAwalNotifController::class, 'edit'])->name('notifikasi.edit');
Route::post('/notification/admin/settingawal/update/{id}', [SettingAwalNotifController::class, 'update'])->name('notifikasi.update');
Route::delete('/notification/admin/settingawal/delete/{id}', [SettingAwalNotifController::class, 'destroy'])->name('notifikasi.delete');


Route::get('/notification_reminder/admin/notifikasi', function () {
    return view('NotificationAndReminder::LogAdmin.logAdmin');
}); 

Route::get('/notification_reminder/user/notifikasi', function () {
    return view('NotificationAndReminder::LogUser.logUser');
});

// Route untuk mengambil notifikasi
Route::get('/api/notifications', [LogModalNotifController::class, 'getNotifications']);
Route::get('/api/notification/{id}', [LogModalNotifController::class, 'show']);
Route::get('/api/logAdmin', [LogAdminController::class, 'getLogNotifications']);

Route::get('/admin/log-admin', function() {
    return view('NotificationAndReminder::LogAdmin.logAdmin'); 
});

Route::get('/user/log-user', function() {
    return view('NotificationAndReminder::LogUser.logUser'); // Sesuaikan dengan nama view yang kamu buat
});

Route::get('/api/notification/{id}', [LogModalNotifController::class, 'show']);

Route::middleware(['auth'])->group(function () {
    Route::get('/preferensi-notifikasi/get', [PreferensiNotifikasiController::class, 'getPreferences'])->name('preferensi.notifikasi.get');
    Route::post('/preferensi-notifikasi', [PreferensiNotifikasiController::class, 'store'])->name('preferensi.notifikasi.store');
});

Route::post('/kirim-email', [EmailController::class, 'kirimEmail']);
Route::post('/daftar-user', [PendaftaranController::class, 'daftarUser']);
