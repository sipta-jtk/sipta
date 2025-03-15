<?php

namespace App\Modules\KelolaPenilaianTA\Controllers;

use App\Modules\Controller;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Exports\RekapitulasiNilaiExport;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\FromCollection;

class RekapitulasiNilaiController extends Controller{

        /**
     * Menampilkan halaman rekapitulasi nilai
     * 
     */
    public function getRekapNilaiSidang(): View
    {
        $data = [];
    
        for ($i = 1; $i <= 100; $i++) {
            $seminar2Penguji1 = rand(50, 100);
            $seminar2Penguji2 = rand(50, 100);
            $seminar2Penguji3 = rand(50, 100);
            $seminar3Penguji1 = rand(50, 100);
            $seminar3Penguji2 = rand(50, 100);
            $seminar3Penguji3 = rand(50, 100);
            $sidangPenguji1 = rand(50, 100);
            $sidangPenguji2 = rand(50, 100);
            $sidangPenguji3 = rand(50, 100);
            $pembimbing1 = rand(50, 100);
            $pembimbing2 = rand(50, 100);
    
            // Menghitung rata-rata nilai
            $rataSeminar2 = round(($seminar2Penguji1 + $seminar2Penguji2 + $seminar2Penguji3) / 3, 2);
            $rataSeminar3 = round(($seminar3Penguji1 + $seminar3Penguji2 + $seminar3Penguji3) / 3, 2);
            $rataSidang = round(($sidangPenguji1 + $sidangPenguji2 + $sidangPenguji3) / 3, 2);
            $rataPembimbing = round(($pembimbing1 + $pembimbing2) / 2, 2);
    
            $data[] = [
                'nim' => "221524" . str_pad($i, 3, '0', STR_PAD_LEFT),
                'nama' => "Nama Mahasiswa #" . $i,
                'prodi' => (rand(0, 1) == 0) ? "D3-Teknik Informatika" : "D4-Teknik Informatika",
                'kelas' => (rand(0, 1) == 0) ? "4A" : "4B",
                'kelompok' => "Kelompok " . rand(1, 5),
                'seminar2Penguji1' => $seminar2Penguji1,
                'seminar2Penguji2' => $seminar2Penguji2,
                'seminar2Penguji3' => $seminar2Penguji3,
                'rataSeminar2' => $rataSeminar2, // Menambahkan rata-rata seminar 2
                'seminar3Penguji1' => $seminar3Penguji1,
                'seminar3Penguji2' => $seminar3Penguji2,
                'seminar3Penguji3' => $seminar3Penguji3,
                'rataSeminar3' => $rataSeminar3, // Menambahkan rata-rata seminar 3
                'sidangPenguji1' => $sidangPenguji1,
                'sidangPenguji2' => $sidangPenguji2,
                'sidangPenguji3' => $sidangPenguji3,
                'rataSidang' => $rataSidang, // Menambahkan rata-rata sidang akhir
                'pembimbing1' => $pembimbing1,
                'pembimbing2' => $pembimbing2,
                'rataPembimbing' => $rataPembimbing, // Menambahkan rata-rata pembimbing
            ];
        }
    
        return view('KelolaPenilaianTA.views.rekapitulasi-nilai.rekapitulasi_nilai_sidang', compact('data'));
    }
    
    
    public function getRekapNilaiAkhir(): View
    {
        $data = [];
    
        for ($i = 1; $i <= 100; $i++) {
            $nilaiUts = rand(50, 100);
            $nilaiUas = rand(50, 100);
            $nilaiLainLain = rand(50, 100);
            $bobotUts = 0.4;
            $bobotUas = 0.4;
            $bobotLainLain = 0.2;

            // Menghitung nilai akhir
            $nilaiAkhir = round((($nilaiUts * $bobotUts) + ($nilaiUas * $bobotUas) + ($nilaiLainLain * $bobotLainLain)) / ($bobotUts + $bobotUas + $bobotLainLain), 2);
            
            switch (true) {
                case $nilaiAkhir >= 85:
                    $predikat = 'A';
                    break;
                case $nilaiAkhir >= 80:
                    $predikat = 'A-';
                    break;
                case $nilaiAkhir >= 75:
                    $predikat = 'B+';
                    break;
                case $nilaiAkhir >= 70:
                    $predikat = 'B';
                    break;
                case $nilaiAkhir >= 65:
                    $predikat = 'B-';
                    break;
                case $nilaiAkhir >= 60:
                    $predikat = 'C+';
                    break;
                case $nilaiAkhir >= 55:
                    $predikat = 'C';
                    break;
                case $nilaiAkhir >= 50:
                    $predikat = 'C-';
                    break;
                default:
                    $predikat = 'D';
                    break;
            }

            $data[] = [
                'nim' => "221524" . str_pad($i, 3, '0', STR_PAD_LEFT),
                'nama' => "Nama Mahasiswa #" . $i,
                'prodi' => (rand(0, 1) == 0) ? "D3-Teknik Informatika" : "D4-Teknik Informatika",
                'kelas' => (rand(0, 1) == 0) ? "4A" : "4B",
                'kelompok' => "Kelompok " . rand(1, 5),
                'nilaiUts' => $nilaiUts,
                'nilaiUas' => $nilaiUas,
                'nilaiLainLain' => $nilaiLainLain,
                'nilaiAkhir' => $nilaiAkhir,
                'predikat' => $predikat,
            ];
        }
    
        return view('KelolaPenilaianTA.views.rekapitulasi-nilai.rekapitulasi_nilai_akhir', compact('data'));
    }

    /**
     * Export data rekapitulasi nilai ke dalam file excel
     * 
     */
    public function exportExcelNilaiSidang(Request $request)
    {
        // Ambil filter dari request
        $filterProdi = $request->query('prodi');
        $filterKelas = $request->query('kelas');
    
        $data = [];
        for ($i = 1; $i <= 100; $i++) {
            $seminar2Penguji1 = rand(50, 100);
            $seminar2Penguji2 = rand(50, 100);
            $seminar2Penguji3 = rand(50, 100);
            $seminar3Penguji1 = rand(50, 100);
            $seminar3Penguji2 = rand(50, 100);
            $seminar3Penguji3 = rand(50, 100);
            $sidangPenguji1 = rand(50, 100);
            $sidangPenguji2 = rand(50, 100);
            $sidangPenguji3 = rand(50, 100);
            $pembimbing1 = rand(50, 100);
            $pembimbing2 = rand(50, 100);
    
            // Menghitung rata-rata nilai
            $rataSeminar2 = round(($seminar2Penguji1 + $seminar2Penguji2 + $seminar2Penguji3) / 3, 2);
            $rataSeminar3 = round(($seminar3Penguji1 + $seminar3Penguji2 + $seminar3Penguji3) / 3, 2);
            $rataSidang = round(($sidangPenguji1 + $sidangPenguji2 + $sidangPenguji3) / 3, 2);
            $rataPembimbing = round(($pembimbing1 + $pembimbing2) / 2, 2);
    
            $data[] = [
                'nim' => "221524" . str_pad($i, 3, '0', STR_PAD_LEFT),
                'nama' => "Nama Mahasiswa #" . $i,
                'prodi' => (rand(0, 1) == 0) ? "D3-Teknik Informatika" : "D4-Teknik Informatika",
                'kelas' => (rand(0, 1) == 0) ? "4A" : "4B",
                'kelompok' => "Kelompok " . rand(1, 5),
                'seminar2Penguji1' => $seminar2Penguji1,
                'seminar2Penguji2' => $seminar2Penguji2,
                'seminar2Penguji3' => $seminar2Penguji3,
                'rataSeminar2' => $rataSeminar2,
                'seminar3Penguji1' => $seminar3Penguji1,
                'seminar3Penguji2' => $seminar3Penguji2,
                'seminar3Penguji3' => $seminar3Penguji3,
                'rataSeminar3' => $rataSeminar3,
                'sidangPenguji1' => $sidangPenguji1,
                'sidangPenguji2' => $sidangPenguji2,
                'sidangPenguji3' => $sidangPenguji3,
                'rataSidang' => $rataSidang,
                'pembimbing1' => $pembimbing1,
                'pembimbing2' => $pembimbing2,
                'rataPembimbing' => $rataPembimbing,
            ];
        }
    
        // Terapkan filter jika ada
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