<?php

namespace App\Modules\UserManagement\Controllers;

use App\Modules\Controller;
use App\Models\User;
use App\Models\PengajuanPisahKota;
use App\Models\Mahasiswa;
use App\Models\Kota;

class PengajuanPisahKoTAController extends Controller
{
    public function index()
    {       

        // Ambil data dari model baru
        $pengajuan = PengajuanPisahKota::with('kota')->get();
        
        return view('UserManagement.views.pengajuan-pisah-kota', compact('pengajuan'));
    }

    public function show($id)
    {
        // Ambil satu data sesuai id
        $pengajuan = PengajuanPisahKota::find($id);
        $mahasiswa = Mahasiswa::where('nim', $pengajuan->nim)->first();
        $kota = Kota::where('id_kota', $pengajuan->id_kota)->first();

        if (!$pengajuan) {
            return abort(404, 'Data tidak ditemukan');
        }
    
        return view('UserManagement.views.form-pisah-kota', compact('mahasiswa', 'kota', 'pengajuan'));
    }

    public function terima($id)
    {
        $pengajuan = PengajuanPisahKota::findOrFail($id);

        // Set id_kota jadi null di tabel mahasiswa
        $pengajuan->mahasiswa->update(['id_kota' => null]);
        $pengajuan->mahasiswa->update(['status_ta' => 'mahasiswa_non_ta']);

        // Hapus data pengajuan biar bersih
        $pengajuan->delete();

        return redirect()->route('pengajuan.pisah.kota')->with('success', 'Pengajuan pisah berhasil diterima.');
    }

    public function ajukan($id)
    {
        $user = auth()->user();
        $pengajuan = PengajuanPisahKota::findOrFail($id);

        // $pengajuan->nim = $user->mahasiswa->nim;
        // $pengajuan->id_kota = $user->mahasiswa->nim;
        //$pengajuan->fta_20 = $request->file('fta_20')->store('fta_20');
        //$pengajuan->status = 'diajukan';
        // $pengajuan->save();

        return redirect()->route('login')->with('success', 'Pengajuan pisah berhasil diajukan!');
    }
}
