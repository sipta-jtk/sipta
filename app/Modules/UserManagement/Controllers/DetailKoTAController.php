<?php

namespace App\Modules\UserManagement\Controllers;

use Illuminate\Http\Request;
use App\Modules\Controller;
use App\Models\User;
use App\Models\Kota;
use App\Models\Mahasiswa;
use App\Models\Bidang;

class DetailKoTAController extends Controller
{
    public function index($id)
    {
        // Ambil data KoTA berdasarkan ID
        $kota = Kota::find($id);

        if (!$kota)
        {
            return redirect()->back()->with('error',  'Kelompok TA tidak ditemukan.');
        }

        // Ambil data anggota kelompok TA
        $anggota = Mahasiswa::join('user', 'mahasiswa.nim', '=', 'user.username')
            ->where('mahasiswa.id_kota', $id)
            ->select('mahasiswa.*', 'user.nama')
            ->get();

        // Ambil data bidang jika ada
        $bidang = null;
        if ($kota->id_bidang)
        {
            $bidang = Bidang::find($kota->id_bidang);
        }

        // Judul TA
        $judulTA = $kota->judul_ta ?? 'Belum ditentukan';

        // Jika bidang TA belum ditentukan
        $bidangTA = $bidang->bidang ?? 'Belum ditentukan';

        return view('UserManagement.views.detail-kota', compact('kota', 'anggota', 'judulTA', 'bidangTA'));
    }
}
