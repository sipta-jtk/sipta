<?php

namespace App\Modules\PerencanaanDanPelaksanaanSeminar3DanSidang\Controllers;

use App\Modules\Controller;
use Illuminate\View\View;

class PerencanaanDanPelaksanaanSeminar3DanSidangController extends Controller
{
    public function index(): View
    {
        return view('PerencanaanDanPelaksanaanSeminar3DanSidang.views.view');
    }

    public function indexPengajuan(): View
    {
        $artifact = (object) [
            'seminar1' => true,
            'seminar2' => true,
            'seminar3' => false, // Misalnya seminar 3 belum selesai
            'berkas' => true
        ];

        return view('PerencanaanDanPelaksanaanSeminar3DanSidang.views.pengajuan.daftarpengajuan', compact('artifact'));
    }

    public function indexPengajuanSeminar3(): View
    {
        $dataKota = (object) [
            'kota_no' => '103',
            'judul_ta' => 'Pengembangan Aplikasi Monitoring Tugas Akhir di Jurusan Teknik Komputer dan Informatika',
            'mahasiswa' => [
                (object) ['nama' => 'Mahasiswa 1', 'nim' => '101010101'],
                (object) ['nama' => 'Mahasiswa 2', 'nim' => '202020202'],
                (object) ['nama' => 'Mahasiswa 3', 'nim' => '303030303'],
            ],
            'pembimbing' => [
                (object) ['nama' => 'Dosen Pembimbing 1', 'nip' => '101010101'],
                (object) ['nama' => 'Dosen Pembimbing 2', 'nip' => '202020202']
            ]
        ];

        return view('PerencanaanDanPelaksanaanSeminar3DanSidang.views.pengajuan.seminar3', compact('dataKota'));
    }

    public function indexPengajuanSidang(): View
    {
        $dataKota = (object) [
            'kota_no' => '101',
            'judul_ta' => 'Pengembangan Aplikasi Monitoring Tugas Akhir di Jurusan Teknik Komputer dan Informatika',
            'mahasiswa' => [
                (object) ['nama' => 'Mahasiswa 1', 'nim' => '101010101'],
                (object) ['nama' => 'Mahasiswa 2', 'nim' => '202020202'],
                (object) ['nama' => 'Mahasiswa 3', 'nim' => '303030303'],
            ],
            'pembimbing' => (object) [
                'nama' => 'Dosen Pembimbing',
                'nip' => '101010101'
            ],
            'penguji' => [
                (object) ['nama' => 'Penguji 1', 'nip' => '101010101'],
                (object) ['nama' => 'Penguji 2', 'nip' => '202020202']
            ]
        ];
        return view('PerencanaanDanPelaksanaanSeminar3DanSidang.views.pengajuan.sidang', compact('dataKota'));
    }
}