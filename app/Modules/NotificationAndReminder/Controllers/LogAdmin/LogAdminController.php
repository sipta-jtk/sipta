<?php

namespace App\Modules\NotificationAndReminder\Controllers\LogAdmin;

use App\Http\Controllers\Controller;
use App\Models\NotifikasiKirim;
use Illuminate\Http\Request;

class LogAdminController extends Controller
{
    public function getLogNotifications()
    {
        $logNotifikasi = NotifikasiKirim::join('notifikasi', 'notifikasi_kirim.id_notifikasi', '=', 'notifikasi.id_notifikasi')
            ->select('notifikasi_kirim.waktu_kirim', 'notifikasi.judul', 'notifikasi.isi_notifikasi', 'notifikasi_kirim.respon_log', 'notifikasi_kirim.username')
            ->get();

        return response()->json($logNotifikasi);
    }
}
