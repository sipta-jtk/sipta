<?php

namespace App\Modules\KelolaPenilaianTA\Controllers;

use App\Modules\Controller;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Exports\RekapitulasiNilaiExport;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\FromCollection;
use App\Models\Mahasiswa;
use App\Models\KategoriPenilaian;
use App\Models\KomponenNilaiAkhir;
use App\Models\SumberNilai;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RekapitulasiNilaiController extends Controller{

    /**
     * Menampilkan halaman rekapitulasi nilai
     * 
     */
    public function getRekapNilaiSidang(): View
    {
        $mahasiswaList = Mahasiswa::select(
                'mahasiswa.nim',
                'user.nama as nama',
                'mahasiswa.kelas',
                'prodi.nama_prodi as prodi',
                'kota.nama_kota as kelompok'
            )
            ->leftJoin('user', 'mahasiswa.nim', '=', 'user.username')
            ->leftJoin('prodi', 'mahasiswa.id_prodi', '=', 'prodi.id_prodi')
            ->leftJoin('kota', 'mahasiswa.id_kota', '=', 'kota.id_kota')
            ->with(['nilaiKategori.kategoriPenilaian', 'nilaiKategori.dosen']) // Eager load
            ->get();    
    
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
    
            // Masukkan ke array data
            $data[] = array_merge([
                'nim' => $mahasiswa->nim,
                'nama' => $mahasiswa->nama,
                'prodi' => $mahasiswa->prodi,
                'kelas' => $mahasiswa->kelas,
                'kelompok' => $mahasiswa->kelompok
            ], $nilaiKategori);
        }
    
        return view('KelolaPenilaianTA.views.rekapitulasi-nilai.rekapitulasi_nilai_sidang', compact('data'));
    }
    
    /**
     * Fungsi untuk menghitung rata-rata dengan mengabaikan nilai null
     */
    private function calculateAverage($values)
    {
        $filteredValues = array_filter($values, function ($value) {
            return !is_null($value);
        });
    
        if (count($filteredValues) > 0) {
            return array_sum($filteredValues) / count($filteredValues);
        }
    
        return null; // Jika tidak ada nilai, kembalikan null
    }
        
        /**
     * Menampilkan halaman pengaturan nilai akhir
     */
    public function getPengaturanNilaiAkhir()
    {

        $data = KomponenNilaiAkhir::select(
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

        return view('KelolaPenilaianTA.views.rekapitulasi-nilai.pengaturan_bobot_nilai', compact('data'));
    }

    /**
     * Update pengaturan nilai akhir
     */
    public function updatePengaturanNilaiAkhir(Request $request)
    {
        $validatedData = $request->validate([
            'bobot' => 'required|array',
            'bobot.*' => 'numeric|min:0|max:100',
            'sumber_nilai' => 'required|array',
            'sumber_nilai.*' => 'string'
        ]);

        try {

            DB::beginTransaction();

            // Update bobot nilai
            foreach ($validatedData['bobot'] as $key => $bobot) {
                $komponen = KomponenNilaiAkhir::where('nama_komponen', $key)->first();
                if (!$komponen) {
                    Log::error("Komponen '$key' tidak ditemukan di database.");
                    throw new \Exception("Komponen '$key' tidak ditemukan.");
                }
                $komponen->bobot_komponen = $bobot;
                $komponen->save();
            }

            // Update sumber nilai
            foreach ($validatedData['sumber_nilai'] as $key => $sumber_nilai) {
                // Dapatkan id kategori penilaian dari nama kategori
                $kategori = KategoriPenilaian::where('nama_kategori', $sumber_nilai)->first();
                // Dapatkan id komponen dari nama komponen
                $komponen = KomponenNilaiAkhir::where('nama_komponen', $key)->first();

                // Update kolom id_komponen dengan komponen->id_komponen dan sumber dengan kategori->id_kategori di tabel sumber nilai
                $sumberNilai = SumberNilai::where('id_komponen', $komponen->id_komponen)->first();
                $sumberNilai->sumber = $kategori->id_kategori;
                $sumberNilai->save();
            }

            DB::commit();
            return redirect()->back()->with('success', 'Pengaturan nilai akhir berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
    
    /**
     * Menampilkan halaman rekapitulasi nilai akhir
     * 
     */
    public function getRekapNilaiAkhir(): View
    {
        $mahasiswaList = Mahasiswa::select(
            'mahasiswa.nim',
            'user.nama as nama',
            'mahasiswa.kelas',
            'prodi.nama_prodi as prodi',
            'kota.nama_kota as kelompok'
        )
        ->leftJoin('user', 'mahasiswa.nim', '=', 'user.username')
        ->leftJoin('prodi', 'mahasiswa.id_prodi', '=', 'prodi.id_prodi')
        ->leftJoin('kota', 'mahasiswa.id_kota', '=', 'kota.id_kota')
        ->with(['nilaiKategori.kategoriPenilaian', 'nilaiKategori.dosen']) // Eager load
        ->get();    

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
            ->get();

            // Hitung nilai uts
            foreach ($komponen_nilai_akhir as $komponen) {
                if ($komponen->nama_komponen == "uts") {
                    switch ($komponen->nama_kategori) {
                        case "Seminar 2":
                            $nilai_asli['uts'] = $nilaiKategori['rataSeminar2'];
                            $nilai_komponen['uts'] = $nilaiKategori['rataSeminar2'] * $komponen->bobot / 100;
                            break;
                        case "Seminar 3":
                            $nilai_asli['uts'] = $nilaiKategori['rataSeminar3'];
                            $nilai_komponen['uts'] = $nilaiKategori['rataSeminar3'] * $komponen->bobot / 100;
                            break;
                        case "Sidang Akhir":
                            $nilai_asli['uts'] = $nilaiKategori['rataSidang'];
                            $nilai_komponen['uts'] = $nilaiKategori['rataSidang'] * $komponen->bobot / 100;
                            break;
                        case "Dosen Pembimbing":
                            $nilai_asli['uts'] = $nilaiKategori['rataPembimbing'];
                            $nilai_komponen['uts'] = $nilaiKategori['rataPembimbing'] * $komponen->bobot / 100;
                            break;
                        default:
                            $nilai_asli['uts'] = 0;
                            $nilai_komponen['uts'] = 0;
                            break;
                    }
                } elseif ($komponen->nama_komponen == "uas") {
                    switch ($komponen->nama_kategori) {
                        case "Seminar 2":
                            $nilai_asli['uas'] = $nilaiKategori['rataSeminar2'];
                            $nilai_komponen['uas'] = $nilaiKategori['rataSeminar2'] * $komponen->bobot / 100;
                            break;
                        case "Seminar 3":
                            $nilai_asli['uas'] = $nilaiKategori['rataSeminar3'];
                            $nilai_komponen['uas'] = $nilaiKategori['rataSeminar3'] * $komponen->bobot / 100;
                            break;
                        case "Sidang Akhir":
                            $nilai_asli['uas'] = $nilaiKategori['rataSidang'];
                            $nilai_komponen['uas'] = $nilaiKategori['rataSidang'] * $komponen->bobot / 100;
                            break;
                        case "Dosen Pembimbing":
                            $nilai_asli['uas'] = $nilaiKategori['rataPembimbing'];
                            $nilai_komponen['uas'] = $nilaiKategori['rataPembimbing'] * $komponen->bobot / 100;
                            break;
                        default:
                            $nilai_asli['uas'] = 0;
                            $nilai_komponen['uas'] = 0;
                            break;
                    }
                } else {
                    switch ($komponen->nama_kategori) {
                        case "Seminar 2":
                            $nilai_asli['lain-lain'] = $nilaiKategori['rataSeminar2'];
                            $nilai_komponen['lain-lain'] = $nilaiKategori['rataSeminar2'] * $komponen->bobot / 100;
                            break;
                        case "Seminar 3":
                            $nilai_asli['lain-lain'] = $nilaiKategori['rataSeminar3'];
                            $nilai_komponen['lain-lain'] = $nilaiKategori['rataSeminar3'] * $komponen->bobot / 100;
                            break;
                        case "Sidang Akhir":
                            $nilai_asli['lain-lain'] = $nilaiKategori['rataSidang'];
                            $nilai_komponen['lain-lain'] = $nilaiKategori['rataSidang'] * $komponen->bobot / 100;
                            break;
                        case "Dosen Pembimbing":
                            $nilai_asli['lain-lain'] = $nilaiKategori['rataPembimbing'];
                            $nilai_komponen['lain-lain'] = $nilaiKategori['rataPembimbing'] * $komponen->bobot / 100;
                            break;
                        default:
                            $nilai_asli['lain-lain'] = 0;
                            $nilai_komponen['lain-lain'] = 0;
                            break;
                    }
                }
            }
            

            // Hitung nilai akhir
            $nilai_akhir = $nilai_komponen['uts'] + $nilai_komponen['uas'] + $nilai_komponen['lain-lain'];

            // Konversi nilai akhir ke huruf
            $nilai_akhir_huruf = $this->konversiNilaiHuruf($nilai_akhir);
            

            // Masukkan ke array data
            $data[] = [
                'nim' => $mahasiswa->nim,
                'nama' => $mahasiswa->nama,
                'prodi' => $mahasiswa->prodi,
                'kelas' => $mahasiswa->kelas,
                'kelompok' => $mahasiswa->kelompok,
                'nilaiUts' => number_format($nilai_asli['uts'], 2), // Format angka agar lebih rapi
                'nilaiUas' => number_format($nilai_asli['uas'], 2),
                'nilaiLainLain' => number_format($nilai_asli['lain-lain'], 2),
                'nilaiAkhir' => number_format($nilai_akhir, 2),
                'predikat' => $nilai_akhir_huruf, // Nilai huruf hasil konversi
            ];
            
            
        }
    
        return view('KelolaPenilaianTA.views.rekapitulasi-nilai.rekapitulasi_nilai_akhir', compact('data'));
    }

    private function konversiNilaiHuruf($nilai)
    {
        if ($nilai >= 85) {
            return 'A';
        } elseif ($nilai >= 80) {
            return 'A-';
        } elseif ($nilai >= 75) {
            return 'B+';
        } elseif ($nilai >= 70) {
            return 'B';
        } elseif ($nilai >= 65) {
            return 'B-';
        } elseif ($nilai >= 60) {
            return 'C+';
        } elseif ($nilai >= 55) {
            return 'C';
        } elseif ($nilai >= 50) {
            return 'C-';
        } elseif ($nilai >= 40) {
            return 'D';
        } else {
            return 'E';
        }
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
    
        $mahasiswaList = Mahasiswa::select(
            'mahasiswa.nim',
            'user.nama as nama',
            'mahasiswa.kelas',
            'prodi.nama_prodi as prodi',
            'kota.nama_kota as kelompok'
        )
        ->leftJoin('user', 'mahasiswa.nim', '=', 'user.username')
        ->leftJoin('prodi', 'mahasiswa.id_prodi', '=', 'prodi.id_prodi')
        ->leftJoin('kota', 'mahasiswa.id_kota', '=', 'kota.id_kota')
        ->with(['nilaiKategori.kategoriPenilaian', 'nilaiKategori.dosen']) // Eager load
        ->get();    

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

            // Masukkan ke array data
            $data[] = array_merge([
                'nim' => $mahasiswa->nim,
                'nama' => $mahasiswa->nama,
                'prodi' => $mahasiswa->prodi,
                'kelas' => $mahasiswa->kelas,
                'kelompok' => $mahasiswa->kelompok
            ], $nilaiKategori);
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