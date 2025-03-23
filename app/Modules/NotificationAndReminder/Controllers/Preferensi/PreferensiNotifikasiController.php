<?php

namespace App\Modules\NotificationAndReminder\Controllers\Preferensi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PreferensiNotifikasi;
use Illuminate\Support\Facades\Auth;

class PreferensiNotifikasiController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|boolean',
            'reminder_h5' => 'required|boolean', 
        ]);

        $whatsapp = false; 

        $user = Auth::user();
        PreferensiNotifikasi::updateOrCreate(
            ['username' => $user->username],
            [
                'email' => $request->email,
                'reminder_h5' => $request->reminder_h5,
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
