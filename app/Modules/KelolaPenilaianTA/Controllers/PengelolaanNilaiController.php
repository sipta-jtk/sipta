<?php

namespace App\Modules\KelolaPenilaianTA\Controllers;

use App\Modules\Controller;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Exports\RekapitulasiNilaiExport;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\FromCollection;

class PengelolaanNilaiController extends Controller{
    /**
     * Menampilkan halaman kelola penilaian
     * 
     */
    public function kelolaNilai(): View
    {
        $data = [
            'header' => 'Kelola Penilaian',
            'kategori' => [
                'Seminar 1',
                'Seminar 2',
                'Seminar 3',
                'Sidang Akhir'
            ]
        ];

        return view('KelolaPenilaianTA.views.pengelolaan-nilai.kelola_penilaian_ta', compact('data'));
    }

    /**
     * Menampilkan halaman detail nilai mahasiswa
     * 
     */
    public function detailNilaiMahasiswa($kategori): View
    {
        $data = [];
        $kategori = Str::title(str_replace('-', ' ', $kategori));

        for ($i = 1; $i <= 100; $i++) {
            $data[] = [
                'nama' => "Nama Mahasiswa #" . $i,
                'kelompok' => rand(1, 100),
                'penguji1' => 'penguji 1',
                'penguji2' => 'penguji 2',
                'penguji3' => 'penguji 3',
                'pembimbing1' => rand(50, 100),
                'pembimbing2' => rand(50, 100),
                'p1' => rand(50, 100),
                'p2' => rand(50, 100),
                'p3' => rand(50, 100),
                'rata_rata' => rand(50, 100),
            ];
        }

        return view('KelolaPenilaianTA.views.pengelolaan-nilai.detail_nilai_mahasiswa', compact('data', 'kategori'));
    }
}