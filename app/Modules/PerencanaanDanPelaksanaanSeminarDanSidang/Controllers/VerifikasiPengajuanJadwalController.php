<?php

namespace App\Modules\PerencanaanDanPelaksanaanSeminarDanSidang\Controllers;

use App\Modules\Controller;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class VerifikasiPengajuanJadwalController extends Controller
{
    public function getListAsKoordinatorTA(Request $request, String $tipe): View
    {
        $nip = auth()->user()->username;
        
        $role = DB::table('dosen')->where('nip', $nip)->value('role_dosen');

        $agenda = ($tipe == 'seminar-3') ? 'seminar_3' : 'sidang';
        
        $dataPengajuan = DB::table('pengajuan_jadwal_kota')
        ->join('penjadwalan', 'pengajuan_jadwal_kota.id_penjadwalan', '=', 'penjadwalan.id_penjadwalan')
        ->join('kota', 'pengajuan_jadwal_kota.id_kota', '=', 'kota.id_kota')
        ->select(
            'pengajuan_jadwal_kota.id_penjadwalan',
            'kota.id_kota',
            'kota.judul_ta',
            'penjadwalan.agenda',
            'penjadwalan.tanggal',
            'penjadwalan.id_ruangan',
            'penjadwalan.sesi',
            'pengajuan_jadwal_kota.status_koordinator_ta'
        )
        ->where('penjadwalan.agenda', $agenda)
        ->where('pengajuan_jadwal_kota.status_dosen_penguji_1', 1)
        ->where('pengajuan_jadwal_kota.status_dosen_penguji_2', 1)
        ->get();
        

        return view('PerencanaanDanPelaksanaanSeminarDanSidang.views.ListPengajuanJadwalKoordinator', compact('dataPengajuan', 'tipe'));
    }

    public function getListAsDosenPembimbing(Request $request, String $tipe): View
    {
        $nip = auth()->user()->username;
        
        $role = DB::table('alokasi_dosen')->where('nip', $nip)->value('tipe_alokasi');

        $agenda = ($tipe == 'seminar-3') ? 'seminar_3' : 'sidang';

        $dataPengajuan = DB::table('penjadwalan')
        ->join('pengajuan_jadwal_kota', 'pengajuan_jadwal_kota.id_penjadwalan', '=', 'penjadwalan.id_penjadwalan')
        ->leftJoin('kota', 'kota.id_kota', '=', 'pengajuan_jadwal_kota.id_kota')
        ->leftJoin('pengajuan_pembimbing', 'pengajuan_pembimbing.id_kota', '=', 'kota.id_kota')
        ->leftJoin('alokasi_dosen', 'pengajuan_pembimbing.id_pengajuan_pembimbing', '=', 'alokasi_dosen.id_pengajuan_pembimbing')
        ->where('alokasi_dosen.status_alokasi', 'fix')
        ->where('alokasi_dosen.tipe_alokasi', 'pembimbing')
        ->where('penjadwalan.agenda', $agenda)
        ->whereRaw("
            (alokasi_dosen.urutan_prioritas = 1 AND pengajuan_jadwal_kota.status_dosen_pembimbing_1 IS NULL) 
            OR 
            (alokasi_dosen.urutan_prioritas = 2 AND pengajuan_jadwal_kota.status_dosen_pembimbing_2 IS NULL)
        ")
        ->select(
            'pengajuan_jadwal_kota.id_penjadwalan',
            'kota.id_kota',
            'kota.judul_ta',
            'penjadwalan.agenda',
            'penjadwalan.tanggal',
            'penjadwalan.id_ruangan',
            'penjadwalan.sesi',
            'pengajuan_jadwal_kota.status_dosen_pembimbing_1',
            'pengajuan_jadwal_kota.status_dosen_pembimbing_2',
            'alokasi_dosen.urutan_prioritas_terpilih'
        )
        ->get();
    
        return view('PerencanaanDanPelaksanaanSeminarDanSidang.views.ListPengajuanJadwalDosen', compact('dataPengajuan', 'tipe'));
    }
    
    public function getListAsDosenPenguji(Request $request, String $tipe): View
    {
        $nip = auth()->user()->username;
        
        $role = DB::table('alokasi_dosen')->where('nip', $nip)->value('tipe_alokasi');

        $agenda = ($tipe == 'seminar-3') ? 'seminar_3' : 'sidang';

        $dataPengajuan = DB::table('penjadwalan')
        ->join('pengajuan_jadwal_kota', 'pengajuan_jadwal_kota.id_penjadwalan', '=', 'penjadwalan.id_penjadwalan')
        ->leftJoin('kota', 'kota.id_kota', '=', 'pengajuan_jadwal_kota.id_kota')
        ->leftJoin('pengajuan_pembimbing', 'pengajuan_pembimbing.id_kota', '=', 'kota.id_kota')
        ->leftJoin('alokasi_dosen', 'pengajuan_pembimbing.id_pengajuan_pembimbing', '=', 'alokasi_dosen.id_pengajuan_pembimbing')
        ->where('alokasi_dosen.status_alokasi', 'fix')
        ->where('alokasi_dosen.tipe_alokasi', 'penguji')
        ->where('penjadwalan.agenda', $agenda)
        ->whereRaw("
            (alokasi_dosen.urutan_prioritas = 1 AND pengajuan_jadwal_kota.status_dosen_penguji_1 IS NULL) 
            OR 
            (alokasi_dosen.urutan_prioritas = 2 AND pengajuan_jadwal_kota.status_dosen_penguji_2 IS NULL)
        ")
        ->select(
            'pengajuan_jadwal_kota.id_penjadwalan',
            'kota.id_kota',
            'kota.judul_ta',
            'penjadwalan.agenda',
            'penjadwalan.tanggal',
            'penjadwalan.id_ruangan',
            'penjadwalan.sesi',
            'pengajuan_jadwal_kota.status_dosen_penguji_1',
            'pengajuan_jadwal_kota.status_dosen_penguji_2',
            'alokasi_dosen.urutan_prioritas_terpilih'
        )
        ->get();
    
        return view('PerencanaanDanPelaksanaanSeminarDanSidang.views.ListPengajuanJadwalDosen', compact('dataPengajuan', 'tipe'));
    }


    public function verifikasiAsKoordinatorTA(Request $request, string $tipe, int $idPenjadwalan)
    {
        // Ambil status verifikasi dari request
        $status = $request->input('status_verifikasi') === 'Ditolak' ? false : true;

        // fungsi to api farhan


        DB::table('pengajuan_jadwal_kota')
        ->where('id_penjadwalan', $idPenjadwalan)
        ->update(['status_koordinator_ta' => $status]);

        // Redirect kembali ke halaman dengan pesan
        return redirect()->route('kelola.jadwal.list', ['tipe' => $tipe])
                        ->with('success', "Status verifikasi: " . ($status ? 'Disetujui' : 'Ditolak'));
    }


}

