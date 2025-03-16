<?php

namespace App\Modules\UserManagement\Controllers;

use Illuminate\Http\Request;
use App\Modules\Controller;
use App\Models\User;
use App\Models\Mahasiswa;
use App\Models\PengajuanPisahKota;

class FormPisahKoTAController extends Controller
{
    public function showFormPisah()
    {
        $user = auth()->user();
    
        // Ambil data mahasiswa
        $mahasiswa = Mahasiswa::where('nim', $user->username)->first();
    
        // Ambil data KoTA dari mahasiswa (tanpa bikin pengajuan)
        $kota = $mahasiswa->kota ?? null;

        //
        $pengajuan = PengajuanPisahKota::where('nim', $mahasiswa->nim)->first();
    
        return view('UserManagement.views.form-pisah-kota', compact('mahasiswa', 'kota', 'pengajuan'));
    }
    

    public function ajukan(Request $request)
    {
        $user = auth()->user();
        $mahasiswa = $user->mahasiswa;
    
        $request->validate([
            'fta_20' => 'required|file|mimes:pdf|max:2048',
        ]);

        $existingPengajuan = PengajuanPisahKota::where('nim', $mahasiswa->nim)->first();

        if (!$existingPengajuan) {
            // Simpan file PDF ke folder storage/app/public/fta
            $filePath = $request->file('fta_20')->store('fta', 'public');

            // Buat pengajuan baru kalau belum ada
            PengajuanPisahKota::create([
                'nim' => $mahasiswa->nim,
                'id_kota' => $mahasiswa->kota->id_kota,
                'fta_20' => $filePath,
            ]);
            return redirect()->back()->with('success', 'Pengajuan pisah berhasil!');
        }
        return redirect()->back()->with('info', 'Pengajuan sudah ada!');
    }

    public function batal()
    {
        $user = auth()->user();
        $nim = $user->mahasiswa->nim;

        // Hapus pengajuan berdasarkan NIM
        PengajuanPisahKota::where('nim', $nim)->delete();

        return redirect()->back()->with('success', 'Pengajuan pisah berhasil dibatalkan!');
    }
    
}