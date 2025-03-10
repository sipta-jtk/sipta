<?php

namespace App\Modules\NotificationAndReminder\Controllers\SettingNotification;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SettingAwalNotif extends Controller
{
    public function render()
    {
        // Dummy data notifikasi
        $notifikasi = [
            (object) ['judul' => 'Notifikasi 1', 'isi' => 'Ini adalah notifikasi pertama', 'trigger' => 'Login'],
            (object) ['judul' => 'Notifikasi 2', 'isi' => 'Ini adalah notifikasi kedua', 'trigger' => 'Logout'],
            (object) ['judul' => 'Notifikasi 3', 'isi' => 'Ini adalah notifikasi ketiga', 'trigger' => 'Pendaftaran'],
        ];

        // Merender view dengan path relatif yang benar
        return view('NotificationAndReminder::SettingNotification.SettingAwalNotif', compact('notifikasi'));
    }

    public function store(Request $request)
    {
        // Validasi inputan
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'isi' => 'required|string',
            'trigger' => 'required|string|max:255',
        ]);

        // Simulasi penyimpanan data
        session()->flash('success', 'Notifikasi berhasil disimpan.');

        // Redirect kembali ke halaman yang sesuai
        return redirect()->route('notification_reminder.admin.notifikasi');
    }
}