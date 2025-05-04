<?php

namespace App\Modules\PerencanaanDanPelaksanaanSeminar3DanSidang\Controllers;

use App\Modules\Controller;
use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Models\VerifikasiBerkasPengajuan;
use App\Models\Kota;
use Carbon\Carbon;
Carbon::setLocale('id');

class VerifikasiBerkasController extends Controller
{
    public function listPengajuan(Request $request, string $tipe): View
    {

        $jenisPengajuan = ($tipe === 'seminar-3') ? 'seminar_3' : 'sidang_akhir';

        $dataKota = VerifikasiBerkasPengajuan::with('kota')
            ->where('jenis_pengajuan', $jenisPengajuan)
            ->where('status_konfirmasi', 'pending')
            ->get()
            ->map(function ($item) {
                $item->tanggal_pengajuan = Carbon::parse($item->tanggal_pengajuan)->translatedFormat('d F Y');
                $item->judul_ta = $item->kota->judul_ta;
                return $item;
            });

        return view('PerencanaanDanPelaksanaanSeminar3DanSidang.views.kelolaBerkasPengajuan.DaftarPengajuanMahasiswa', compact('dataKota', 'tipe'));        
    }


    public function pengajuanDitolak(Request $request, string $tipe): View
    {
        
        $jenisPengajuan = ($tipe === 'seminar-3') ? 'seminar_3' : 'sidang_akhir';

        $dataKota = VerifikasiBerkasPengajuan::with('kota')
            ->where('jenis_pengajuan', $jenisPengajuan)
            ->where('status_konfirmasi', 'tidak_disetujui')
            ->get()
            ->map(function ($item) {
                $item->tanggal_pengajuan = Carbon::parse($item->tanggal_pengajuan)->translatedFormat('d F Y');
                $item->judul_ta = $item->kota->judul_ta;
                $item->catatan = $item->catatan ?? '-';
                return $item;
            });

        return view('PerencanaanDanPelaksanaanSeminar3DanSidang.views.kelolaBerkasPengajuan.DaftarPengajuanDitolak', compact('dataKota', 'tipe'));
    }

    public function pengajuanDiterima(Request $request, string $tipe): View
    {
        $jenisPengajuan = ($tipe === 'seminar-3') ? 'seminar_3' : 'sidang_akhir';

        $dataKota = VerifikasiBerkasPengajuan::with('kota')
            ->where('jenis_pengajuan', $jenisPengajuan)
            ->where('status_konfirmasi', 'disetujui')
            ->get()
            ->map(function ($item) {
                $item->tanggal_pengajuan = Carbon::parse($item->tanggal_pengajuan)->translatedFormat('d F Y');
                $item->judul_ta = $item->kota->judul_ta;
                $item->catatan = $item->catatan ?? '-';
                return $item;
            });

        return view('PerencanaanDanPelaksanaanSeminar3DanSidang.views.kelolaBerkasPengajuan.DaftarPengajuanDiterima', compact('dataKota', 'tipe'));
    }

    public function show(String $tipe, $idPengajuan): View
    {
        $dataKota = VerifikasiBerkasPengajuan::with('kota')
            ->where('id_pengajuan', $idPengajuan)
            ->first(); // Ganti dari get() ke first()

        if ($dataKota) {
            $dataKota->tanggal_pengajuan = Carbon::parse($dataKota->tanggal_pengajuan)->translatedFormat('d F Y');
            $dataKota->judul_ta = $dataKota->kota?->judul_ta ?? '-';

            // jika agenda = seminar 3
            if ($dataKota->jenis_pengajuan === 'seminar_3') {
                $dataKota->jenis_pengajuan = 'Seminar 3';
            } elseif ($dataKota->jenis_pengajuan === 'sidang_akhir') {
                $dataKota->jenis_pengajuan = 'Sidang Akhir';
            } else {
                $dataKota->jenis_pengajuan = '-';
            }
            
        }


        return view('PerencanaanDanPelaksanaanSeminar3DanSidang.views.kelolaBerkasPengajuan.DetailPengajuan', compact('dataKota', 'tipe'));
    }

    public function verifikasi(Request $request, String $tipe, int $id)
    {
        $keputusan = $request->input('keputusan');
        $catatan = $request->input('catatan', '');

        if ($keputusan === 'tidak_disetujui') {
            // Ambil data berkas berdasarkan ID
            $dataKota = Kota::where('id_kota', $id)->first(); 

            // Hapus berkas (jika ada)
            if ($dataKota && isset($dataKota->berkas)) {
                foreach (json_decode($dataKota->berkas) as $berkas) {
                    Storage::delete("public/berkas/{$berkas->file}");
                }
            }
        }

        VerifikasiBerkasPengajuan::where('id_pengajuan', $id)
            ->update([
                'status_konfirmasi' => $keputusan,
                'catatan' => $catatan,
                'tanggal_verifikasi' => Carbon::now()->format('Y-m-d H:i:s'),
            ]);
    
        return redirect()->route('kelola.berkas.list', ['tipe' => $tipe])
            ->with('success', "Pengajuan telah $keputusan.");
    }

}


