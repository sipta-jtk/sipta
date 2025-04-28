<?php

namespace App\Modules\KelolaPenilaianTA\Controllers;

use App\Modules\Controller;
use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Exports\RekapitulasiNilaiExport;
use App\Exports\RekapitulasiNilaiAkhirExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Mahasiswa;
use App\Models\KategoriPenilaian;
use App\Models\KomponenNilaiAkhir;
use App\Models\SumberNilai;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Collection;

class RekapitulasiNilaiController extends Controller{

    /**
     * Menampilkan halaman rekapitulasi nilai
     */
    public function getRekapNilaiSidang(): View
    {
        $data = $this->generateRekapitulasiNilai();
        return view('KelolaPenilaianTA.views.rekapitulasi-nilai.rekapitulasi_nilai_sidang', compact('data'));
    }

    /**
     * Export data rekapitulasi nilai ke dalam file excel
     */
    public function exportExcelNilaiSidang(Request $request)
    {
        $data = $this->generateRekapitulasiNilai($request);
        return Excel::download(new RekapitulasiNilaiExport($data), 'rekapitulasi_nilai_seminar_sidang.xlsx');
    }

    /**
     * Menghasilkan data rekapitulasi nilai
     */
    private function generateRekapitulasiNilai(Request $request = null)
    {
        $query = Mahasiswa::select('mahasiswa.nim', 'user.nama as nama', 'mahasiswa.kelas', 'prodi.nama_prodi as prodi', 'kota.nama_kota as kelompok')
            ->leftJoin('user', 'mahasiswa.nim', '=', 'user.username')
            ->leftJoin('prodi', 'mahasiswa.id_prodi', '=', 'prodi.id_prodi')
            ->leftJoin('kota', 'mahasiswa.id_kota', '=', 'kota.id_kota')
            ->where('mahasiswa.status_ta', 'mahasiswa_ta')
            ->whereNotNull('mahasiswa.id_kota')
            ->with(['nilaiKategori.kategoriPenilaian', 'nilaiKategori.dosen']);

        $daftarMahasiswa = $query->get();
        
        $data = $daftarMahasiswa->map(fn($mahasiswa) => $this->prosesMahasiswa($mahasiswa))->toArray();

        if ($request) {
            $data = collect($data)
                ->when($request->query('prodi'), fn($q) => $q->where('prodi', $request->query('prodi')))
                ->when($request->query('kelas'), fn($q) => $q->where('kelas', $request->query('kelas')))
                ->values()
                ->toArray();
        }

        return $data;
    }

    /**
     * Memproses data mahasiswa
     */
    private function prosesMahasiswa($mahasiswa)
    {
        $nilaiKategori = $this->aturNilaiKategori($mahasiswa);
        
        return array_merge([
            'nim' => $mahasiswa->nim,
            'nama' => $mahasiswa->nama,
            'prodi' => $mahasiswa->prodi,
            'kelas' => $mahasiswa->kelas,
            'kelompok' => $mahasiswa->kelompok
        ], $nilaiKategori);
    }

    /**
     * Mengatur nilai kategori berdasarkan penguji dan pembimbing
     */
    private function aturNilaiKategori($mahasiswa)
    {
        $kategori = [
            'seminar2Penguji1', 'seminar2Penguji2', 'seminar2Penguji3',
            'seminar3Penguji1', 'seminar3Penguji2', 'seminar3Penguji3',
            'sidangPenguji1', 'sidangPenguji2', 'sidangPenguji3',
            'pembimbing1', 'pembimbing2'
        ];
        
        $nilaiKategori = array_fill_keys($kategori, null);

        $mappingKategori = [
            2 => ['seminar2Penguji1', 'seminar2Penguji2', 'seminar2Penguji3'],
            3 => ['seminar3Penguji1', 'seminar3Penguji2', 'seminar3Penguji3'],
            4 => ['sidangPenguji1', 'sidangPenguji2', 'sidangPenguji3'],
            5 => ['pembimbing1', 'pembimbing2']
        ];

        foreach ($mahasiswa->nilaiKategori as $nilai) {
            if (!$nilai->kategoriPenilaian || !$nilai->dosen) continue;

            $id = $nilai->kategoriPenilaian->id_kategori;
            $value = number_format($nilai->nilai, 2);

            if (isset($mappingKategori[$id])) {
                $this->assignNilai($nilaiKategori, $mappingKategori[$id], $value);
            }
        }

        foreach (['seminar2', 'seminar3', 'sidang', 'pembimbing'] as $kategori) {
            $keys = array_filter(array_keys($nilaiKategori), fn($k) => str_starts_with($k, $kategori));
            $nilaiKategori["rata" . ucfirst($kategori)] = number_format($this->hitungRataRata(array_intersect_key($nilaiKategori, array_flip($keys))), 2);
        }
        
        return $nilaiKategori;
    }


    /**
     * Fungsi untuk menghitung rata-rata dengan mengabaikan nilai null
     */
    private function hitungRataRata($nilai)
    {
        $nilaiTersaring = array_filter($nilai, fn($item) => !is_null($item));
        return count($nilaiTersaring) ? array_sum($nilaiTersaring) / count($nilaiTersaring) : null;
    }

    /**
     * Menetapkan nilai ke array jika slot masih kosong
     */
    private function assignNilai(&$array, $keys, $value)
    {
        foreach ($keys as $key) {
            if (is_null($array[$key])) {
                $array[$key] = $value;
                break;
            }
        }
    }

    /**
     * Menampilkan halaman rekapitulasi nilai akhir
     */
    public function getRekapNilaiAkhir(): View
    {
        $data = $this->generateRekapitulasiNilaiAkhir();
        return view('KelolaPenilaianTA.views.rekapitulasi-nilai.rekapitulasi_nilai_akhir', compact('data'));
    }

    /**
     * Export data rekapitulasi nilai akhir ke dalam file excel
     */
    public function exportExcelNilaiAkhir(Request $request)
    {
        $data = $this->generateRekapitulasiNilaiAkhir($request);
        return Excel::download(new RekapitulasiNilaiAkhirExport($data), 'rekapitulasi_nilai_akhir.xlsx');
    }

    /**
     * Menghasilkan data rekapitulasi nilai akhir
     */
    private function generateRekapitulasiNilaiAkhir(Request $request = null)
    {
        $query = Mahasiswa::select('mahasiswa.nim', 'user.nama as nama', 'mahasiswa.kelas', 'prodi.nama_prodi as prodi', 'kota.nama_kota as kelompok')
            ->leftJoin('user', 'mahasiswa.nim', '=', 'user.username')
            ->leftJoin('prodi', 'mahasiswa.id_prodi', '=', 'prodi.id_prodi')
            ->leftJoin('kota', 'mahasiswa.id_kota', '=', 'kota.id_kota')
            ->where('mahasiswa.status_ta', 'mahasiswa_ta')
            ->whereNotNull('mahasiswa.id_kota')
            ->with(['nilaiKategori.kategoriPenilaian', 'nilaiKategori.dosen']);

        $daftarMahasiswa = $query->get();
        
        $data = $daftarMahasiswa->map(fn($mahasiswa) => $this->prosesMahasiswaAkhir($mahasiswa))->toArray();

        if ($request) {
            $data = collect($data)
                ->when($request->query('prodi'), fn($q) => $q->where('prodi', $request->query('prodi')))
                ->when($request->query('kelas'), fn($q) => $q->where('kelas', $request->query('kelas')))
                ->values()
                ->toArray();
        }

        return $data;
    }

    /**
     * Memproses data mahasiswa untuk nilai akhir
     */
    private function prosesMahasiswaAkhir($mahasiswa)
    {
        $nilaiKategori = $this->aturNilaiKategori($mahasiswa);
        
        $nilai_asli = [
            'uts' => $nilaiKategori['rataSeminar2'] ?? 0,
            'uas' => $nilaiKategori['rataSeminar3'] ?? 0,
            'lain-lain' => $nilaiKategori['rataSidang'] ?? 0,
        ];

        // Dapatkan sumber nilai dan bobot uts, uas, dan lain-lain dari kategori penilaian apa
        $komponen_nilai_akhir = KomponenNilaiAkhir::select(
            'komponen_nilai_akhir.id_komponen',
            'komponen_nilai_akhir.bobot_komponen as bobot',
            'sumber_nilai.sumber',
            'kategori_penilaian.id_kategori',
        )
        ->leftJoin('sumber_nilai', 'komponen_nilai_akhir.id_komponen', '=', 'sumber_nilai.id_komponen')
        ->leftJoin('kategori_penilaian', 'kategori_penilaian.id_kategori', '=', 'sumber_nilai.sumber')
        ->get();

        $nilai_komponen = $this->hitungNilaiKomponen($komponen_nilai_akhir, $nilaiKategori);

        // Hitung nilai akhir
        $nilai_akhir = $nilai_komponen['uts'] + $nilai_komponen['uas'] + $nilai_komponen['lain-lain'];
        $nilai_akhir_huruf = $this->konversiNilaiHuruf($nilai_akhir);
        
        return array_merge([
            'nim' => $mahasiswa->nim,
            'nama' => $mahasiswa->nama,
            'prodi' => $mahasiswa->prodi,
            'kelas' => $mahasiswa->kelas,
            'kelompok' => $mahasiswa->kelompok,
            'nilaiUts' => number_format($nilai_asli['uts'], 2),
            'nilaiUas' => number_format($nilai_asli['uas'], 2),
            'nilaiLainLain' => number_format($nilai_asli['lain-lain'], 2),
            'nilaiAkhir' => number_format($nilai_akhir, 2),
            'predikat' => $nilai_akhir_huruf,
        ], $nilaiKategori);
    }

    /**
     * Fungsi untuk menghitung nilai komponen berdasarkan kategori
     */
    private function hitungNilaiKomponen($komponen_nilai_akhir, $nilaiKategori)
    {
        $nilai_komponen = [
            'uts' => 0,
            'uas' => 0,
            'lain-lain' => 0,
        ];

        foreach ($komponen_nilai_akhir as $komponen) {
            $nilai = $this->getNilaiPerKategori($komponen, $nilaiKategori);

            if ($komponen->id_komponen == 1) {
                $nilai_komponen['uts'] = $nilai['nilai'] * $komponen->bobot / 100;
            } elseif ($komponen->id_komponen == 2) {
                $nilai_komponen['uas'] = $nilai['nilai'] * $komponen->bobot / 100;
            } else {
                $nilai_komponen['lain-lain'] = $nilai['nilai'] * $komponen->bobot / 100;
            }
        }

        return $nilai_komponen;
    }

    /**
     * Fungsi untuk mendapatkan nilai berdasarkan kategori komponen
     */
    private function getNilaiPerKategori($komponen, $nilaiKategori)
    {
        $nilai = 0;

        switch ($komponen->id_kategori) {
            case 2:
                $nilai = $nilaiKategori['rataSeminar2'] ?? 0;
                break;
            case 3:
                $nilai = $nilaiKategori['rataSeminar3'] ?? 0;
                break;
            case 4:
                $nilai = $nilaiKategori['rataSidang'] ?? 0;
                break;
            case 5:
                $nilai = $nilaiKategori['rataPembimbing'] ?? 0;
                break;
            default:
                $nilai = 0;
                break;
        }

        return ['nilai' => $nilai];
    }


    /**
     * Menampilkan halaman pengaturan nilai akhir
     */
    public function getPengaturanNilaiAkhir()
    {
        $data = KomponenNilaiAkhir::select(
                'komponen_nilai_akhir.nama_komponen',
                'komponen_nilai_akhir.bobot_komponen as bobot',
                'kategori_penilaian.id_kategori'
            )
            ->leftJoin('sumber_nilai', 'komponen_nilai_akhir.id_komponen', '=', 'sumber_nilai.id_komponen')
            ->leftJoin('kategori_penilaian', 'kategori_penilaian.id_kategori', '=', 'sumber_nilai.sumber')
            ->get()
            ->map(fn($item) => [
                'komponen' => $item->nama_komponen,
                'bobot' => $item->bobot,
                'sumber_nilai' => $item->id_kategori
            ]);

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
            $this->updateBobotNilai($validatedData['bobot']);
            
            // Update sumber nilai
            $this->updateSumberNilai($validatedData['sumber_nilai']);

            DB::commit();
            return redirect()->back()->with('success', 'Pengaturan nilai akhir berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Update bobot nilai
     */
    private function updateBobotNilai(array $bobotData)
    {
        foreach ($bobotData as $komponenName => $bobot) {
            $komponen = KomponenNilaiAkhir::where('nama_komponen', $komponenName)->first();
            if (!$komponen) {
                Log::error("Komponen '$komponenName' tidak ditemukan di database.");
                throw new \Exception("Komponen '$komponenName' tidak ditemukan.");
            }
            $komponen->bobot_komponen = $bobot;
            $komponen->save();
        }
    }

    /**
     * Update sumber nilai
     */
    private function updateSumberNilai(array $sumberNilaiData)
    {
        foreach ($sumberNilaiData as $komponenName => $kategoriId) {
            $kategori = KategoriPenilaian::where('id_kategori', $kategoriId)->first();
            $komponen = KomponenNilaiAkhir::where('nama_komponen', $komponenName)->first();

            $sumberNilai = SumberNilai::where('id_komponen', $komponen->id_komponen)->first();
            $sumberNilai->sumber = $kategori->id_kategori;

            $sumberNilai->save();
        }
    }



    private function konversiNilaiHuruf($nilai)
    {
        $gradeRanges = [
            ['min' => 80, 'max' => 100, 'grade' => 'A'],
            ['min' => 75, 'max' => 79.99, 'grade' => 'AB'],
            ['min' => 70, 'max' => 74.99, 'grade' => 'B'],
            ['min' => 65, 'max' => 69.99, 'grade' => 'BC'],
            ['min' => 60, 'max' => 64.99, 'grade' => 'C'],
            ['min' => 55, 'max' => 59.99, 'grade' => 'CD'],
            ['min' => 40, 'max' => 54.99, 'grade' => 'D'],
            ['min' => 0, 'max' => 39.99, 'grade' => 'E'],
        ];
    
        foreach ($gradeRanges as $range) {
            if ($nilai >= $range['min'] && $nilai <= $range['max']) {
                return $range['grade'];
            }
        }
    
        return 'T';  
    }
    
}