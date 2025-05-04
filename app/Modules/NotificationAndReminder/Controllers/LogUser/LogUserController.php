<?php

namespace App\Modules\NotificationAndReminder\Controllers\LogUser;

use App\Http\Controllers\Controller;
use App\Models\NotifikasiKirim;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Services\Notifikasi;


class LogUserController extends Controller
{
public function getLogUserNotifications()
{
    Carbon::setLocale('id'); // Set Bahasa Indonesia

    $username = Auth::user()->username; // Ambil username user yang login
    // dd(Auth::user()->nama);

    $logUserNotifikasi = NotifikasiKirim::with('user')
        ->join('notifikasi', 'notifikasi_kirim.id_notifikasi', '=', 'notifikasi.id_notifikasi')
        ->where('notifikasi_kirim.username', $username) // Filter berdasarkan user login
        ->select(
            'notifikasi_kirim.waktu_kirim',
            'notifikasi.judul',
            'notifikasi.isi_notifikasi'
        )
        ->orderBy('notifikasi_kirim.waktu_kirim', 'desc')
        ->paginate(10);

        Notifikasi::kirim(
            '[Pemberitahuan] Dosen Pembimbing Tugas AKhir Telah Ditetapkan!', // Judul template notifikasi
            '221524051', // Ganti dengan username admin, atau log system
            [
                'nama' => $username,
                'Topik' => 'User membuka halaman log',
                'deadline' => now()->format('d-m-Y H:i')
            ]
        );
    


    $logUserNotifikasi->getCollection()->transform(function ($notif) {
        $notif->waktu_kirim = Carbon::parse($notif->waktu_kirim)->translatedFormat('d F Y H:i');
        return $notif;
    });
    
    return view('NotificationAndReminder::LogUser.logUser', compact('logUserNotifikasi'));
}
}