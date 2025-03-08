<?php

namespace App\Modules\KelolaPenilaianTA\Controllers;

use App\Modules\Controller;
use Illuminate\View\View;
use App\Exports\RekapitulasiNilaiExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;

class KelolaPenilaianTAController extends Controller
{
    public function index(): View
    {
        $data = [];

        for ($i = 1; $i <= 100; $i++) {
            $data[] = [
                'nim' => "221524" . str_pad($i, 3, '0', STR_PAD_LEFT),
                'nama' => "Nama Mahasiswa #" . $i,
                'prodi' => (rand(0, 1) == 0) ? "D3-Teknik Informatika" : "D4-Teknik Informatika",
                'kelas' => (rand(0, 1) == 0) ? "4A" : "4B",
                'kelompok' => "Kelompok " . rand(1, 5),
                'seminar2_penguji1' => rand(50, 100),
                'seminar2_penguji2' => rand(50, 100),
                'seminar2_penguji3' => -1,
                'seminar3_penguji1' => rand(50, 100),
                'seminar3_penguji2' => rand(50, 100),
                'seminar3_penguji3' => rand(50, 100),
                'sidang_penguji1' => rand(50, 100),
                'sidang_penguji2' => rand(50, 100),
                'sidang_penguji3' => rand(50, 100),
                'pembimbing1' => rand(50, 100),
                'pembimbing2' => rand(50, 100),
                'uts' => rand(50, 100),
                'uas' => rand(50, 100),
                'lain_lain' => rand(50, 100),
                'nilai_akhir' => rand(50, 100),
                'predikat' => ["A", "B", "C", "D", "E"][rand(0, 4)]
            ];
        }

        return view('KelolaPenilaianTA.views.RekapitulasiNilai', compact('data'));
    }

    public function exportExcel(Request $request)
    {
        // Ambil filter dari request
        $filterProdi = $request->query('prodi');
        $filterKelas = $request->query('kelas');

        $data = [];
        for ($i = 1; $i <= 100; $i++) {
            $data[] = [
                'nim' => "221524" . str_pad($i, 3, '0', STR_PAD_LEFT),
                'nama' => "Nama Mahasiswa #" . $i,
                'prodi' => (rand(0, 1) == 0) ? "D3-Teknik Informatika" : "D4-Teknik Informatika",
                'kelas' => (rand(0, 1) == 0) ? "4A" : "4B",
                'kelompok' => "Kelompok " . rand(1, 5),
                'seminar2_penguji1' => rand(50, 100),
                'seminar2_penguji2' => rand(50, 100),
                'seminar2_penguji3' => -1,
                'seminar3_penguji1' => rand(50, 100),
                'seminar3_penguji2' => rand(50, 100),
                'seminar3_penguji3' => rand(50, 100),
                'sidang_penguji1' => rand(50, 100),
                'sidang_penguji2' => rand(50, 100),
                'sidang_penguji3' => rand(50, 100),
                'pembimbing1' => rand(50, 100),
                'pembimbing2' => rand(50, 100),
                'uts' => rand(50, 100),
                'uas' => rand(50, 100),
                'lain_lain' => rand(50, 100),
                'nilai_akhir' => rand(50, 100),
                'predikat' => ["A", "B", "C", "D", "E"][rand(0, 4)]
            ];
        }

        // Terapkan filter
        if ($filterProdi) {
            $data = array_filter($data, function ($item) use ($filterProdi) {
                return $item['prodi'] == $filterProdi;
            });
        }

        if ($filterKelas) {
            $data = array_filter($data, function ($item) use ($filterKelas) {
                return $item['kelas'] == $filterKelas;
            });
        }

        // Ubah ke array agar bisa diekspor
        $data = array_values($data);

        return Excel::download(new RekapitulasiNilaiExport($data), 'rekapitulasi_nilai.xlsx');
    }

}