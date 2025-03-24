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
            ->orderBy('nama_fta')
            ->distinct()
            ->pluck('nama_fta');

        return view('KelolaPenilaianTA.views.pengelolaan-nilai.kelola_penilaian_ta', [
            'kategoriPenilaian' => $kategoriPenilaian,
        ]);
    }

    public function detailNilaiMahasiswa($namaFta): View
    {
        $namaFtaSlug = Str::slug($namaFta, ' '); // Convert to lowercase and replace hyphens with spaces
        $detailInformasiFta = FormPenilaian::where('nama_fta', $namaFtaSlug)
            ->select('kode_fta', 'nama_fta', 'id_prodi', 'id_fta') // Hanya mengambil kolom yang diperlukan
            ->orderBy('nama_fta')
            ->distinct()
            ->get();
    
        
        // Log::info('Detail FTA: ' . JSON_ENCODE($detailInformasiFta, JSON_PRETTY_PRINT));
        $idFtaList = $detailInformasiFta->pluck('id_fta')->toArray();

        $detailNilaiMahasiswa = Mahasiswa::with([
            'nilaiKategori' => function ($query) use ($idFtaList) {
                $query->whereHas('kategoriPenilaian', function ($q) use ($idFtaList) {
                    $q->whereIn('id_fta', $idFtaList);
                });
            },
            'nilaiKategori.kategoriPenilaian',
            'nilaiKategori.dosen',
            'user',
            'kota',
        ])->get();

        Log::info('Detail informasi nilai: ' . JSON_ENCODE($detailNilaiMahasiswa, JSON_PRETTY_PRINT));
        // Log::info('Informasi FTA: ' . JSON_ENCODE($detailInformasiFta, JSON_PRETTY_PRINT));

        return view('KelolaPenilaianTA.views.pengelolaan-nilai.detail_nilai_mahasiswa', compact('detailNilaiMahasiswa', 'detailInformasiFta', 'namaFta'));
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