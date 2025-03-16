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

        foreach ($mahasiswaList as $mahasiswa) {
            // Default nilai null
            $nilaiKategori = [
                'seminar2Penguji1' => null,
                'seminar2Penguji2' => null,
                'seminar2Penguji3' => null,
                'seminar3Penguji1' => null,
                'seminar3Penguji2' => null,
                'seminar3Penguji3' => null,
                'sidangPenguji1' => null,
                'sidangPenguji2' => null,
                'sidangPenguji3' => null,
                'pembimbing1' => null,
                'pembimbing2' => null,
            ];

            // Mengelompokkan nilai berdasarkan kategori dan penguji
            foreach ($mahasiswa->nilaiKategori as $nilai) {
                if (!$nilai->kategoriPenilaian || !$nilai->dosen) {
                    continue; // Lewati jika kategori atau dosen null
                }

                $kategoriNama = strtolower(str_replace(' ', '', $nilai->kategoriPenilaian->nama_kategori));

                if ($kategoriNama == "seminar2") {
                    if (is_null($nilaiKategori['seminar2Penguji1'])) {
                        $nilaiKategori['seminar2Penguji1'] = $nilai->nilai;
                    } elseif (is_null($nilaiKategori['seminar2Penguji2'])) {
                        $nilaiKategori['seminar2Penguji2'] = $nilai->nilai;
                    } else {
                        $nilaiKategori['seminar2Penguji3'] = $nilai->nilai;
                    }
                } elseif ($kategoriNama == "seminar3") {
                    if (is_null($nilaiKategori['seminar3Penguji1'])) {
                        $nilaiKategori['seminar3Penguji1'] = $nilai->nilai;
                    } elseif (is_null($nilaiKategori['seminar3Penguji2'])) {
                        $nilaiKategori['seminar3Penguji2'] = $nilai->nilai;
                    } else {
                        $nilaiKategori['seminar3Penguji3'] = $nilai->nilai;
                    }
                } elseif ($kategoriNama == "sidangakhir") {
                    if (is_null($nilaiKategori['sidangPenguji1'])) {
                        $nilaiKategori['sidangPenguji1'] = $nilai->nilai;
                    } elseif (is_null($nilaiKategori['sidangPenguji2'])) {
                        $nilaiKategori['sidangPenguji2'] = $nilai->nilai;
                    } else {
                        $nilaiKategori['sidangPenguji3'] = $nilai->nilai;
                    }
                } elseif ($kategoriNama == "dosenpembimbing") {
                    if (is_null($nilaiKategori['pembimbing1'])) {
                        $nilaiKategori['pembimbing1'] = $nilai->nilai;
                    } else {
                        $nilaiKategori['pembimbing2'] = $nilai->nilai;
                    }
                }
            }

            // Hitung rata-rata dengan validasi null
            $nilaiKategori['rataSeminar2'] = $this->calculateAverage([$nilaiKategori['seminar2Penguji1'], $nilaiKategori['seminar2Penguji2'], $nilaiKategori['seminar2Penguji3']]);
            $nilaiKategori['rataSeminar3'] = $this->calculateAverage([$nilaiKategori['seminar3Penguji1'], $nilaiKategori['seminar3Penguji2'], $nilaiKategori['seminar3Penguji3']]);
            $nilaiKategori['rataSidang'] = $this->calculateAverage([$nilaiKategori['sidangPenguji1'], $nilaiKategori['sidangPenguji2'], $nilaiKategori['sidangPenguji3']]);
            $nilaiKategori['rataPembimbing'] = $this->calculateAverage([$nilaiKategori['pembimbing1'], $nilaiKategori['pembimbing2']]);

            // Dapatkan sumber nilai dan bobot uts, uas, dan lain-lain dari kategori penilaian apa
            $komponen_nilai_akhir = KomponenNilaiAkhir::select(
                'komponen_nilai_akhir.nama_komponen',
                'komponen_nilai_akhir.bobot_komponen as bobot',
                'kategori_penilaian.nama_kategori'
            )
            ->leftJoin('sumber_nilai', 'komponen_nilai_akhir.id_komponen', '=', 'sumber_nilai.id_komponen')
            ->leftJoin('kategori_penilaian', 'kategori_penilaian.id_kategori', '=', 'sumber_nilai.sumber')
            ->get()
            ->map(function ($item) {
                return [
                    'komponen' => $item->nama_komponen,
                    'bobot' => $item->bobot,
                    'sumber_nilai' => $item->nama_kategori
                ];
            });

            // // Hitung nilai uts
            // if ($komponen_nilai_akhir->komponen == "uts"){
            //     switch ($komponen_nilai_akhir->sumber_nilai) {
            //         case "Seminar 2":
            //             $nilai_komponen['uts'] = $nilaiKategori['rataSeminar2'] * $komponen_nilai_akhir->bobot;
            //             break;
            //         case "Seminar 3":
            //             $nilai_komponen['uts'] = $nilaiKategori['rataSeminar3'] * $komponen_nilai_akhir->bobot;
            //             break;
            //         case "Sidang Akhir":
            //             $nilai_komponen['uts'] = $nilaiKategori['rataSidang'] * $komponen_nilai_akhir->bobot;
            //             break;
            //         case "Dosen Pembimbing":
            //             $nilai_komponen['uts'] = $nilaiKategori['rataPembimbing'] * $komponen_nilai_akhir->bobot;
            //             break;
            //         default:
            //             $nilai_komponen['uts'] = 0;
            //     }
            // }else if ($komponen_nilai_akhir->komponen == "uas"){
            //     switch ($komponen_nil_akhir->sumber_nilai) {
            //         case "Seminar 2":
            //             $nilai_komponen['uas'] = $nilaiKategori['rataSeminar2'] * $komponen_nilai_akhir->bobot;
            //             break;
            //         case "Seminar 3":
            //             $nilai_komponen['uas'] = $nilaiKategori['rataSeminar3'] * $komponen_nilai_akhir->bobot;
            //             break;
            //         case "Sidang Akhir":
            //             $nilai_komponen['uas'] = $nilaiKategori['rataSidang'] * $komponen_nilai_akhir->bobot;
            //             break;
            //         case "Dosen Pembimbing":
            //             $nilai_komponen['uas'] = $nilaiKategori['rataPembimbing'] * $komponen_nilai_akhir->bobot;
            //             break;
            //         default:
            //             $nilai_komponen['uas'] = 0;
            //     }
            // }else{
            //     switch ($komponen_nilai_akhir->sumber_nilai) {
            //         case "Seminar 2":
            //             $nilai_komponen['lain-lain'] = $nilaiKategori['rataSeminar2'] * $komponen_nilai_akhir->bobot;
            //             break;
            //         case "Seminar 3":
            //             $nilai_komponen['lain-lain'] = $nilaiKategori['rataSeminar3'] * $komponen_nilai_akhir->bobot;
            //             break;
            //         case "Sidang Akhir":
            //             $nilai_komponen['lain-lain'] = $nilaiKategori['rataSidang'] * $komponen_nilai_akhir->bobot;
            //             break;
            //         case "Dosen Pembimbing":
            //             $nilai_komponen['lain-lain'] = $nilaiKategori['rataPembimbing'] * $komponen_nilai_akhir->bobot;
            //             break;
            //         default:
            //             $nilai_komponen['lain-lain'] = 0;
            //     }
            // }

            Log::info("Nilai kategori: " . json_encode($nilaiKategori));
            // Log::info("Nilai komponen: " . json_encode($nilai_komponen));

            // Hitung nilai akhir

            // Konversi nilai akhir ke huruf

            // Masukkan ke array data
            
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