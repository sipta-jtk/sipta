<?php

namespace App\Modules\NotificationAndReminder\Controllers\Preferensi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PreferensiNotifikasi;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;

class PreferensiNotifikasiController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|boolean',
            'in_app' => 'required|boolean',
        ]);

        $tipe_notifikasi = 'semua'; 
        $whatsapp = false; 

        // Simpan atau perbarui preferensi berdasarkan username
        $user = Auth::user();
        PreferensiNotifikasi::updateOrCreate(
            ['username' => $user->username],
            [
                'email' => $request->email,
                'in_app' => $request->in_app,
                'tipe_notifikasi' => $tipe_notifikasi,  
                'whatsapp' => $whatsapp,  
            ]
        );

        return response()->json(['message' => 'Preferensi notifikasi berhasil diperbarui'], 200);
    }

    public function getPreferences()
    {
        $user = Auth::user();
        $preferences = PreferensiNotifikasi::where('username', $user->username)->first();

        return response()->json($preferences);
    }
}