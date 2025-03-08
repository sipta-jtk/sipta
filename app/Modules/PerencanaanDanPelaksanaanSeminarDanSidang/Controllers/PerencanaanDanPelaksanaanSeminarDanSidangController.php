<?php

namespace App\Modules\PerencanaanDanPelaksanaanSeminarDanSidang\Controllers;

use App\Modules\Controller;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;
class PerencanaanDanPelaksanaanSeminarDanSidangController extends Controller
{
    public function index(): View
    {
        $data = [
            ['id' => 1, 'tanggal' => '2025-03-08', 'kelompok' => 'Kelompok A', 'judul' => 'Judul 1', 'status' => 'Belum Verifikasi'],
        ];
        return view('PerencanaanDanPelaksanaanSeminarDanSidang.views.DaftarPengajuanMahasiswa', compact('data'));
    }

    public function pengajuanDitolak(): View
    {
        $data = [
            ['id' => 2, 'tanggal' => '2025-03-07', 'kelompok' => 'Kelompok B', 'judul' => 'Judul 2', 'catatan' => 'Ditolak'],
        ];
        return view('PerencanaanDanPelaksanaanSeminarDanSidang.views.DaftarPengajuanDitolak', compact('data'));
    }

    public function pengajuanDiterima(): View
    {
        $data = [
            ['id' => 3, 'tanggal' => '2025-03-06', 'kelompok' => 'Kelompok C', 'judul' => 'Judul 3', 'status' => 'Diterima'],
        ];
        return view('PerencanaanDanPelaksanaanSeminarDanSidang.views.DaftarPengajuanDiterima', compact('data'));
    }
}


