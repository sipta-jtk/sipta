<?php

namespace App\Modules\PerencanaanDanPelaksanaanSeminarDanSidang\Controllers;

use App\Modules\Controller;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class VerifikasiBerkasController extends Controller
{
    public function listPengajuan(Request $request, string $tipe): View
    {

        $jenisPengajuan = ($tipe === 'seminar-3') ? 'seminar_3' : 'sidang_akhir';

        $dataKota = DB::table('verifikasi_berkas_pengajuan')
        ->join('kota', 'verifikasi_berkas_pengajuan.id_kota', '=', 'kota.id_kota')
        ->where('jenis_pengajuan', $jenisPengajuan)
        ->where('status_konfirmasi', 'pending')
        ->get();


        return view('PerencanaanDanPelaksanaanSeminarDanSidang.views.DaftarPengajuanMahasiswa', compact('dataKota', 'tipe'));        
    }


    public function pengajuanDitolak(Request $request, string $tipe): View
    {
        
        $jenisPengajuan = ($tipe === 'seminar-3') ? 'seminar_3' : 'sidang_akhir';

        $dataKota = DB::table('verifikasi_berkas_pengajuan')
        ->join('kota', 'verifikasi_berkas_pengajuan.id_kota', '=', 'kota.id_kota')
        ->where('jenis_pengajuan', $jenisPengajuan)
        ->where('status_konfirmasi', 'tidak_disetujui')
        ->get();

        return view('PerencanaanDanPelaksanaanSeminarDanSidang.views.DaftarPengajuanDitolak', compact('dataKota', 'tipe'));
    }

    public function pengajuanDiterima(Request $request, string $tipe): View
    {
        $jenisPengajuan = ($tipe === 'seminar-3') ? 'seminar_3' : 'sidang_akhir';

        $dataKota = DB::table('verifikasi_berkas_pengajuan')
        ->join('kota', 'verifikasi_berkas_pengajuan.id_kota', '=', 'kota.id_kota')
        ->where('jenis_pengajuan', $jenisPengajuan)
        ->where('status_konfirmasi', 'disetujui')
        ->get();

        return view('PerencanaanDanPelaksanaanSeminarDanSidang.views.DaftarPengajuanDiterima', compact('dataKota', 'tipe'));
    }

    public function show(String $tipe, $id){
        if ($tipe === 'berkas-seminar-3'){
            $dataKota = 
            (object) [
                'kelompok' => '001',
                'judul_ta' => 'Sistem Informasi Akademik Berbasis Web',
                'id_bidang' => 1,
                'jenis_pengajuan' => 'Seminar 3',
                'tanggal_pengajuan' => '2025-03-05',
                'berkas' => [
                    (object) ['nama' => 'Proposal TA', 'file' => 'proposal_ta.pdf'],
                    (object) ['nama' => 'Laporan TA', 'file' => 'laporan_ta.pdf'],
                    (object) ['nama' => 'Presentasi TA', 'file' => 'presentasi_ta.pptx']
                ],
            ];
        } else {
            $dataKota = 
            (object) [
                'kelompok' => '001',
                'judul_ta' => 'Sistem Informasi Informasi Akademik Berbasis Web',
                'id_bidang' => 1,
                'jenis_pengajuan' => 'Sidang Akhir',
                'tanggal_pengajuan' => '2025-03-05',
                'berkas' => [
                    (object) ['nama' => 'Proposal TA', 'file' => 'proposal_ta.pdf'],
                    (object) ['nama' => 'Laporan TA', 'file' => 'laporan_ta.pdf'],
                    (object) ['nama' => 'Presentasi TA', 'file' => 'presentasi_ta.pptx']
                ],
            ];
        }

        return view('PerencanaanDanPelaksanaanSeminarDanSidang.views.DetailPengajuan', compact('dataKota', 'tipe'));
    }

    public function verifikasi(Request $request, String $tipe, int $id)
    {
        $keputusan = $request->input('keputusan');
        $catatan = $request->input('catatan', '');

        if ($keputusan === 'Ditolak') {
            // Ambil data berkas berdasarkan ID
            $dataKota = DB::table('kota')->where('id_kota', $id)->first();

            // Hapus berkas (jika ada)
            if ($dataKota && isset($dataKota->berkas)) {
                foreach (json_decode($dataKota->berkas) as $berkas) {
                    Storage::delete("public/berkas/{$berkas->file}");
                }
            }

            $status = 'tidak_disetujui';
        } else {
            $status = 'disetujui';
        }
    
        // Update status_konfirmasi
        DB::table('verifikasi_berkas_pengajuan')
        ->where('id_pengajuan', $id)
        ->update([
            'status_konfirmasi' => $status,
            'catatan' => $catatan,
            'tanggal_verifikasi' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);


        return redirect()->route('kelola.berkas.list', ['tipe' => $tipe])
            ->with('success', "Pengajuan telah $status.");
    }

}


