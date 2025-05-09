<?php

namespace App\Modules\NotificationAndReminder\Controllers\SettingNotification;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TemplateNotifikasi;

class SettingAwalNotif extends Controller
{
    public function index()
    {
        $notifikasis = TemplateNotifikasi::all(); // Ambil semua data notifikasi dari database
        return view('NotificationAndReminder.views.SettingNotification.SettingAwalNotif', compact('notifikasis'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul_notifikasi' => 'required|string|max:255',
            'jenis_notifikasi' => 'required|string|max:20',
            'isi_in_apps' => 'required|string',
            'isi_in_email' => 'required|string',
        ]);

        TemplateNotifikasi::create($validated); // Simpan ke database

        return redirect()->route('notification_reminder.admin.notifikasi')->with('success', 'Notifikasi berhasil disimpan.');
    }

    public function edit($id)
    {
        $notifikasi = TemplateNotifikasi::findOrFail($id);
        return view('NotificationAndReminder.views.modals.edit-notifikasi', compact('notifikasi'));
    }
    
    public function update(Request $request, $id)
    {
        $notifikasi = TemplateNotifikasi::findOrFail($id);

        $validated = $request->validate([
            'judul_notifikasi' => 'required|string|max:255',
            'jenis_notifikasi' => 'required|string|max:20',
            'isi_in_apps' => 'required|string',
            'isi_in_email' => 'required|string',
        ]);

        $notifikasi->update($validated);

        return redirect()->route('notification_reminder.admin.notifikasi')->with('success', 'Notifikasi berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $notifikasi = TemplateNotifikasi::findOrFail($id);
        $notifikasi->delete();

        return redirect()->route('notification_reminder.admin.notifikasi')->with('success', 'Notifikasi berhasil dihapus.');
    }
}