<?php

namespace App\Modules\PerencanaanDanPelaksanaanSeminarDanSidang\Controllers;

use App\Modules\Controller;
use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Models\VerifikasiBerkasPengajuan;

class VerifikasiBerkasController extends Controller
{
    public function index(Request $request, string $tipe): View
    {
        if ($tipe === 'berkas-seminar-3'){
            $dataKota = VerifikasiBerkasPengajuan::all()->where('status_konfirmasi', 'disetujui');
        } else {
            $dataKota = VerifikasiBerkasPengajuan::all()->where('status_konfirmasi', 'disetujui');
        }
        return view('PerencanaanDanPelaksanaanSeminarDanSidang.views.DaftarPengajuanMahasiswa', compact('dataKota', 'tipe'));        
    }


    public function pengajuanDitolak(Request $request, string $tipe): View
    {
        if ($tipe === 'berkas-seminar-3'){
            $dataKota = [
                (object) [
                    'id' => 1,
                    'kelompok' => '001', // FK
                    'judul_ta' => 'Sistem Informasi Akademik Berbasis Web', // data ta
                    'nip' => '1234567890',
                    'status' => 'ditolak',
                    'catatan' => 'Berkas Tidak Valid',
                    'jenis_pengajuan' => 'Seminar 3',
                    'tanggal_pengajuan' => '2025-03-05',
                    'tanggal_verifikasi' => '2025-04-05',
                ],
            ];
        } else {
            $dataKota = [
                (object) [
                    'id' => 1,
                    'kelompok' => '001', // FK
                    'judul_ta' => 'Sistem Informasi Informasi Akademik Berbasis Web', // data ta
                    'nip' => '1234567890',
                    'status' => 'ditolak',
                    'catatan' => 'Berkas Tidak Valid',
                    'jenis_pengajuan' => 'Sidang Akhir',
                    'tanggal_pengajuan' => '2025-03-05',
                    'tanggal_verifikasi' => '2025-04-05',
                ],
            ];
        }

        return view('PerencanaanDanPelaksanaanSeminarDanSidang.views.DaftarPengajuanDitolak', compact('dataKota', 'tipe'));
    }

    public function pengajuanDiterima(Request $request, string $tipe): View
    {
        if ($tipe === 'berkas-seminar-3'){
            $dataKota = [
                (object) [
                    'id' => 1,
                    'kelompok' => '001', // FK
                    'judul_ta' => 'Sistem Informasi Akademik Berbasis Web', // data ta
                    'nip' => '1234567890',
                    'status' => 'diterima',
                    'catatan' => '',
                    'jenis_pengajuan' => 'Seminar 3',
                    'tanggal_pengajuan' => '2025-03-05',
                    'tanggal_verifikasi' => '2025-04-05',
                ],
            ];
        } else {
            $dataKota = [
                (object) [
                    'id' => 1,
                    'kelompok' => '001', // FK
                    'judul_ta' => 'Sistem Informasi Informasi Akademik Berbasis Web', // data ta
                    'nip' => '1234567890',
                    'status' => 'diterima',
                    'catatan' => '',
                    'jenis_pengajuan' => 'Sidang Akhir',
                    'tanggal_pengajuan' => '2025-03-05',
                    'tanggal_verifikasi' => '2025-04-05',
                ],
            ];
        }

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

    public function verifikasi(Request $request, $id, String $tipe)
    {
        $keputusan = $request->input('keputusan');
        $catatan = $request->input('catatan', '');

        if ($keputusan === 'Ditolak') {
            // Hapus berkas (jika ada)
            $dataKota = session()->get("pengajuan_$id");
            if ($dataKota && isset($dataKota->berkas)) {
                foreach ($dataKota->berkas as $berkas) {
                    Storage::delete("public/berkas/{$berkas->file}");
                }
            }
            $status = 'Ditolak';
        } else {
            $status = 'Disetujui';
        }

        // Simpan data yang diperbarui ke sesi (bisa diganti dengan database jika diperlukan)
        session()->put("pengajuan_$id", (object) [
            'kelompok' => 'KoTA 002',
            'judul_ta' => 'Pengembangan Aplikasi Monitoring Tugas Akhir di Jurusan Teknik Komputer dan Informatika',
            'status' => $keputusan === 'Ditolak' ? [] : (session()->get("pengajuan_$id")->status ?? []),
            'catatan' => $catatan,
            'tanggal_pengajuan' => '2025-03-06',
        ]);

        return redirect()->route('kelola.berkas.list', ['tipe' => $tipe])->with('success', "Pengajuan telah $status.");
    }

    public function json(): View
    {
        $kotas = VerifikasiBerkasPengajuan::all()->where('status_konfirmasi', 'disetujui');

        return view('PerencanaanDanPelaksanaanSeminarDanSidang.views.json', compact('kotas'));
    }
}


