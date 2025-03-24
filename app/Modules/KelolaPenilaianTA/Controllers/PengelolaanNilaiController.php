<?php

namespace App\Modules\KelolaPenilaianTA\Controllers;

use App\Modules\Controller;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Exports\RekapitulasiNilaiExport;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Support\Facades\Log;
use App\Models\Mahasiswa;
use App\Models\kategoriPenilaian;
use App\Models\FormPenilaian;

class PengelolaanNilaiController extends Controller{
    /**
     * Menampilkan halaman kelola penilaian
     * 
     */
    public function kelolaNilai(): View
    {
        $kategoriPenilaian = FormPenilaian::whereIn('nama_fta', ['Seminar I', 'Seminar II', 'Seminar III', 'Sidang Akhir'])
            ->where('jenis_form', 'penilaian') // Menambahkan kondisi where untuk jenis_form
            ->orderBy('nama_fta')
            ->get();

        $kategoriFeedback = FormPenilaian::whereIn('nama_fta', ['Seminar I'])
            ->unique('nama_fta')
        // Log::info('Data kategori: ' . json_encode($kategori, JSON_PRETTY_PRINT));
        // $kategori = FormPenilaian::whereIn('id_fta', [1, 2, 4, 6])
        //     ->orderBy('id_fta', )
        //     ->get();
            
        return view('KelolaPenilaianTA.views.pengelolaan-nilai.kelola_penilaian_ta', compact('kategori'));
    }

    /**
     * Menampilkan halaman detail nilai mahasiswa
     * 
     */
    public function detailNilaiMahasiswa($idFta): View
    {
        $formPenilaian = FormPenilaian::where('id_fta', $idFta)
            ->with('kategoriPenilaian')
            ->orderBy('id_fta')
            ->first();
        
        $namaKategori = $formPenilaian->nama_fta;
        $idKategori = $formPenilaian->kategoriPenilaian[0]->id_kategori;

        $data = Mahasiswa::with(['nilaiKategori' => function ($query) use ($idKategori) {
            $query->where('id_kategori', $idKategori);
        }, 'nilaiKategori.dosen', 'user', 'kota'])->get();

        $filteredData = $this->mappingViewDetailNilaiMahasiswa($data);

        return view('KelolaPenilaianTA.views.pengelolaan-nilai.detail_nilai_mahasiswa', compact('filteredData', 'namaKategori', 'idFta'));
    }

    /**
     * Helper function untuk mapping data mahasiswa ke view detail nilai mahasiswa
     * 
     */
    private function mappingViewDetailNilaiMahasiswa($data): array
{
    $startTime = microtime(true);

    $filteredData = [];

    foreach ($data as $index => $mahasiswa) {
        $nilaiArray = [0, 0, 0];
        $kodeDosenArray = ['-', '-', '-'];

        foreach ($mahasiswa->nilaiKategori as $index => $nilaiKategori) {
            $nilaiArray[$index] = $nilaiKategori->nilai;
            $kodeDosenArray[$index] = $nilaiKategori->dosen->id_dosen;
        }

        $nilaiArray = array_map(fn($nilai) => round($nilai, 2), $nilaiArray);

        $filtered = array_filter($nilaiArray, fn($nilai) => $nilai > 0);
        $rataRata = count($filtered) > 0 ? round(array_sum($filtered) / count($filtered), 2) : 0.00;
        $filteredData[] = [
            'index' => $index + 1,
            'id_kota' => $mahasiswa->kota->id_kota ?? '-',
            'nama' => $mahasiswa->user->nama ?? '-',
            'kelompok' => $mahasiswa->kota->nama_kota ?? '-',
            'nilai' => $nilaiArray,
            'kode_dosen' => $kodeDosenArray,
            'rata-rata' => $rataRata,
        ];
    }

    $endTime = microtime(true);
    $executionTime = $endTime - $startTime;
    Log::info('Execution time of mappingViewDetailNilaiMahasiswa: ' . $executionTime . ' seconds');

    return $filteredData;
}

    /**
     * Menampilkan halaman tambah nilai mahasiswa
     * 
     */
    public function formPenilaianSeminar2(): View
    {
        // Data statis untuk kota yang akan nilai
        $kota = 2;

        $mahasiswa = Mahasiswa::where('id_kota', $kota)->get();

        return view ('KelolaPenilaianTA.views.pengelolaan-nilai.dummy_formulir_seminar2', compact('mahasiswa'));
    }

    /**
     * Menyimpan nilai mahasiswa
     * 
     */
    public function simpanNilaiMahasiswa(Request $request): View
    {   
        // Data statis untuk id penyimpanan nilai
        $kota = 2;
        $nip = 198502102015042001;
        
        $nilai = $request->except('_token');
        $nilai_mahasiswa = [];
        $mahasiswa = Mahasiswa::where('id_kota', $kota)->get();
        
        // Menghitung rata-rata nilai untuk setiap mahasiswa
        foreach ($nilai as $index => $values) {
            $average = $this->hitungRataRataNilai($values);
            $nilai_mahasiswa[] = $average;
        }

        // Log::info('Nilai mahasiswa: ' . JSON_ENCODE($nilai_mahasiswa, JSON_PRETTY_PRINT));

        $this->inputNilaiKeDatabase($mahasiswa, $nilai_mahasiswa, $nip);
    
        return view ('KelolaPenilaianTA.views.pengelolaan-nilai.dummy_formulir_seminar2');
    }

    /**
     * Helper function untuk menghitung rata-rata nilai
     */
    public static function hitungRataRataNilai(array $nilai): float
    {
        return count($nilai) > 0 ? array_sum($nilai) / count($nilai) : 0;
    }

    /**
     * Helper function untuk input nilai ke database
     */
    private function inputNilaiKeDatabase($mahasiswa, $nilai_mahasiswa, $nip): void
    {
        foreach ($mahasiswa as $index => $mhs) {
            $mhs->nilaiKategori()->create([
                'nim' => $mhs->nim,
                'nip' => $nip,
                'id_kategori' => 1,
                'nilai' => $nilai_mahasiswa[$index],
            ]);
        }
    }
}