<?php

namespace App\Modules\PerencanaanDanPelaksanaanSeminar3DanSidang\Controllers;

use Carbon\Carbon;
use App\Modules\Controller;
use App\Models\Kota;
use App\Models\Mahasiswa;
use App\Models\Penjadwalan;
use App\Models\PengajuanJadwalKota;
use App\Models\VerifikasiBerkasPengajuan;

use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class VerifikasiBerkasPengajuanMahasiswaController extends Controller
{
    public function create()
    {
        $pengajuan = VerifikasiBerkasPengajuan::where('nip', Auth::user()->nip)->latest()->first();
        return view('verifikasi.create', compact('pengajuan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'jenis_pengajuan' => 'required|in:seminar3,sidang',
        ]);

        VerifikasiBerkasPengajuan::create([
            'nip' => Auth::user()->nip,
            'status_konfirmasi' => 'pending',
            'tanggal_pengajuan' => Carbon::now(),
            'jenis_pangajuan' => $request->jenis_pengajuan,
        ]);

        return redirect()->route('verifikasi.create')->with('success', 'Pengajuan berhasil diajukan.');
    }
}