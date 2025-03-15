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


        // angka 1 dibawah untuk menandakan kategori mana yang ingin diambil
        $data = Mahasiswa::with(['nilaiKategori' => function ($query) {
            $query->where('id_kategori', 1);
        }, 'nilaiKategori.dosen', 'user'])->get();

        $filteredData = $this->mappingViewDetailNilaiMahasiswa($data);

        // Log::info('Filtered data:' . JSON_ENCODE($filteredData, JSON_PRETTY_PRINT));

        return view('KelolaPenilaianTA.views.pengelolaan-nilai.detail_nilai_mahasiswa', compact('filteredData', 'kategori'));
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

        $filteredData[] = [
            'index' => $index + 1,
            'nama' => $mahasiswa->user->nama,
            'kelompok' => $mahasiswa->id_kota,
            'nilai' => $nilaiArray,
            'kode_dosen' => $kodeDosenArray,
            'rata-rata' => count($nilaiArray) > 0 ? array_sum($nilaiArray) / count($nilaiArray) : 0,
            'nim' => $mahasiswa->nim
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

        return view ('KelolaPenilaianTA.views.pengelolaan-nilai.dummy_formulir_seminar2'. compact('mahasiswa'));
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

        Log::info('Nilai mahasiswa: ' . JSON_ENCODE($nilai_mahasiswa, JSON_PRETTY_PRINT));

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