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
        $role = auth()->check() ? auth()->user()->role : 'unauthorized';
        // dd($tipe);
        // 1. Get profile dosen dari session yang sedang
        if ($tipe === 'seminar-3' && $role === 'dosen') {
            $dataPengajuan = PengajuanJadwalKota::where('tipe', 'seminar-3')->get();
        } else {
            $dataPengajuan = PengajuanJadwalKota::where('tipe', 'sidang-akhir')->get();
        }

        // if ($tipe === 'seminar-3' && $role === 'dosen') {
        //     $dataPengajuan = [
        //         (object) [
        //             'ID' => 1,
        //             'kelompok' => '010',
        //             'tanggal_pengajuan' => '2023-01-01',
        //             'judul_ta' => 'Sistem AI untuk Diagnosis Penyakit',
        //             'tanggal_kegiatan' => '2023-01-02',
        //             'sesi' => 1,
        //             'ruangan' => 'D221',
        //         ],
        //     ];
        // } else {
        //     $dataPengajuan = [
        //         (object) [
        //             'ID' => 2,
        //             'kelompok' => '011',
        //             'tanggal_pengajuan' => '2023-01-01',
        //             'judul_ta' => 'Sistem AI untuk Diagnosis Penyakit',
        //             'tanggal_kegiatan' => '2023-01-02',
        //             'sesi' => 3,
        //             'ruangan' => 'D221',
        //             'status_mahasiswa' => '',
        //             'status_pembimbing1' => '',
        //             'status_pembimbing2' => '',
        //             'status_penguji1' => '',
        //             'status_penguji2' => '',
        //             'status_koordinatorTA' => '',
        //             'nip' => '1234567890',
        //         ],
        //     ];
        // }

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
        $dataPengajuan = DB::table('pengajuan_jadwal_kota')->join('kota', 'pengajuan_jadwal_kota.id_kota', '=', 'kota.id_kota')->get();
        



        return view('PerencanaanDanPelaksanaanSeminarDanSidang.views.json', compact('dataPengajuan'));
    }

}

