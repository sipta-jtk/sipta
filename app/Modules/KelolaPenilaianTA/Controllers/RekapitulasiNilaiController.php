<?php

namespace App\Modules\KelolaPenilaianTA\Controllers;

use App\Modules\Controller;
use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Exports\RekapitulasiNilaiExport;
use App\Exports\RekapitulasiNilaiAkhirExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\KategoriPenilaian;
use App\Models\KomponenNilaiAkhir;
use App\Models\SumberNilai;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Collection;

class RekapitulasiNilaiController extends Controller
{
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
        $query = DB::table('mahasiswa')
            ->select(
                'mahasiswa.nim',
                'user.nama as nama',
                'mahasiswa.kelas',
                'prodi.nama_prodi as prodi',
                'kota.nama_kota as kelompok',
                'nilai_kategori.id_kategori',
                'nilai_kategori.nip',
                'nilai_kategori.nilai',
                'nilai_kategori.status_penilaian_dosen',
                'kategori_penilaian.id_kategori as kategori_id',
                'kategori_penilaian.id_fta',
                'kategori_penilaian.kunci_penilaian'
            )
            ->leftJoin('user', 'mahasiswa.nim', '=', 'user.username')
            ->leftJoin('prodi', 'mahasiswa.id_prodi', '=', 'prodi.id_prodi')
            ->leftJoin('kota', 'mahasiswa.id_kota', '=', 'kota.id_kota')
            ->leftJoin('nilai_kategori', 'mahasiswa.nim', '=', 'nilai_kategori.nim')
            ->leftJoin('kategori_penilaian', 'nilai_kategori.id_kategori', '=', 'kategori_penilaian.id_kategori')
            ->where('mahasiswa.status_ta', 'mahasiswa_ta')
            ->whereNotNull('mahasiswa.id_kota');

        $daftarNilai = $query->get();

        // Kelompokkan per mahasiswa
        $grouped = $daftarNilai->groupBy('nim');

        // Proses setiap kelompok mahasiswa
        $data = $grouped->map(function ($items) {
            return $this->prosesMahasiswa($items);
        })->values()->toArray();

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
    private function prosesMahasiswa($records)
    {
        $first = $records->first();

        $nilaiKategori = $this->aturNilaiKategori($records);

        return array_merge([
            'nim' => $first->nim,
            'nama' => $first->nama,
            'prodi' => $first->prodi,
            'kelas' => $first->kelas,
            'kelompok' => $first->kelompok,
        ], $nilaiKategori);
    }


    /**
     * Mengatur nilai kategori berdasarkan penguji dan pembimbing
     */
private function aturNilaiKategori($records)
{
    $kategori = [
        'seminar1Penguji1',
        'seminar1Penguji2',
        'seminar1Penguji3',
        'seminar2Penguji1',
        'seminar2Penguji2',
        'seminar2Penguji3',
        'seminar3Penguji1',
        'seminar3Penguji2',
        'seminar3Penguji3',
        'sidangPenguji1',
        'sidangPenguji2',
        'sidangPenguji3',
        'pembimbing1',
        'pembimbing2'
    ];

    $nilaiKategori = array_fill_keys($kategori, null);

    // Mapping berdasarkan nama_fta
    $mappingKategori = [
        'Seminar I' => ['seminar1Penguji1', 'seminar1Penguji2', 'seminar1Penguji3'],
        'Seminar II' => ['seminar2Penguji1', 'seminar2Penguji2', 'seminar2Penguji3'],
        'Seminar III' => ['seminar3Penguji1', 'seminar3Penguji2', 'seminar3Penguji3'],
        'Sidang Akhir' => ['sidangPenguji1', 'sidangPenguji2', 'sidangPenguji3'],
        'Dosen Pembimbing' => ['pembimbing1', 'pembimbing2']
    ];

    foreach ($records as $record) {
        if (!$record->kategori_id || $record->status_penilaian_dosen !== 'dipublikasikan') continue;

        // Ambil nama_fta berdasarkan kategori_id
        $kategoriPenilaian = DB::table('kategori_penilaian')
            ->join('form_penilaian', 'kategori_penilaian.id_fta', '=', 'form_penilaian.id_fta')
            ->where('kategori_penilaian.id_kategori', $record->kategori_id)
            ->select('form_penilaian.nama_fta')
            ->first();

        if (!$kategoriPenilaian) continue;

        $namaFta = $kategoriPenilaian->nama_fta;
        $value = number_format($record->nilai, 2);

        // Cocokkan nama_fta dengan mappingKategori
        if (isset($mappingKategori[$namaFta])) {
            $this->assignNilai($nilaiKategori, $mappingKategori[$namaFta], $value);
        }
    }

    // Hitung rata-rata untuk setiap kategori utama
    foreach (['seminar1', 'seminar2', 'seminar3', 'sidang', 'pembimbing'] as $kategori) {
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
        $query = DB::table('mahasiswa')
            ->select(
                'mahasiswa.nim',
                'user.nama as nama',
                'mahasiswa.kelas',
                'prodi.nama_prodi as prodi',
                'kota.nama_kota as kelompok',
                'nilai_kategori.id_kategori',
                'nilai_kategori.nip',
                'nilai_kategori.nilai',
                'nilai_kategori.status_penilaian_dosen',
                'kategori_penilaian.id_kategori as kategori_id',
                'kategori_penilaian.id_fta',
                'kategori_penilaian.kunci_penilaian'
            )
            ->leftJoin('user', 'mahasiswa.nim', '=', 'user.username')
            ->leftJoin('prodi', 'mahasiswa.id_prodi', '=', 'prodi.id_prodi')
            ->leftJoin('kota', 'mahasiswa.id_kota', '=', 'kota.id_kota')
            ->leftJoin('nilai_kategori', 'mahasiswa.nim', '=', 'nilai_kategori.nim')
            ->leftJoin('kategori_penilaian', 'nilai_kategori.id_kategori', '=', 'kategori_penilaian.id_kategori')
            ->where('mahasiswa.status_ta', 'mahasiswa_ta')
            ->whereNotNull('mahasiswa.id_kota');

        $daftarNilai = $query->get();

        // Kelompokkan per mahasiswa
        $grouped = $daftarNilai->groupBy('nim');

        $data = $grouped->map(function ($items) {
            return $this->prosesMahasiswaAkhir($items);
        })->values()->toArray();

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
        $first = $mahasiswa->first();

        $nilaiKategori = $this->aturNilaiKategori($mahasiswa);

        // Dapatkan sumber nilai dan bobot uts, uas, dan lain-lain dari kategori penilaian apa
        $komponen_nilai_akhir = KomponenNilaiAkhir::select(
            'komponen_nilai_akhir.id_komponen',
            'komponen_nilai_akhir.bobot_komponen as bobot',
            'komponen_nilai_akhir.nama_komponen',
            'sumber_nilai.sumber',
            'kategori_penilaian.id_kategori',
        )
            ->leftJoin('sumber_nilai', 'komponen_nilai_akhir.id_komponen', '=', 'sumber_nilai.id_komponen')
            ->leftJoin('kategori_penilaian', 'kategori_penilaian.id_kategori', '=', 'sumber_nilai.sumber')
            ->get();

        $nilai_komponen = $this->hitungNilaiKomponen($komponen_nilai_akhir, $nilaiKategori);

        // Hitung nilai akhir
        $nilai_akhir = array_sum($nilai_komponen);
        $nilai_akhir_huruf = $this->konversiNilaiHuruf($nilai_akhir);

        return array_merge([
            'nim' => $first->nim,
            'nama' => $first->nama,
            'prodi' => $first->prodi,
            'kelas' => $first->kelas,
            'kelompok' => $first->kelompok,
            'nilaiUtsTeori' => number_format($nilai_komponen['utsTeori'], 2),
            'nilaiPraktikumETS' => number_format($nilai_komponen['praktikumETS'], 2),
            'nilaiLainLainETS' => number_format($nilai_komponen['lainLainETS'], 2),
            'nilaiUasTeori' => number_format($nilai_komponen['uasTeori'], 2),
            'nilaiPraktikumEAS' => number_format($nilai_komponen['praktikumEAS'], 2),
            'nilaiLainLainEAS' => number_format($nilai_komponen['lainLainEAS'], 2),
            'nilaiPjBL' => number_format($nilai_komponen['pjbl'], 2),
            'nilaiPartisipatif' => number_format($nilai_komponen['partisipatif'], 2),
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
            'utsTeori' => 0,
            'praktikumETS' => 0,
            'lainLainETS' => 0,
            'uasTeori' => 0,
            'praktikumEAS' => 0,
            'lainLainEAS' => 0,
            'pjbl' => 0,
            'partisipatif' => 0,
        ];

        foreach ($komponen_nilai_akhir as $komponen) {
            $nilai = $this->getNilaiPerKategori($komponen, $nilaiKategori);

            switch ($komponen->nama_komponen) {
                case 'UTS (Teori)':
                    $nilai_komponen['utsTeori'] = $nilai['nilai'] * $komponen->bobot / 100;
                    break;
                case 'Praktikum ETS':
                    $nilai_komponen['praktikumETS'] = $nilai['nilai'] * $komponen->bobot / 100;
                    break;
                case 'Lain - lain ETS':
                    $nilai_komponen['lainLainETS'] = $nilai['nilai'] * $komponen->bobot / 100;
                    break;
                case 'UAS (Teori)':
                    $nilai_komponen['uasTeori'] = $nilai['nilai'] * $komponen->bobot / 100;
                    break;
                case 'Praktikum EAS':
                    $nilai_komponen['praktikumEAS'] = $nilai['nilai'] * $komponen->bobot / 100;
                    break;
                case 'Lain - lain EAS':
                    $nilai_komponen['lainLainEAS'] = $nilai['nilai'] * $komponen->bobot / 100;
                    break;
                case 'PjBL':
                    $nilai_komponen['pjbl'] = $nilai['nilai'] * $komponen->bobot / 100;
                    break;
                case 'Partisipatif':
                    $nilai_komponen['partisipatif'] = $nilai['nilai'] * $komponen->bobot / 100;
                    break;
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
            case 1:
                $nilai = $nilaiKategori['rataSeminar1'] ?? 0;
                break;
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

    /**
     * Mengonversi nilai ke dalam huruf
     */
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
