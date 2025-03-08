<?php

namespace App\Modules\PerencanaanDanPelaksanaanSeminar3DanSidang\Controllers;

use App\Modules\Controller;
use Illuminate\View\View;

class PembatalanJadwalSeminarSidangController extends Controller
{
    public function index(): View
    {
        return view('PerencanaanDanPelaksanaanSeminar3DanSidang.views.view');
    }

    public function indexPersetujuanPembatalanJadwalSeminar(): View
    {
        $jadwal = collect([
            (object) [
                'ID' => 1,
                'kota_no' => '010',
                'judul_ta' => 'Sistem AI untuk Diagnosis Penyakit',
                'tanggal' => '2023-01-01',
                'sesi' => 1,
                'ruangan' => 'D221',
                'pembimbing' => 'Dr. John Doe',
                'penguji1' => 'Dr. Jane Doe',
                'penguji2' => 'Dr. Foo Bar',
                'alasan_pembatalan' => 'Tanggal tidak sesuai kesepakatan'
            ],
            (object) [
                'ID' => 2,
                'kota_no' => '012',
                'judul_ta' => 'Sistem AI untuk mendeteksi keaslian uang',
                'tanggal' => '2023-01-01',
                'sesi' => 2,
                'ruangan' => 'D221',
                'pembimbing' => 'Dr. John Doe',
                'penguji1' => 'Dr. Jane Doe',
                'penguji2' => 'Dr. Foo Bar',
                'alasan_pembatalan' => 'Tanggal tidak sesuai kesepakatan'
            ],
            (object) [
                'ID' => 3,
                'kota_no' => '014',
                'judul_ta' => 'Sistem AI untuk memprediksi cuaca',
                'tanggal' => '2023-01-01',
                'sesi' => 3,
                'ruangan' => 'D221',
                'pembimbing' => 'Dr. John Doe',
                'penguji1' => 'Dr. Jane Doe',
                'penguji2' => 'Dr. Foo Bar',
                'alasan_pembatalan' => 'Tanggal tidak sesuai kesepakatan'
            ],
        ]);
        return view('PerencanaanDanPelaksanaanSeminar3DanSidang.views.pembatalan.persetujuanpembatalanjadwalseminar', compact('jadwal'));
    }

    public function indexPersetujuanPembatalanJadwalSidang(): View
    {
        $jadwal = collect([
            (object) [
                'kota_no' => '010',
                'judul_ta' => 'Sistem AI untuk Diagnosis Penyakit',
                'tanggal' => '2023-01-01',
                'sesi' => 1,
                'ruangan' => 'D221',
                'pembimbing' => 'Dr. John Doe',
                'penguji1' => 'Dr. Jane Doe',
                'penguji2' => 'Dr. Foo Bar',
                'alasan_pembatalan' => 'Tanggal tidak sesuai kesepakatan'
            ],
            (object) [
                'kota_no' => '012',
                'judul_ta' => 'Sistem AI untuk mendeteksi keaslian uang',
                'tanggal' => '2023-01-01',
                'sesi' => 2,
                'ruangan' => 'D221',
                'pembimbing' => 'Dr. John Doe',
                'penguji1' => 'Dr. Jane Doe',
                'penguji2' => 'Dr. Foo Bar',
                'alasan_pembatalan' => 'Tanggal tidak sesuai kesepakatan'
            ],
            (object) [
                'kota_no' => '014',
                'judul_ta' => 'Sistem AI untuk memprediksi cuaca',
                'tanggal' => '2023-01-01',
                'sesi' => 3,
                'ruangan' => 'D221',
                'pembimbing' => 'Dr. John Doe',
                'penguji1' => 'Dr. Jane Doe',
                'penguji2' => 'Dr. Foo Bar',
                'alasan_pembatalan' => 'Tanggal tidak sesuai kesepakatan'
            ],
        ]);
        return view('PerencanaanDanPelaksanaanSeminar3DanSidang.views.pembatalan.persetujuanpembatalanjadwalsidang', compact('jadwal'));
    }

    public function indexJadwalSeminar(): View
    {
        $jadwal = collect([
            (object) [
                'ID' => 1,
                'kota_no' => '010',
                'judul_ta' => 'Sistem AI untuk Diagnosis Penyakit',
                'tanggal' => '2023-01-01',
                'sesi' => 1,
                'ruangan' => 'D221',
                'pembimbing' => 'Dr. John Doe',
                'penguji1' => 'Dr. Jane Doe',
                'penguji2' => 'Dr. Foo Bar',
                'alasan_pembatalan' => 'Tanggal tidak sesuai kesepakatan'
            ],
            (object) [
                'ID' => 2,
                'kota_no' => '012',
                'judul_ta' => 'Sistem AI untuk mendeteksi keaslian uang',
                'tanggal' => '2023-01-01',
                'sesi' => 2,
                'ruangan' => 'D221',
                'pembimbing' => 'Dr. John Doe',
                'penguji1' => 'Dr. Jane Doe',
                'penguji2' => 'Dr. Foo Bar',
                'alasan_pembatalan' => 'Tanggal tidak sesuai kesepakatan'
            ],
            (object) [
                'ID' => 3,
                'kota_no' => '014',
                'judul_ta' => 'Sistem AI untuk memprediksi cuaca',
                'tanggal' => '2023-01-01',
                'sesi' => 3,
                'ruangan' => 'D221',
                'pembimbing' => 'Dr. John Doe',
                'penguji1' => 'Dr. Jane Doe',
                'penguji2' => 'Dr. Foo Bar',
                'alasan_pembatalan' => 'Tanggal tidak sesuai kesepakatan'
            ],
        ]);
        return view('PerencanaanDanPelaksanaanSeminar3DanSidang.views.jadwal.jadwalseminar', compact('jadwal'));
    }

    public function indexJadwalSidang(): View
    {
        $jadwal = collect([
            (object) [
                'ID' => 1,
                'kota_no' => '010',
                'judul_ta' => 'Sistem AI untuk Diagnosis Penyakit',
                'tanggal' => '2023-01-01',
                'sesi' => 1,
                'ruangan' => 'D221',
                'pembimbing' => 'Dr. John Doe',
                'penguji1' => 'Dr. Jane Doe',
                'penguji2' => 'Dr. Foo Bar',
                'alasan_pembatalan' => 'Tanggal tidak sesuai kesepakatan'
            ],
            (object) [
                'ID' => 2,
                'kota_no' => '012',
                'judul_ta' => 'Sistem AI untuk mendeteksi keaslian uang',
                'tanggal' => '2023-01-01',
                'sesi' => 2,
                'ruangan' => 'D221',
                'pembimbing' => 'Dr. John Doe',
                'penguji1' => 'Dr. Jane Doe',
                'penguji2' => 'Dr. Foo Bar',
                'alasan_pembatalan' => 'Tanggal tidak sesuai kesepakatan'
            ],
            (object) [
                'ID' => 3,
                'kota_no' => '014',
                'judul_ta' => 'Sistem AI untuk memprediksi cuaca',
                'tanggal' => '2023-01-01',
                'sesi' => 3,
                'ruangan' => 'D221',
                'pembimbing' => 'Dr. John Doe',
                'penguji1' => 'Dr. Jane Doe',
                'penguji2' => 'Dr. Foo Bar',
                'alasan_pembatalan' => 'Tanggal tidak sesuai kesepakatan'
            ],
        ]);
        return view('PerencanaanDanPelaksanaanSeminar3DanSidang.views.jadwal.jadwalsidang', compact('jadwal'));
    }

}