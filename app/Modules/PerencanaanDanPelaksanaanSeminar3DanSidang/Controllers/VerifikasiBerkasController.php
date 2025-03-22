<?php

namespace App\Modules\PerencanaanDanPelaksanaanSeminar3DanSidang\Controllers;

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


        return view('PerencanaanDanPelaksanaanSeminar3DanSidang.views.kelolaBerkasPengajuan.DaftarPengajuanMahasiswa', compact('dataKota', 'tipe'));        
    }


    public function pengajuanDitolak(Request $request, string $tipe): View
    {
        
        $jenisPengajuan = ($tipe === 'seminar-3') ? 'seminar_3' : 'sidang_akhir';

        $dataKota = DB::table('verifikasi_berkas_pengajuan')
        ->join('kota', 'verifikasi_berkas_pengajuan.id_kota', '=', 'kota.id_kota')
        ->where('jenis_pengajuan', $jenisPengajuan)
        ->where('status_konfirmasi', 'tidak_disetujui')
        ->get();

        return view('PerencanaanDanPelaksanaanSeminar3DanSidang.views.kelolaBerkasPengajuan.DaftarPengajuanDitolak', compact('dataKota', 'tipe'));
    }

    public function pengajuanDiterima(Request $request, string $tipe): View
    {
        $jenisPengajuan = ($tipe === 'seminar-3') ? 'seminar_3' : 'sidang_akhir';

        $dataKota = DB::table('verifikasi_berkas_pengajuan')
        ->join('kota', 'verifikasi_berkas_pengajuan.id_kota', '=', 'kota.id_kota')
        ->where('jenis_pengajuan', $jenisPengajuan)
        ->where('status_konfirmasi', 'disetujui')
        ->get();

        return view('PerencanaanDanPelaksanaanSeminar3DanSidang.views.kelolaBerkasPengajuan.DaftarPengajuanDiterima', compact('dataKota', 'tipe'));
    }

    public function show(String $tipe, $idPengajuan): View
    {
        
        $dataKota = DB::table('verifikasi_berkas_pengajuan')
        ->join('kota', 'verifikasi_berkas_pengajuan.id_kota', '=', 'kota.id_kota')
        ->where('id_pengajuan', $idPengajuan)->first();

        return view('PerencanaanDanPelaksanaanSeminar3DanSidang.views.kelolaBerkasPengajuan.DetailPengajuan', compact('dataKota', 'tipe'));
    }

    public function verifikasi(Request $request, String $tipe, int $id)
    {
        $keputusan = $request->input('keputusan');
        $catatan = $request->input('catatan', '');

        if ($keputusan === 'tidak_disetujui') {
            // Ambil data berkas berdasarkan ID
            $dataKota = DB::table('kota')->where('id_kota', $id)->first();

            DB::table('verifikasi_berkas_pengajuan')
            ->where('id_pengajuan', $id)
            ->update([
                'status_konfirmasi' => $keputusan,
                'catatan' => $catatan,
                'tanggal_verifikasi' => Carbon::now()->format('Y-m-d H:i:s'),
            ]);

            // Hapus berkas (jika ada)
            if ($dataKota && isset($dataKota->berkas)) {
                foreach (json_decode($dataKota->berkas) as $berkas) {
                    Storage::delete("public/berkas/{$berkas->file}");
                }
            }

        } else {
            // Update status_konfirmasi
            DB::table('verifikasi_berkas_pengajuan')
            ->where('id_pengajuan', $id)
            ->update([
                'status_konfirmasi' => $keputusan,
                'catatan' => $catatan,
                'tanggal_verifikasi' => Carbon::now()->format('Y-m-d H:i:s'),
            ]);
        }
    
        


        return redirect()->route('kelola.berkas.list', ['tipe' => $tipe])
            ->with('success', "Pengajuan telah $keputusan.");
    }

}


