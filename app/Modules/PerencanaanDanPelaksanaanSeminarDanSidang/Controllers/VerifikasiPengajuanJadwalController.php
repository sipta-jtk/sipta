<?php

namespace App\Modules\PerencanaanDanPelaksanaanSeminarDanSidang\Controllers;

use App\Modules\Controller;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VerifikasiPengajuanJadwalController extends Controller
{
    public function getList(Request $request, String $tipe): View
    {
        // pindah ke versi 4
        // tipe_alokasi 1,2,3,4 di match ke dosbing1 dosbing2 penguji1 penguji2




        // Mendapatkan role dan nip dari user yang sedang login
        $nip = auth()->user()->username;
        $user = DB::table('users')->where('username', auth()->user()->username)->value('role');
        // Ganti tabel menjadi alokasi_penguji di versi 4
        $role = DB::table('alokasi_pembimbing')->where('nip', auth()->user()->username)->value('urutan_prioritas_terpilih');

        // Menentukan agenda berdasarkan tipe agar bisa dijadikan page berbeda
        $agenda = ($tipe == 'seminar-3') ? 'seminar_3' : 'sidang';

        // Menentukan kolom status yang akan dicek berdasarkan role
        // Kolom status yang akan diubah sesuai dengan role
        if ($role === 'dosen') {
            $statusColumns = ['pengajuan_jadwal_kota.status_dosen_pembimbing_1', 'pengajuan_jadwal_kota.status_dosen_pembimbing_2'];
        }
        
        $dataPengajuan = DB::table('alokasi_pembimbing')
            ->rightJoin('pengajuan_pembimbing', 'alokasi_pembimbing.id_pengajuan_pembimbing', '=', 'pengajuan_pembimbing.id_pengajuan_pembimbing')
            ->rightJoin('pengajuan_jadwal_kota', 'pengajuan_pembimbing.id_kota', '=', 'pengajuan_jadwal_kota.id_kota')
            ->rightJoin('penjadwalan', 'pengajuan_jadwal_kota.id_kota', '=', 'penjadwalan.id_kota')
            ->where('alokasi_pembimbing.nip', $nip)
            ->where('alokasi_pembimbing.status_alokasi', 'fix')
            ->where(function ($query) use ($statusColumns) {
                foreach ($statusColumns as $column) {
                    $query->orWhereNull($column);
                }
            })
            ->where('penjadwalan.agenda', $agenda)
            ->select([
                'alokasi_pembimbing.nip',
                'pengajuan_jadwal_kota.id_kota',
                'penjadwalan.agenda',
                'penjadwalan.id_ruangan',
                'penjadwalan.sesi',
                'penjadwalan.tanggal',
                'pengajuan_jadwal_kota.status_dosen_pembimbing_1',
                'pengajuan_jadwal_kota.status_dosen_pembimbing_2'
            ])
            ->get();

        return view('PerencanaanDanPelaksanaanSeminarDanSidang.views.ListPengajuanJadwal', compact('dataPengajuan', 'tipe'));
    }

    public function verifikasi(Request $request, int $id, string $tipe)
    {
        $keputusan = $request->input('keputusan');
        $catatan = $request->input('catatan', '');

        // Menentukan status verifikasi berdasarkan keputusan
        $status = ($keputusan === 'Ditolak') ? false : true;
        session()->put("status_verifikasi_$id", $status);

        // Cek apakah ada data sebelumnya di session
        $pengajuanSebelumnya = session()->get("pengajuan_$id", (object) []);

        // Simpan data ke session
        session()->put("pengajuan_$id", (object) [
            'kelompok' => $pengajuanSebelumnya->kelompok ?? 'KoTA 002',
            'judul_ta' => $pengajuanSebelumnya->judul_ta ?? 'Judul Tidak Diketahui',
            'status' => $status, // Simpan status sebagai boolean
            'catatan' => $catatan,
            'tanggal_pengajuan' => $pengajuanSebelumnya->tanggal_pengajuan ?? now(),
        ]);

        // Redirect ke halaman yang sesuai
        return redirect()->route('kelola.jadwal.list', ['tipe' => $tipe])
                        ->with('success', "Pengajuan telah " . ($status ? 'Disetujui' : 'Ditolak') . ".");
    }

    public function json(){

    //    Cari data pengajuan dengan detail kota
        // $dataPengajuan = DB::table('pengajuan_jadwal_kota')->join('kota', 'pengajuan_jadwal_kota.id_kota', '=', 'kota.id_kota')->get();
        
        $dataPengajuan = DB::table('alokasi_pembimbing')
        ->rightJoin('pengajuan_pembimbing', 'alokasi_pembimbing.id_pengajuan_pembimbing', '=', 'pengajuan_pembimbing.id_pengajuan_pembimbing')
        ->rightJoin('pengajuan_jadwal_kota', 'pengajuan_pembimbing.id_kota', '=', 'pengajuan_jadwal_kota.id_kota')
        ->rightJoin('penjadwalan', 'pengajuan_jadwal_kota.id_kota', '=', 'penjadwalan.id_kota')
        ->where('alokasi_pembimbing.nip', '197201061999031002')
        ->where('alokasi_pembimbing.status_alokasi', 'fix')
        ->where(function ($query) {
            $query->whereNull('pengajuan_jadwal_kota.status_dosen_pembimbing_1')
                  ->orWhereNull('pengajuan_jadwal_kota.status_dosen_pembimbing_2');
        })
        ->whereIn('penjadwalan.agenda', ['seminar_3', 'sidang'])
        ->select([
            'alokasi_pembimbing.nip',
            'pengajuan_jadwal_kota.id_kota',
            'penjadwalan.agenda',
            'penjadwalan.id_ruangan',
            'penjadwalan.sesi',
            'penjadwalan.tanggal',
            'pengajuan_jadwal_kota.status_dosen_pembimbing_1',
            'pengajuan_jadwal_kota.status_dosen_pembimbing_2'
        ])
        ->get();
    
    


        return view('PerencanaanDanPelaksanaanSeminarDanSidang.views.json', compact('dataPengajuan'));
    }

}

