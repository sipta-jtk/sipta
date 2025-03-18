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

class PengelolaanNilaiController extends Controller{
    /**
     * Menampilkan halaman kelola penilaian
     * 
     */
    public function kelolaNilai(): View
    {
        $kategori = kategoriPenilaian::whereIn('kode_fta', [1, 2, 3, 4])->orderBy('nama_kategori')->get(); // 1, 2, 3, 4 adalah kode fta yang akan diambil masih statis

        return view('KelolaPenilaianTA.views.pengelolaan-nilai.kelola_penilaian_ta', compact('kategori'));
    }

    /**
     * Menampilkan halaman detail nilai mahasiswa
     * 
     */
    public function detailNilaiMahasiswa($id): View
    {
        $kategoriPenilaian = kategoriPenilaian::where('kode_fta', $id)->firstOrFail();
        $id_kategori = $kategoriPenilaian->id_kategori;
        $nama_kategori = $kategoriPenilaian->nama_kategori;

        $data = Mahasiswa::with(['nilaiKategori' => function ($query) use ($id_kategori) {
            $query->where('id_kategori', $id_kategori);
        }, 'nilaiKategori.dosen', 'user'])->get();


        $filteredData = $this->mappingViewDetailNilaiMahasiswa($data);

        return view('KelolaPenilaianTA.views.pengelolaan-nilai.detail_nilai_mahasiswa', compact('filteredData', 'nama_kategori', 'id'));
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

        $filtered = array_filter($nilaiArray, fn($nilai) => $nilai > 0);
        $filteredData[] = [
            'index' => $index + 1,
            'kota' => $mahasiswa->kota->id_kota,
            'nama' => $mahasiswa->user->nama,
            'kelompok' => $mahasiswa->id_kota,
            'nilai' => $nilaiArray,
            'kode_dosen' => $kodeDosenArray,
            'rata-rata' => count($filtered) > 0 ? array_sum($filtered) / count($filtered) : 0,
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