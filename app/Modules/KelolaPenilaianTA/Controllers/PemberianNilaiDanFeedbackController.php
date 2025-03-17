<?php

namespace App\Modules\KelolaPenilaianTA\Controllers;

use App\Modules\Controller;
use App\Models\Mahasiswa;
use App\Models\Kota;
use App\Models\KategoriPenilaian;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Exports\RekapitulasiNilaiExport;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\FromCollection;

class PemberianNilaiDanFeedbackController extends Controller
{
     /**
     * Menampilkan halaman pemberian nilai seminar 1
     */
    public function pengisianMasukanSeminar1(): View
    {
        // Data Mahasiswa
        $mahasiswa = [
            'kode_fta' => 'FTA-04',
            'tanggal' => '7 Maret 2025',
            'waktu' => '10:00 - 11:00',
            'id_kota' => 'KoTA-313',
            'topik_ta' => 'Analisis Perbandingan Performa Model x dan y dalam Memprediksi Skor Esai pada Automated Essay Scoring',
            'list_mahasiswa' => [
                ['nim' => '221524044', 'nama' => 'Mahardika Pratama'],
                ['nim' => '221524052', 'nama' => 'Naia Siti Az-zahra'],
                ['nim' => '221524058', 'nama' => 'Salsabil Khoirunisa']
            ]
        ];

        return view('KelolaPenilaianTA.views.pemberian-nilai-dan-feedback.pengisian_masukan_seminar_1', compact('mahasiswa'));
    }

    /**
     * Menampilkan halaman pemberian nilai seminar 2
     * 
     */
    public function pengisianNilaiSeminarII(): View
    {

        // ID kota statis
        $idKota = 2;

        // Ambil hanya mahasiswa dengan id_kota = 2
        $mahasiswaList = Mahasiswa::with('user')->where('id_kota', $idKota)->get();

        // Ambil informasi KoTA berdasarkan id_kota = 2
        $kotaInfo = Kota::where('id_kota', $idKota)->first();

        // Data Mahasiswa
        $mahasiswa = [
            'kode_fta' => 'FTA-07',
            'tanggal' => '7 Maret 2025',
            'waktu' => '10:00 - 11:00',
            // 'id_kota' => 'KoTA-313',
            // 'topik_ta' => 'Analisis Perbandingan Performa Model x dan y dalam Memprediksi Skor Esai pada Automated Essay Scoring',
        ];

        return view('KelolaPenilaianTA.views.pemberian-nilai-dan-feedback.pengisian_nilai_seminar_II', compact('mahasiswa', 'mahasiswaList', 'kotaInfo'));
    }


    /**
     * Menampilkan halaman pemberian masukan seminar 2
     * 
     */
    public function pengisianMasukanSeminarII(): View
    {
        // Data Mahasiswa
        $mahasiswa = [
            'kode_fta' => 'FTA-08',
            'tanggal' => '7 Maret 2025',
            'waktu' => '10:00 - 11:00',
            'id_kota' => 'KoTA-313',
            'topik_ta' => 'Analisis Perbandingan Performa Model x dan y dalam Memprediksi Skor Esai pada Automated Essay Scoring',
            'list_mahasiswa' => [
                ['nim' => '221524044', 'nama' => 'Mahardika Pratama'],
                ['nim' => '221524052', 'nama' => 'Naia Siti Az-zahra'],
                ['nim' => '221524058', 'nama' => 'Salsabil Khoirunisa']
            ]
        ];

        return view('KelolaPenilaianTA.views.pemberian-nilai-dan-feedback.pengisian_masukan_seminar_II', compact('mahasiswa', 'mahasiswaList','kotaInfo'));
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
                'bobot' => '15 %',
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
                'bobot' => '35 %',
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
                'kriteria' => 'Prototipe yang dihasilkan',
                'detail_kriteria' => [
                    'Detail Kriteria 4.1'
                ],
                'bobot' => '15 %',
                'rentang_nilai' => '0 - 100',
                'nilai' => [
                    '≥ 80 (A)' => ['Produk yang dihasilkan sesuai dengan target Seminar III, memenuhi spesifikasi, dan rancangan yang sesuai spesifikasi (sufficient)'],
                    '75 - 79.99 (AB)' => ['Produk yang dihasilkan sesuai dengan target Seminar III, memenuhi spesifikasi, dan rancangan yang kurang sesuai spesifikasi (less sufficient)'],
                    '70 - 74.99 (B)' => ['Produk yang dihasilkan kurang dari target Seminar III, tidak memenuhi spesifikasi, dan rancangan yang tidak sesuai spesifikasi (not sufficient).'],
                    '65 - 69.99 (BC)' => ['Produk yang dihasilkan kurang dari target Seminar III, tidak memenuhi spesifikasi, dan rancangan yang tidak sesuai spesifikasi (not sufficient)'],
                    '60 - 64.99 (C)' => ['Produk yang dihasilkan kurang dari target Seminar III, tidak memenuhi spesifikasi, dan tidak ada rancangan'],
                    '< 60 (CD)' => ['Produk yang dihasilkan tidak memenuhi target Seminar III dan tidak memenuhi spesifikasi']
                ]
            ]
        ];

        // return view('KelolaPenilaianTA.views.pemberian-nilai-dan-feedback.pengisian_nilai_seminar_III', compact('mahasiswa', 'penilaian'));
        // Kirimkan data ke tampilan Blade
        return view('KelolaPenilaianTA.views.pemberian-nilai-dan-feedback.pengisian_nilai_seminar_III', compact('mahasiswa', 'mahasiswaList', 'kotaInfo'));
        // Kirimkan data ke tampilan Blade
        // return view('KelolaPenilaianTA.views.monitoring-nilai-mahasiswa.monitoring_mahasiswa', compact('mahasiswaList', 'kotaInfo', 'dosenPembimbing'));
    }

    /**
     * Menampilkan halaman pemberian masukan seminar 3
     * 
     */
    public function pengisianMasukanSeminarIII(): View
    {
        // Data Mahasiswa
        $mahasiswa = [
            'kode_fta' => 'FTA-012',
            'tanggal' => '7 Maret 2025',
            'waktu' => '10:00 - 11:00',
            'id_kota' => 'KoTA-313',
            'topik_ta' => 'Analisis Perbandingan Performa Model x dan y dalam Memprediksi Skor Esai pada Automated Essay Scoring',
            'list_mahasiswa' => [
                ['nim' => '221524044', 'nama' => 'Mahardika Pratama'],
                ['nim' => '221524052', 'nama' => 'Naia Siti Az-zahra'],
                ['nim' => '221524058', 'nama' => 'Salsabil Khoirunisa']
            ]
        ];

        return view('KelolaPenilaianTA.views.pemberian-nilai-dan-feedback.pengisian_masukan_seminar_III', compact('mahasiswa'));
    }

    /**
     * Menampilkan halaman pemberian nilai sidang akhir
     * 
     */
    public function pengisianNilaiSidangAkhir(): View
    {
        // Data Mahasiswa
        $mahasiswa = [
            'kode_fta' => 'FTA-015',
            'tanggal' => '7 Maret 2025',
            'waktu' => '10:00 - 11:00',
            'id_kota' => 'KoTA-313',
            'topik_ta' => 'Analisis Perbandingan Performa Model x dan y dalam Memprediksi Skor Esai pada Automated Essay Scoring',
            'list_mahasiswa' => [
                ['nim' => '221524044', 'nama' => 'Mahardika Pratama'],
                ['nim' => '221524052', 'nama' => 'Naia Siti Az-zahra'],
                ['nim' => '221524058', 'nama' => 'Salsabil Khoirunisa']
            ]
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

        return view('KelolaPenilaianTA.views.pemberian-nilai-dan-feedback.pengisian_nilai_sidang_akhir', compact('mahasiswa', 'penilaian'));
    }

    /**
     * Menampilkan halaman pemberian masukan sidang akhir
     * 
     */
    public function pengisianMasukanSidangAkhir(): View
    {
        // Data Mahasiswa
        $mahasiswa = [
            'kode_fta' => 'FTA-015',
            'tanggal' => '7 Maret 2025',
            'waktu' => '10:00 - 11:00',
            'id_kota' => 'KoTA-313',
            'topik_ta' => 'Analisis Perbandingan Performa Model x dan y dalam Memprediksi Skor Esai pada Automated Essay Scoring',
            'list_mahasiswa' => [
                ['nim' => '221524044', 'nama' => 'Mahardika Pratama'],
                ['nim' => '221524052', 'nama' => 'Naia Siti Az-zahra'],
                ['nim' => '221524058', 'nama' => 'Salsabil Khoirunisa']
            ]
        ];

        return view('KelolaPenilaianTA.views.pemberian-nilai-dan-feedback.pengisian_masukan_sidang_akhir', compact('mahasiswa'));
    }

    /**
     * Menampilkan halaman pemberian nilai tugas akhir
     * 
     */
    public function pengisianNilaiTA(): View
    {
        // Data Mahasiswa
        $mahasiswa = [
            'kode_fta' => 'FTA-017',
            'tanggal' => '7 Maret 2025',
            'waktu' => '10:00 - 11:00',
            'id_kota' => 'KoTA-313',
            'topik_ta' => 'Analisis Perbandingan Performa Model x dan y dalam Memprediksi Skor Esai pada Automated Essay Scoring',
            'list_mahasiswa' => [
                ['nim' => '221524044', 'nama' => 'Mahardika Pratama'],
                ['nim' => '221524052', 'nama' => 'Naia Siti Az-zahra'],
                ['nim' => '221524058', 'nama' => 'Salsabil Khoirunisa']
            ]
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

        return view('KelolaPenilaianTA.views.pemberian-nilai-dan-feedback.pengisian_nilai_tugas_akhir', compact('mahasiswa', 'penilaian'));
    }

    /**
     * Helper function untuk input nilai mahasiswa ke database
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

    /**
     * 
     */
    public function pengisianNilaiSeminar($id): View
    {
        if($id == 1) {
            // return view('KelolaPenilaianTA.views.pemberian-nilai-dan-feedback.pengisian_masukan_seminar_1');
        } else if($id == 2) {
            return view('KelolaPenilaianTA.views.pemberian-nilai-dan-feedback.pengisian_nilai_seminar_II');
        } else if($id == 3) {
            return view('KelolaPenilaianTA.views.pemberian-nilai-dan-feedback.pengisian_nilai_seminar_III');
        } else {
            return view('KelolaPenilaianTA.views.pemberian-nilai-dan-feedback.pengisian_masukan_sidang_akhir');
        } 
    }

    /**
     * 
     */
    public function pengisianMasukanSeminar($id): View
    {
        if($id == 1) {
            return view('KelolaPenilaianTA.views.pemberian-nilai-dan-feedback.pengisian_masukan_seminar_1');
        } else if($id == 2) {
            return view('KelolaPenilaianTA.views.pemberian-nilai-dan-feedback.pengisian_masukan_seminar_II');
        } else if($id == 3) {
            return view('KelolaPenilaianTA.views.pemberian-nilai-dan-feedback.pengisian_masukan_seminar_III');
        } else {
            return view('KelolaPenilaianTA.views.pemberian-nilai-dan-feedback.pengisian_masukan_sidang_akhir');
        } 
    }
}