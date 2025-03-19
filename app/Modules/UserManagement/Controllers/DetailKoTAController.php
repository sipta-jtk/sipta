<?php

namespace App\Modules\UserManagement\Controllers;

use Illuminate\Http\Request;
use App\Modules\Controller;
use App\Models\User;
use App\Models\Kota;
use App\Models\Mahasiswa;
use App\Models\Bidang;
use App\Models\PengajuanPembimbing;
use App\Models\AlokasiPembimbing;
use Illuminate\Support\Facades\DB;

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

        // Ambil data dosen pembimbing
        $pembimbing = DB::table('pengajuan_pembimbing')
            ->join('alokasi_pembimbing', 'pengajuan_pembimbing.id_pengajuan_pembimbing', '=', 'alokasi_pembimbing.id_pengajuan_pembimbing')
            ->join('dosen', 'alokasi_pembimbing.nip', '=', 'dosen.nip')
            ->join('user', 'dosen.nip', '=', 'user.username')
            ->where('pengajuan_pembimbing.id_kota', $id)
            ->orderBy('alokasi_pembimbing.urutan_prioritas_terpilih', 'asc')
            ->select('alokasi_pembimbing.urutan_prioritas_terpilih', 'user.nama', 'dosen.nip')
            ->get();

        // Jika tidak ada pembimbing yang ditemukan
        if ($pembimbing->isEmpty()) {
            $pembimbing = [
                (object)['urutan_prioritas_terpilih' => 1, 'nama' => 'Belum ditentukan', 'nip' => '-'],
                (object)['urutan_prioritas_terpilih' => 2, 'nama' => 'Belum ditentukan', 'nip' => '-']
            ];
        }

        return view('UserManagement.views.detail-kota', compact('kota', 'anggota', 'judulTA', 'bidangTA', 'pembimbing'));
    }
}
