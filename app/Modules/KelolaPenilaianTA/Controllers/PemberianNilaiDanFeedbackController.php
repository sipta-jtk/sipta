<?php

namespace App\Modules\KelolaPenilaianTA\Controllers;

use App\Modules\Controller;
use App\Models\Mahasiswa;
use App\Models\Kota;
use App\Models\KriteriaPenilaian;
use App\Models\KategoriPenilaian;
use App\Models\FormPenilaian;
use App\Models\AspekFeedback;
use App\Models\DetailFeedback;
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

    public function pengisianNilaiSeminar($id_fta, $id_kota) 
    {
        return $this->pengisianNilaiSeminarII($id_fta, $id_kota);
    }

    /**
     * Menampilkan halaman pemberian nilai seminar 2
     * 
     */
    private function pengisianNilaiSeminarII($idFta, $idKota): View
    {
        $mahasiswa = Mahasiswa::where('id_kota', $idKota)->with('user', 'kota', 'nilaiKriteria')->get();
        
        $kategoriPenilaian = KategoriPenilaian::where('id_fta', $idFta)->with('formulirPenilaian')->first();

        $kriteriaPenilaian = KriteriaPenilaian::where('id_fta', $idFta)->with(['rubrik', 'nilaiKriteria' => function ($query) use ($idKota) {
            $query->whereHas('mahasiswa', function ($query) use ($idKota) {
                $query->where('id_kota', $idKota);
            });
        }])->get(); 
    
        $nilaiKriteria = [];

        foreach ($kriteriaPenilaian as $index => $kriteria) {
            foreach ($kriteria->nilaiKriteria as $nilai) {
                $nilaiKriteria[$index][] = $nilai->nilai_kriteria;
            }
        }

        $data = $this->mappingDataMahasiswa($kategoriPenilaian, $mahasiswa, $idKota);

        return view('KelolaPenilaianTA.views.pemberian-nilai-dan-feedback.pengisian_nilai_seminar_II', 
                    compact('data', 'mahasiswa', 'idFta', 'kriteriaPenilaian', 'nilaiKriteria'));
    }

    /**
     * Helper mapping untuk data mahasiswa di form pengisian nilai seminar 2
     */
    private function mappingDataMahasiswa($kategoriPenilaian, $mahasiswa, $idKota)
    {
        $data = [
            'nama_fta' => $kategoriPenilaian->formulirPenilaian->nama_fta,
            'tanggal' => $kategoriPenilaian->formulirPenilaian->tanggal_tenggat_pengisian,
            'judul_ta' => $mahasiswa->first()->kota->judul_ta,
            'start' => date('H:i', strtotime($mahasiswa->first()->kota->penjadwalan[0]->start)),
            'nama_kota' => $mahasiswa->first()->kota->nama_kota,
            'id_fta' => $idKota,
        ];
    
        return $data;
    }

     /**
     * Menampilkan halaman pemberian nilai seminar 3
     * 
     */
    public function pengisianNilaiSeminarIII(): View
    {   

        // Ambil nama fta
        // $ftaInfo

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
            'kode_fta' => 'FTA-011',
            'tanggal' => '7 Maret 2025',
            'waktu' => '10:00 - 11:00',
            // 'id_kota' => 'KoTA-313',
            // 'topik_ta' => 'Analisis Perbandingan Performa Model x dan y dalam Memprediksi Skor Esai pada Automated Essay Scoring'
        ];

        // Kirimkan data ke tampilan Blade
        return view('KelolaPenilaianTA.views.pemberian-nilai-dan-feedback.pengisian_nilai_seminar_III', compact('mahasiswa', 'mahasiswaList', 'kotaInfo'));
    }

    /**
     * Menampilkan halaman pemberian nilai sidang akhir
     * 
     */
    public function pengisianNilaiSidangAkhir(): View
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

        // Data Penilaian
        $penilaian = [
            [
                'no' => 1,
                'kriteria' => 'Kejelasan isi dokumen',
                'detail_kriteria' => [
                    'Detail Kriteria 1.1',
                    'Detail Kriteria 1.2',
                    'Detail Kriteria 1.3',
                    'Detail Kriteria 1.4'
                ],
                'bobot' => '35 %',
                'rentang_nilai' => '0 - 100',
                'nilai' => [
                    '≥ 80 (A)' => ['Sangat jelas dan lengkap', 'Bahasa mudah dipahami', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit'],
                    '75 - 79.99 (AB)' => ['Cukup jelas namun ada beberapa kekurangan', 'Bahasa kurang mudah dipahami', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit'],
                    '70 - 74.99 (B)' => ['Kurang jelas, ada banyak bagian yang ambigu', 'Bahasa sulit dipahami', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit'],
                    '65 - 69.99 (BC)' => ['Tidak jelas, sulit dipahami', 'Bahasa sangat sulit dipahami', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit'],
                    '60 - 64.99 (C)' => ['Sangat tidak jelas, tidak ada informasi yang berguna', 'Bahasa sangat sulit dipahami', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit'],
                    '< 60 (CD)' => ['Tidak ada informasi yang berguna', 'Bahasa sangat sulit dipahami', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit']
                ]
            ],
            [
                'no' => 2,
                'kriteria' => 'Presentasi',
                'detail_kriteria' => [
                    'Detail Kriteria 2.1',
                    'Detail Kriteria 2.2',
                    'Detail Kriteria 2.3',
                    'Detail Kriteria 2.4',
                    'Detail Kriteria 2.5'
                ],
                'bobot' => '10 %',
                'rentang_nilai' => '0 - 100',
                'nilai' => [
                    '≥ 80 (A)' => ['Penyampaian sangat menarik', 'Tidak ada gangguan teknis', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit'],
                    '75 - 79.99 (AB)' => ['Penyampaian cukup baik tetapi kurang interaktif', 'Ada beberapa gangguan teknis', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit'],
                    '70 - 74.99 (B)' => ['Kurang menarik, beberapa bagian kurang dipahami', 'Ada beberapa gangguan teknis', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit'],
                    '65 - 69.99 (BC)' => ['Sangat membosankan, kurang persiapan', 'Ada banyak gangguan teknis', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit'],
                    '60 - 64.99 (C)' => ['Tidak menarik, banyak kesalahan teknis', 'Ada banyak gangguan teknis', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit'],
                    '< 60 (CD)' => ['Tidak menarik, banyak kesalahan teknis', 'Ada banyak gangguan teknis', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit']
                ]
            ],
            [
                'no' => 3,
                'kriteria' => 'Tanya Jawab (Penguasaan materi terkait tugas yang dikerjakan)',
                'detail_kriteria' => [
                    'Detail Kriteria 3.1'
                ],
                'bobot' => '30 %',
                'rentang_nilai' => '0 - 100',
                'nilai' => [
                    '≥ 80 (A)' => ['Mahasiswa dapat menjawab dengan sangat baik beserta reasoning dan rasionalitas yang tinggi'],
                    '75 - 79.99 (AB)' => ['Mahasiswa dapat menjawab dengan baik beserta reasoning dan rasionalitas yang cukup'],
                    '70 - 74.99 (B)' => ['Mahasiswa dapat menjawab dengan baik beserta reasoning dan rasionalitas yang kurang'],
                    '65 - 69.99 (BC)' => ['Mahasiswa dapat menjawab dengan kurang baik beserta reasoning dan rasionalitas yang kurang'],
                    '60 - 64.99 (C)' => ['Mahasiswa dapat menjawab dengan kurang baik beserta tidak ada reasoning dan rasionalitas'],
                    '< 60 (CD)' => ['Mahasiswa tidak dapat menjawab']
                ]
            ],
            [
                'no' => 4,
                'kriteria' => 'Produk perangkat lunak',
                'detail_kriteria' => [
                    'Detail Kriteria 4.1'
                ],
                'bobot' => '25 %',
                'rentang_nilai' => '0 - 100',
                'nilai' => [
                    '≥ 80 (A)' => ['Produk yang dihasilkan sesuai dengan target Sidang Akhir, memenuhi spesifikasi, dan rancangan yang sesuai spesifikasi (sufficient)'],
                    '75 - 79.99 (AB)' => ['Produk yang dihasilkan sesuai dengan target Sidang Akhir, memenuhi spesifikasi, dan rancangan yang kurang sesuai spesifikasi (less sufficient)'],
                    '70 - 74.99 (B)' => ['Produk yang dihasilkan kurang dari target Sidang Akhir, tidak memenuhi spesifikasi, dan rancangan yang tidak sesuai spesifikasi (not sufficient).'],
                    '65 - 69.99 (BC)' => ['Produk yang dihasilkan kurang dari target Sidang Akhir, tidak memenuhi spesifikasi, dan rancangan yang tidak sesuai spesifikasi (not sufficient)'],
                    '60 - 64.99 (C)' => ['Produk yang dihasilkan kurang dari target Sidang Akhir, tidak memenuhi spesifikasi, dan tidak ada rancangan'],
                    '< 60 (CD)' => ['Produk yang dihasilkan tidak memenuhi target Sidang Akhir dan tidak memenuhi spesifikasi']
                ]
            ]
        ];

        return view('KelolaPenilaianTA.views.pemberian-nilai-dan-feedback.pengisian_nilai_sidang_akhir', compact('mahasiswa', 'penilaian', 'mahasiswaList', 'kotaInfo'));
    }

    public function simpanNilaiSeminar(Request $request, $idFta, $idKota): View
    {
        return $this->simpanNilaiSeminarII($request, $idFta, $idKota);
    }

    private function simpanNilaiSeminarII($request, $idFta, $idKota): View
    {
        $nip = auth()->user()->username;
        $nilai = $request->except('_token');

        $mahasiswa = Mahasiswa::where('id_kota', $idKota)->get();
    
        $this->inputNilaiKeDatabaseKriteriaPenilaian($mahasiswa, $nilai, $nip, $idFta);
        $this->inputNilaiKeDatabaseKategoriPenilaian($mahasiswa, $nilai, $nip, $idFta);
    
        $pengelolaanNilai = new PengelolaanNilaiController();
        return $pengelolaanNilai->detailNilaiMahasiswa($idFta);
    }
    

    /**
     * Helper function untuk input nilai mahasiswa ke database
     */
    private function inputNilaiKeDatabaseKategoriPenilaian($mahasiswa, $nilai, $nip, $idFta): void
    {
        $nilai_rata_rata = [];
        $bobot = FormPenilaian::where('id_fta', $idFta)->with('kriteriaPenilaian')->first();
        $bobotKriteria = $bobot->kriteriaPenilaian->pluck('bobot_kriteria')->toArray();
        $idKategori = KategoriPenilaian::where('id_fta', $idFta)->first()->id_kategori;

        // Menghitung rata-rata nilai untuk setiap mahasiswa dengan bobot
        foreach ($nilai as $index => $values) {
            $average = $this->hitungRataRataNilaiDenganBobot($values, $bobotKriteria);
            $nilai_rata_rata[] = $average;
        }

        foreach ($mahasiswa as $index => $mhs) {
            $mhs->nilaiKategori()->create([
                'nim' => $mhs->nim,
                'nip' => $nip,
                'id_kategori' => $idKategori,
                'nilai' => $nilai_rata_rata[$index],
            ]);
        }
    }

    /**
     * Helper function untuk input nilai kriteria penilaian ke database
     */
    private function inputNilaiKeDatabaseKriteriaPenilaian($mahasiswa, $nilai, $nip, $idFta): void
    {
        $kriteriaPenilaian = KriteriaPenilaian::where('id_fta', $idFta)->get();
    
        foreach($mahasiswa as $index => $mhs) {
            foreach($kriteriaPenilaian as $kriteriaIndex => $kriteria) {
                $nilaiKriteria = (double) $nilai['nilai' . $index][$kriteriaIndex];
                Log::info(json_encode($nilaiKriteria, JSON_PRETTY_PRINT));
                $mhs->nilaiKriteria()->create([
                    'nim' => $mhs->nim,
                    'nip' => $nip,
                    'id_kriteria' => $kriteria->id_kriteria,
                    'nilai_kriteria' => $nilaiKriteria,
                    'status_penilaian_dosen' => 'draf'
                ]);
            }
        }
    }

        /**
     * Menampilkan halaman pemberian nilai tugas akhir
     * 
     */
    public function pengisianNilaiTA(): View
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
            'kode_fta' => 'FTA-017',
            'tanggal' => '7 Maret 2025',
            'waktu' => '10:00 - 11:00'
        ];

        // Data penilaian berdasarkan kategori
        $penilaian = [
            [
                'kategori' => 'A',
                'judul' => 'Luaran Tugas Akhir',
                'sub_kriteria' => [
                    [
                        'kode' => 'a.1',
                        'kriteria' => 'Dokumen',
                        'bobot' => '30%',
                        'deskripsi' => null
                    ],
                    [
                        'kode' => 'a.2',
                        'kriteria' => 'Produk Perangkat Lunak/Hasil Penelitian',
                        'bobot' => '30%',
                        'deskripsi' => '(produk aplikasi (sistem, tools, atau yg lain) atau prototype / simulator atau model)'
                    ]
                ]
            ],
            [
                'kategori' => 'B',
                'judul' => 'Proses Bimbingan',
                'sub_kriteria' => [
                    [
                        'kode' => 'b.1',
                        'kriteria' => 'Softskill',
                        'bobot' => '20%',
                        'deskripsi' => '(Komunikasi (verbal dan tertulis), Kolaborasi / Kerja Tim, Kesungguhan, Manajemen Waktu)'
                    ],
                    [
                        'kode' => 'b.2',
                        'kriteria' => 'Hardskill',
                        'bobot' => '20%',
                        'deskripsi' => '(Kemampuan menganalisis/berpikir logis, merancang, coding, menguji produk PL atau hasil penelitian; technical writting)'
                    ]
                ]
            ]
        ];

        return view('KelolaPenilaianTA.views.pemberian-nilai-dan-feedback.pengisian_nilai_tugas_akhir', compact('mahasiswa', 'penilaian', 'mahasiswaList', 'kotaInfo'));
    }

    /**
     * Helper function untuk menghitung rata-rata nilai
     */
    public function hitungRataRataNilaiDenganBobot(array $nilai, array $bobot): float
    {
        $totalNilai = 0;
        $totalBobot = 0;
    
        foreach ($nilai as $index => $value) {
            $totalNilai += $value * $bobot[$index];
            $totalBobot += $bobot[$index];
        }
    
        return $totalBobot > 0 ? $totalNilai / $totalBobot : 0;
    }

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

    public function editNilaiSeminar(Request $request, $idFta, $idKota): View
{
    return $this->editNilaiSeminarII($request, $idFta, $idKota);
}

private function editNilaiSeminarII(Request $request, $idFta, $idKota): View
{
    $nip = auth()->user()->username;
    $nilai = $request->except('_token', '_method');

    $mahasiswa = Mahasiswa::where('id_kota', $idKota)->get();

    $this->updateNilaiKeDatabaseKriteriaPenilaian($mahasiswa, $nilai, $nip, $idFta);
    $this->updateNilaiKeDatabaseKategoriPenilaian($mahasiswa, $nilai, $nip, $idFta);

    $pengelolaanNilai = new PengelolaanNilaiController();
    return $pengelolaanNilai->detailNilaiMahasiswa($idFta);
}

/**
 * Helper function untuk update nilai kriteria penilaian ke database
 */
private function updateNilaiKeDatabaseKriteriaPenilaian($mahasiswa, $nilai, $nip, $idFta): void
{
    $kriteriaPenilaian = KriteriaPenilaian::where('id_fta', $idFta)->get();

    foreach ($mahasiswa as $index => $mhs) {
        // Hapus nilai kriteria yang sudah ada untuk mahasiswa ini agar tidak perlu update
        $mhs->nilaiKriteria()->whereIn('id_kriteria', $kriteriaPenilaian->pluck('id_kriteria'))->delete();

        foreach ($kriteriaPenilaian as $kriteriaIndex => $kriteria) {
            $nilaiKriteria = (double) $nilai['nilai' . $index][$kriteriaIndex];

            Log::info(json_encode($kriteria, JSON_PRETTY_PRINT));
            Log::info(json_encode($mhs, JSON_PRETTY_PRINT));

            // Masukkan data baru setelah penghapusan
            $mhs->nilaiKriteria()->create([
                'nim' => $mhs->nim,
                'nip' => $nip,
                'id_kriteria' => $kriteria->id_kriteria,
                'nilai_kriteria' => $nilaiKriteria,
            ]);
        }
    }
}

/**
 * Helper function untuk update nilai kategori penilaian ke database tanpa UPDATE
 */
private function updateNilaiKeDatabaseKategoriPenilaian($mahasiswa, $nilai, $nip, $idFta): void
{
    $nilai_rata_rata = [];
    $bobot = FormPenilaian::where('id_fta', $idFta)->with('kriteriaPenilaian')->first();
    $bobotKriteria = $bobot->kriteriaPenilaian->pluck('bobot_kriteria')->toArray();
    $idKategori = KategoriPenilaian::where('id_fta', $idFta)->first()->id_kategori;

    // Menghitung rata-rata nilai untuk setiap mahasiswa dengan bobot
    foreach ($nilai as $index => $values) {
        $average = $this->hitungRataRataNilaiDenganBobot($values, $bobotKriteria);
        $nilai_rata_rata[] = $average;
    }

    foreach ($mahasiswa as $index => $mhs) {
        // Hapus nilai kategori yang sudah ada agar tidak perlu update
        $mhs->nilaiKategori()->where('id_kategori', $idKategori)->delete();

        // Masukkan data baru setelah penghapusan
        $mhs->nilaiKategori()->create([
            'nim' => $mhs->nim,
            'nip' => $nip,
            'id_kategori' => $idKategori,
            'nilai' => $nilai_rata_rata[$index],
        ]);
    }
}

    public function editNilaiSeminar(Request $request, $idFta, $idKota): View
{
    return $this->editNilaiSeminarII($request, $idFta, $idKota);
}

private function editNilaiSeminarII(Request $request, $idFta, $idKota): View
{
    $nip = auth()->user()->username;
    $nilai = $request->except('_token', '_method');

    $mahasiswa = Mahasiswa::where('id_kota', $idKota)->get();

    $this->updateNilaiKeDatabaseKriteriaPenilaian($mahasiswa, $nilai, $nip, $idFta);
    $this->updateNilaiKeDatabaseKategoriPenilaian($mahasiswa, $nilai, $nip, $idFta);

    $pengelolaanNilai = new PengelolaanNilaiController();
    return $pengelolaanNilai->detailNilaiMahasiswa($idFta);
}

/**
 * Helper function untuk update nilai kriteria penilaian ke database
 */
private function updateNilaiKeDatabaseKriteriaPenilaian($mahasiswa, $nilai, $nip, $idFta): void
{
    $kriteriaPenilaian = KriteriaPenilaian::where('id_fta', $idFta)->get();

    foreach ($mahasiswa as $index => $mhs) {
        // Hapus nilai kriteria yang sudah ada untuk mahasiswa ini agar tidak perlu update
        $mhs->nilaiKriteria()->whereIn('id_kriteria', $kriteriaPenilaian->pluck('id_kriteria'))->delete();

        foreach ($kriteriaPenilaian as $kriteriaIndex => $kriteria) {
            $nilaiKriteria = (double) $nilai['nilai' . $index][$kriteriaIndex];

            Log::info(json_encode($kriteria, JSON_PRETTY_PRINT));
            Log::info(json_encode($mhs, JSON_PRETTY_PRINT));

            // Masukkan data baru setelah penghapusan
            $mhs->nilaiKriteria()->create([
                'nim' => $mhs->nim,
                'nip' => $nip,
                'id_kriteria' => $kriteria->id_kriteria,
                'nilai_kriteria' => $nilaiKriteria,
            ]);
        }
    }
}

/**
 * Helper function untuk update nilai kategori penilaian ke database tanpa UPDATE
 */
private function updateNilaiKeDatabaseKategoriPenilaian($mahasiswa, $nilai, $nip, $idFta): void
{
    $nilai_rata_rata = [];
    $bobot = FormPenilaian::where('id_fta', $idFta)->with('kriteriaPenilaian')->first();
    $bobotKriteria = $bobot->kriteriaPenilaian->pluck('bobot_kriteria')->toArray();
    $idKategori = KategoriPenilaian::where('id_fta', $idFta)->first()->id_kategori;

    // Menghitung rata-rata nilai untuk setiap mahasiswa dengan bobot
    foreach ($nilai as $index => $values) {
        $average = $this->hitungRataRataNilaiDenganBobot($values, $bobotKriteria);
        $nilai_rata_rata[] = $average;
    }

    foreach ($mahasiswa as $index => $mhs) {
        // Hapus nilai kategori yang sudah ada agar tidak perlu update
        $mhs->nilaiKategori()->where('id_kategori', $idKategori)->delete();

        // Masukkan data baru setelah penghapusan
        $mhs->nilaiKategori()->create([
            'nim' => $mhs->nim,
            'nip' => $nip,
            'id_kategori' => $idKategori,
            'nilai' => $nilai_rata_rata[$index],
        ]);
    }
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