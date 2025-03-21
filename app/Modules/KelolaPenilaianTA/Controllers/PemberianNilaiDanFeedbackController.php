<?php

namespace App\Modules\KelolaPenilaianTA\Controllers;

use App\Modules\Controller;
use App\Models\Mahasiswa;
use App\Models\Kota;
use App\Models\KriteriaPenilaian;
use App\Models\KategoriPenilaian;
use App\Models\FormPenilaian;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Exports\RekapitulasiNilaiExport;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Support\Facades\Log;

class PemberianNilaiDanFeedbackController extends Controller
{  
    /**
     * Akses halaman pemberian masukan
     */
    // public function simpanMasukanSeminar($id, $kota): View
    // {
    //     switch ($id) {
    //         case 1:
    //             return $this->pengisianMasukanSeminar1($id, $kota);
    //         case 3:
    //             return $this->pengisianMasukanSeminarII($id, $kota);
    //         case 5:
    //             return $this->pengisianMasukanSeminarIII();
    //         default:
    //             return $this->pengisianMasukanSidangAkhir();
    //     }
    // }

    /**
     * Menampilkan halaman pemberian masukan seminar 1
     */
    private function pengisianMasukanSeminar1(): View
    {
        Log::info("Halo");
        // ID kota statis
        $idKota = 2;

        // Ambil hanya mahasiswa dengan id_kota = 2
        $mahasiswaList = Mahasiswa::with('user')->where('id_kota', $idKota)->get();

        // Ambil informasi KoTA berdasarkan id_kota = 2
        $kotaInfo = Kota::where('id_kota', $idKota)->first();

        // Data Mahasiswa
        $mahasiswa = [
            'kode_fta' => 'FTA-04',
            'tanggal' => '7 Maret 2025',
            'waktu' => '10:00 - 11:00'
            // 'id_kota' => 'KoTA-313',
            // 'topik_ta' => 'Analisis Perbandingan Performa Model x dan y dalam Memprediksi Skor Esai pada Automated Essay Scoring'
        ];

        return view('KelolaPenilaianTA.views.pemberian-nilai-dan-feedback.pengisian_masukan_seminar_1', compact('mahasiswa', 'mahasiswaList', 'kotaInfo'));
    }

    /**
     * Menampilkan halaman pemberian masukan seminar 2
     */
    public function pengisianMasukanSeminar($id, $kota): View
    {
        Log::info("Halo");
        // Ambil informasi seminar
        $seminar = KategoriPenilaian::where('nama_kategori', 'Seminar ' . $id)
            ->with('formulirPenilaian')
            ->first();

        // Ambil daftar mahasiswa
        $mahasiswa = Mahasiswa::where('id_kota', $kota)
            ->with('user', 'kota.penjadwalan')
            ->get();

        // Ambil aspek feedback yang sesuai dengan seminar ini
        $aspekFeedback = AspekFeedback::where('id_fta', $id)->get();
        Log::info("Aspek feedback".$aspekFeedback);

        // Mapping data mahasiswa untuk form pengisian masukan
        $data = $this->mappingDataMahasiswaMasukan($seminar, $mahasiswa);
        Log::info($data);

        return view('KelolaPenilaianTA.views.pemberian-nilai-dan-feedback.pengisian_masukan_seminar_II', 
                    compact('seminar', 'mahasiswa', 'id', 'aspekFeedback', 'kota', 'data'));
    }

    /**
     * Helper mapping untuk data mahasiswa di form pengisian masukan seminar 2
     */
    public function mappingDataMahasiswaMasukan($kategoriPenilaian, $mahasiswa)
    {
        // Log::info($mahasiswa);
        Log::info(json_encode($mahasiswa, JSON_PRETTY_PRINT));
        $data = [
            'kode_fta' => $kategoriPenilaian->formulirPenilaian->kode_fta,
            'tanggal' => $kategoriPenilaian->formulirPenilaian->tanggal_tenggat_pengisian,
            'start' => date('H:i', strtotime($mahasiswa->first()->kota->penjadwalan[0]->start)),
            'kota' => $mahasiswa->first()->kota->nama_kota
        ];


        return $data;
    }
    
    //     return $data;
    // }

    // private function pengisianMasukanSeminarII($id, $kota): View
    // {
    //     // Ambil informasi seminar
    //     $seminar = KategoriPenilaian::where('nama_kategori', 'Seminar ' . $id)
    //         ->with('formulirPenilaian')
    //         ->first();

    //     // Ambil aspek feedback yang sesuai dengan seminar ini
    //     $aspekFeedback = AspekFeedback::where('id_fta', $id)->get();

    //     return view('KelolaPenilaianTA.views.pemberian-nilai-dan-feedback.pengisian_masukan_seminar_II', 
    //                 compact('seminar', 'id', 'aspekFeedback', 'kota'));
    // }
    
    // private function pengisianMasukanSeminarII($id, $kota): View
    // {
    //     $seminar = KategoriPenilaian::where('nama_kategori', 'Seminar '. $id)->with('formulirPenilaian')->first();
    //     $mahasiswa = Mahasiswa::where('id_kota', $kota)->with('user', 'kota.penjadwalan')->get();
        
    //     // Ambil aspek feedback dari database
    //     $aspekFeedback = AspekFeedback::where('id_fta', $id)->get();
    
    //     $data = $this->mappingDataMahasiswa($seminar, $mahasiswa);
    
    //     return view('KelolaPenilaianTA.views.pemberian-nilai-dan-feedback.pengisian_masukan_seminar_II', 
    //                 compact('data', 'mahasiswa', 'id', 'aspekFeedback'));
    // }

    /**
     * Menampilkan halaman pemberian masukan seminar 3
     * 
     */
    private function pengisianMasukanSeminarIII(): View
    {
        // Ambil kode fta
        $kodeFTA = KategoriPenilaian::where('id_kategori', 3)->first()->kode_fta; // masih blm bisa

        // ID kota statis
        $idKota = 1;

        // Ambil hanya mahasiswa dengan id_kota = 1
        $mahasiswaList = Mahasiswa::with('user')->where('id_kota', $idKota)->get();

        // Ambil informasi KoTA berdasarkan id_kota = 1
        $kotaInfo = Kota::where('id_kota', $idKota)->first();

        // Data Mahasiswa
        $mahasiswa = [
            'kode_fta' => 'FTA-012',
            'tanggal' => '7 Maret 2025',
            'waktu' => '10:00 - 11:00'
            // 'id_kota' => 'KoTA-313',
            // 'topik_ta' => 'Analisis Perbandingan Performa Model x dan y dalam Memprediksi Skor Esai pada Automated Essay Scoring'
        ];

        return view('KelolaPenilaianTA.views.pemberian-nilai-dan-feedback.pengisian_masukan_seminar_III', compact('mahasiswa', 'mahasiswaList', 'kotaInfo'));
    }

    /**
     * Menampilkan halaman pemberian masukan sidang akhir
     * 
     */
    private function pengisianMasukanSidangAkhir(): View
    {
        // Ambil kode fta
        $kodeFTA = KategoriPenilaian::where('id_kategori', 3)->first()->kode_fta; // masih blm bisa

        // ID kota statis
        $idKota = 1;

        // Ambil hanya mahasiswa dengan id_kota = 1
        $mahasiswaList = Mahasiswa::with('user')->where('id_kota', $idKota)->get();

        // Ambil informasi KoTA berdasarkan id_kota = 1
        $kotaInfo = Kota::where('id_kota', $idKota)->first();

        // Data Mahasiswa
        $mahasiswa = [
            'kode_fta' => 'FTA-015',
            'tanggal' => '7 Maret 2025',
            'waktu' => '10:00 - 11:00'
        ];

        return view('KelolaPenilaianTA.views.pemberian-nilai-dan-feedback.pengisian_masukan_sidang_akhir', compact('mahasiswa', 'mahasiswaList', 'kotaInfo'));
    }

    /**
     * Simpan feedback berdasarkan seminar yang dipilih
     */
    public function simpanFeedback(Request $request, $seminar, $kota)
    {
        switch ($seminar) {
            case 1:
                return $this->simpanFeedbackSeminarI($request, $seminar, $kota);
            case 2:
                return $this->simpanFeedbackSeminarII($request, $seminar, $kota);
            case 3:
                return $this->simpanFeedbackSeminarIII($request, $kota);
            default:
                return $this->simpanFeedbackSidangAkhir($request, $kota);
        }
    }

    /**
     * Simpan feedback untuk Seminar 2
     */
    public function simpanMasukanSeminar(Request $request, $id, $kota)
    {
        $nip = auth()->user()->username; // Ambil NIP dosen yang login

        // Validasi data masukan
        $request->validate([
            'feedback' => 'required|array',
            'feedback.*.masukan' => 'required|string',
        ]);

        // Ambil semua masukan dari form
        $feedbacks = $request->input('feedback');

        // Simpan setiap masukan ke dalam database
        Log::info($feedbacks);
        foreach ($feedbacks as $feedback) {
            DetailFeedback::create([
                'id_feedback' => $id, // ID feedback dari URL
                'id_kota' => $kota, // Kota tujuan dari URL
                'nip' => $nip,
                'status_penilaian_dosen' => 'draf', // Status default
                'isi_feedback' => $request->input('feedback'), // Data feedback dari form
            ]);
        }

        // Redirect dengan notifikasi sukses
        // return redirect()->back()->with('success', 'Masukan berhasil disimpan.');
        // $pengelolaanNilai = new PengelolaanNilaiController();
        // return $pengelolaanNilai->detailNilaiMahasiswa($id);
        return redirect('sipta/kelola-penilaian-ta/nilai-seminar/'.$id);
    }

}