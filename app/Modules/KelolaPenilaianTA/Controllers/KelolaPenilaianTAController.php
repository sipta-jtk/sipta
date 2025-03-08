<?php

namespace App\Modules\KelolaPenilaianTA\Controllers;

use App\Modules\Controller;
use Illuminate\View\View;

class KelolaPenilaianTAController extends Controller
{
    public function index(): View
    {
        $data = [];

        for ($i = 1; $i <= 100; $i++) {
            $data[] = [
                'nim' => "221524" . str_pad($i, 3, '0', STR_PAD_LEFT),
                'kota' => "KoTA " . str_pad($i, 3, '0', STR_PAD_LEFT),
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
}
