<?php

namespace App\Modules\UserManagement\Controllers;

use Illuminate\Http\Request;
use App\Modules\Controller;
use App\Models\User;
use App\Models\Mahasiswa;
use App\Models\PengajuanPisahKota;
use App\Models\KoTA;

class FormPisahKoTAController extends Controller
{
    public function showFormPisah()
    {
        $user = auth()->user();
    
        // Ambil data mahasiswa
        $mahasiswa = Mahasiswa::where('nim', $user->username)->first();
    
        // Ambil data KoTA dari mahasiswa (tanpa bikin pengajuan)
        if($mahasiswa->status_ta == "mahasiswa_ta"){
            $kota = $mahasiswa->kota ?? null;

            if($mahasiswa->id_kota){
                $anggotaKelompok = Mahasiswa::where('id_kota', $mahasiswa->id_kota)
                    ->where('nim', '!=', $mahasiswa->nim)
                    ->get();
            }
        }

        $pengajuan = PengajuanPisahKota::where('nim', $mahasiswa->nim)->first();
        
        if ($pengajuan) {
            $alasan = $pengajuan->alasan;
        } else {
            $alasan = '';
        }
    
        return view('UserManagement.views.form-pisah-kota', compact('mahasiswa', 'kota', 'anggotaKelompok', 'pengajuan', 'alasan'));
    }
    

    public function ajukan(Request $request)
    {
        $user = auth()->user();
        $mahasiswa = $user->mahasiswa;
    
        $request->validate([
            'alasan' => 'string|max:512',
            'fta_20' => 'required|file|mimes:pdf|max:2048',
        ]);

        $existingPengajuan = PengajuanPisahKota::where('nim', $mahasiswa->nim)->first();
        $kotaAktif = Kota::where('id_kota', $mahasiswa->id_kota)
                        ->where('status_kota', 'aktif')
                        ->first();

        if (!$existingPengajuan && $kotaAktif) {
            // Simpan file PDF ke folder storage/app/public/fta
            $filePath = $request->file('fta_20')->store('fta', 'public');
            $alasan = $request->input('alasan');

            // Buat pengajuan baru kalau belum ada
            PengajuanPisahKota::create([
                'nim' => $mahasiswa->nim,
                'id_kota' => $mahasiswa->kota->id_kota,
                'fta_20' => $filePath,
                'alasan' => $alasan
            ]);
            return redirect()->back()->with('success', 'Pengajuan pisah berhasil!');
        }
        return redirect()->back()->with('info', 'Pengajuan sudah ada!');
    }

    public function prakota(Request $request)
    {

        $user = auth()->user();
        $mahasiswa = $user->mahasiswa;

        $prakota = Kota::where('id_kota', $mahasiswa->id_kota)
        ->where('status_kota', 'pra_kota')
        ->first();

        if ($prakota) {
            $mahasiswa->update(['id_kota' => null]);
            $mahasiswa->update(['status_ta' => 'mahasiswa_non_ta']);
        
            return redirect()->route('perekrutan-anggota-kota')->with('success', 'Berhasil berpisah');
        }
        

        return redirect()->back()->with('info', 'Gagal berpisah');
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