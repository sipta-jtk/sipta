<?php

namespace App\Modules\NotificationAndReminder\Controllers\LogAdmin;

use App\Http\Controllers\Controller;
use App\Models\NotifikasiKirim;
use Illuminate\Http\Request;
use Carbon\Carbon; // Tambahkan ini

class LogAdminController extends Controller
{
    public function getLogNotifications()
    {
        Carbon::setLocale('id'); // Set Bahasa Indonesia

        $logNotifikasi = NotifikasiKirim::with('user') 
            ->join('notifikasi', 'notifikasi_kirim.id_notifikasi', '=', 'notifikasi.id_notifikasi')
            ->select('notifikasi_kirim.waktu_kirim','notifikasi.judul','notifikasi.isi_notifikasi',
                        'notifikasi_kirim.respon_log','notifikasi_kirim.username')
            ->get();

        // Format waktu_kirim satu per satu
        $logNotifikasi->transform(function ($notif) {
            $notif->waktu_kirim = Carbon::parse($notif->waktu_kirim)->translatedFormat('H:i d F Y');
            return $notif;
        });

        return view('NotificationAndReminder::LogAdmin.logAdmin', compact('logNotifikasi'));
    }
}
