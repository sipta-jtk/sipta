<?php

namespace App\Modules\PerencanaanDanPelaksanaanSeminar3DanSidang\Controllers;

use App\Modules\Controller;
use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Models\VerifikasiBerkasPengajuan;
use App\Models\Kota;
use App\Models\Dokumen;
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
                $item->nama_kota = $item->kota->nama_kota ?? '-';
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
                $item->nama_kota = $item->kota->nama_kota ?? '-';
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
                $item->nama_kota = $item->kota->nama_kota ?? '-';
                $item->catatan = $item->catatan ?? '-';
                return $item;
            });

        return view('PerencanaanDanPelaksanaanSeminar3DanSidang.views.kelolaBerkasPengajuan.DaftarPengajuanDiterima', compact('dataKota', 'tipe'));
    }

    public function show(String $tipe, $idKota): View
    {
        // Jika tipe adalah seminar-3, kategori menjadi seminar3, jika tipe adalah sidang, kategori menjadi sidang
        $kategori = ($tipe === 'seminar-3') ? 'seminar3' : 'sidang';

        // Informasi Kota
        $dataKota = VerifikasiBerkasPengajuan::with('kota')
            ->where('id_Kota', $idKota)
            ->where('status_konfirmasi', 'pending')
            ->first();
        
        // Transformasi Data
        if ($dataKota) {
                $dataKota->tanggal_pengajuan = Carbon::parse($dataKota->tanggal_pengajuan)->translatedFormat('d F Y');
                $dataKota->judul_ta = $dataKota->kota?->judul_ta ?? '-';
                $dataKota->nama_kota = $dataKota->kota?->nama_kota ?? '-';
                

                if ($dataKota->jenis_pengajuan === 'seminar_3') {
                    $dataKota->jenis_pengajuan = 'Seminar 3';
                } elseif ($dataKota->jenis_pengajuan === 'sidang_akhir') {
                    $dataKota->jenis_pengajuan = 'Sidang Akhir';
                } else {
                    $dataKota->jenis_pengajuan = '-';
                }
            }

        // Dokumen Persyaratan yang akan ditampilkan dengan versi terakhir
        $fileTA = Dokumen::where('id_kota', $idKota)
        ->where('kategori', $kategori)
        ->where('id_subkategori', 1)
        ->orderByDesc('versi')
        ->first();
    
        $filePresentasi = Dokumen::where('id_kota', $idKota)
        ->where('kategori', $kategori)
        ->where('id_subkategori', 2)
        ->orderByDesc('versi')
        ->first();   

        if ($kategori === 'seminar3') {
            $ftaSatu = Dokumen::where('id_kota', $idKota)
                ->where('kategori', $kategori)
                ->where('id_subkategori', 2)
                ->where('kode_fta', 'FTA-10')
                ->orderByDesc('versi')
                ->first();

            $ftaDua = Dokumen::where('id_kota', $idKota)
                ->where('kategori', $kategori)
                ->where('id_subkategori', 2)
                ->where('kode_fta', 'FTA-10a')
                ->orderByDesc('versi')
                ->first();
        }

        if ($kategori === 'sidang') {
            $ftaSatu = Dokumen::where('id_kota', $idKota)
                ->where('kategori', $kategori)
                ->where('id_subkategori', 2)
                ->where('kode_fta', 'FTA-14')
                ->orderByDesc('versi')
                ->first();

            $ftaDua = Dokumen::where('id_kota', $idKota)
                ->where('kategori', $kategori)
                ->where('id_subkategori', 2)
                ->where('kode_fta', 'FTA-14a')
                ->orderByDesc('versi')
                ->first();
        }

        $daftarDokumen = collect([$fileTA, $filePresentasi, $ftaSatu, $ftaDua])->filter(); // hindari null
        
        return view('PerencanaanDanPelaksanaanSeminar3DanSidang.views.kelolaBerkasPengajuan.DetailPengajuan', compact('dataKota', 'daftarDokumen','tipe', 'kategori'));
    }

    public function verifikasi(Request $request, String $tipe, int $id)
    {
        $keputusan = $request->input('keputusan');
        $catatan = $request->input('catatan', '');
        $nip = auth()->user()->username;

        VerifikasiBerkasPengajuan::where('id_pengajuan', $id)
            ->update([
                'nip' => $nip,
                'status_konfirmasi' => $keputusan,
                'catatan' => $catatan,
                'tanggal_verifikasi' => Carbon::now()->format('Y-m-d H:i:s'),
            ]);

        if ($keputusan === 'tidak_disetujui') {
            $keputusan = 'ditolak';
        }
    
        return redirect()->route('kelola.berkas.list', ['tipe' => $tipe])
            ->with('success', "Pengajuan telah $keputusan.");
    }
}


