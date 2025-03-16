<?php

namespace App\Modules\KelolaPenilaianTA\Controllers;

use App\Modules\Controller;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Exports\RekapitulasiNilaiExport;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\FromCollection;


class KelolaPenilaianTAController extends Controller
{
    public function index_form(): View
    {
        for ($i = 1; $i <= 100; $i++) {
            $data = [
                [
                    'kode' => 'FTA 04',
                    'nama' => 'Penilaian Seminar I',
                ],
                [
                    'kode' => 'FTA 07',
                    'nama' => 'Penilaian Semester II',
                ],
                [
                    'kode' => 'FTA 08',
                    'nama' => 'Masukan Seminar II',
                ],
                [
                    'kode' => 'FTA 011',
                    'nama' => 'Penilaian Semester III',
                ],
                [
                    'kode' => 'FTA 012',
                    'nama' => 'Masukan Seminar III',
                ],
            ];
        }

        return view('KelolaPenilaianTA.views.FormulirPenilaianTA', compact('data'));
    }

    public function index(): View
    {
        return view('KelolaPenilaianTA.views.view');
    }

    public function create(): View
    {
        return view('KelolaPenilaianTA.views.TambahFormulirPenilaian');
    }

    public function edit()
    {
        // Data dummy untuk sementara
        $formulir = (object) [
            'kodeFTA' => 'FTA.011',
            'namaFTA' => 'PENILAIAN SEMINAR III',
            'tanggalTenggat' => '2025-03-05',
            'waktuTenggat' => '23:59',
            'aspekPenilaian' => [
                (object) [
                    'kriteria' => 'Kejelasan isi dokumen',
                    'bobot' => 40,
                    'detail' => 'Kejelasan kaitan antar bab/sub bab/kaitan (hubungan sebab akibat/ reasoning, rasionalitas)',
                    'lebih80' => 'Deskripsi sangat jelas dan rinci',
                    'tujuhPuluhLima' => 'Deskripsi cukup jelas namun ada bagian yang perlu diperjelas',
                    'tujuhPuluh' => 'Deskripsi kurang jelas dan perlu banyak perbaikan',
                    'enamPuluhLima' => 'Deskripsi tidak jelas dan membingungkan',
                    'enamPuluh' => 'Deskripsi tidak jelas dan membingungkan',
                    'kurang60' => 'Deskripsi tidak jelas dan membingungkan',
                ],
            ],
        ];

        return view('KelolaPenilaianTA.views.UbahFormulirTA', compact('formulir'));
    }

    public function update(Request $request)
    {
        return redirect()->route('formulir-penilaian.index')->with('success', 'Formulir penilaian berhasil diperbarui.');
    }

    public function indexMonitoringMahasiswa(): View
    {
        return view('KelolaPenilaianTA.views.monitoring_mahasiswa');
    }

    public function indexMonitoringFeedback(): View
    {
        return view('KelolaPenilaianTA.views.monitoring_feedback');
    }

    public function indexMonitoringRubrik(): View
    {
        return view('KelolaPenilaianTA.views.monitoring_rubrik');
    }

    public function kelola_nilai(): View
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

        return view('KelolaPenilaianTA.views.KelolaPenilaianTA', compact('data'));
    }

    public function detail_nilai_mahasiswa($kategori): View
    {
        $data = [];
        $kategori = Str::title(str_replace('-', ' ', $kategori));

        for ($i = 1; $i <= 100; $i++) {
            $data[] = [
                'nama' => "Nama Mahasiswa #" . $i,
                'kelompok' => rand(1, 100),
                'penguji1' => 'penguji 1',
                'penguji2' => 'penguji 2',
                'penguji3' => 'penguji 3',
                'pembimbing1' => rand(50, 100),
                'pembimbing2' => rand(50, 100),
                'p1' => rand(50, 100),
                'p2' => rand(50, 100),
                'p3' => rand(50, 100),
                'rata_rata' => rand(50, 100),
            ];
        }

        return view('KelolaPenilaianTA.views.DetailNilaiMahasiswa', compact('data', 'kategori'));
    }

    public function getRekapNilai(): View
    {
        $data = [];

        for ($i = 1; $i <= 100; $i++) {
            $data[] = [
                'nim' => "221524" . str_pad($i, 3, '0', STR_PAD_LEFT),
                'nama' => "Nama Mahasiswa #" . $i,
                'prodi' => (rand(0, 1) == 0) ? "D3-Teknik Informatika" : "D4-Teknik Informatika",
                'kelas' => (rand(0, 1) == 0) ? "4A" : "4B",
                'kelompok' => "Kelompok " . rand(1, 5),
                'seminar2_penguji1' => rand(50, 100),
                'seminar2_penguji2' => rand(50, 100),
                'seminar2_penguji3' => -1,
                'seminar3_penguji1' => rand(50, 100),
                'seminar3_penguji2' => rand(50, 100),
                'seminar3_penguji3' => rand(50, 100),
                'sidang_penguji1' => rand(50, 100),
                'sidang_penguji2' => rand(50, 100),
                'sidang_penguji3' => rand(50, 100),
                'pembimbing1' => rand(50, 100),
                'pembimbing2' => rand(50, 100),
                'uts' => rand(50, 100),
                'uas' => rand(50, 100),
                'lain_lain' => rand(50, 100),
                'nilai_akhir' => rand(50, 100),
                'predikat' => ["A", "B", "C", "D", "E"][rand(0, 4)]
            ];
        }

        return view('KelolaPenilaianTA.views.RekapitulasiNilai', compact('data'));
    }

    public function exportExcel(Request $request)
    {
        // Ambil filter dari request
        $filterProdi = $request->query('prodi');
        $filterKelas = $request->query('kelas');

        $data = [];
        for ($i = 1; $i <= 100; $i++) {
            $data[] = [
                'nim' => "221524" . str_pad($i, 3, '0', STR_PAD_LEFT),
                'nama' => "Nama Mahasiswa #" . $i,
                'prodi' => (rand(0, 1) == 0) ? "D3-Teknik Informatika" : "D4-Teknik Informatika",
                'kelas' => (rand(0, 1) == 0) ? "4A" : "4B",
                'kelompok' => "Kelompok " . rand(1, 5),
                'seminar2_penguji1' => rand(50, 100),
                'seminar2_penguji2' => rand(50, 100),
                'seminar2_penguji3' => -1,
                'seminar3_penguji1' => rand(50, 100),
                'seminar3_penguji2' => rand(50, 100),
                'seminar3_penguji3' => rand(50, 100),
                'sidang_penguji1' => rand(50, 100),
                'sidang_penguji2' => rand(50, 100),
                'sidang_penguji3' => rand(50, 100),
                'pembimbing1' => rand(50, 100),
                'pembimbing2' => rand(50, 100),
                'uts' => rand(50, 100),
                'uas' => rand(50, 100),
                'lain_lain' => rand(50, 100),
                'nilai_akhir' => rand(50, 100),
                'predikat' => ["A", "B", "C", "D", "E"][rand(0, 4)]
            ];
        }

        // Terapkan filter
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

        return view('KelolaPenilaianTA.views.pengisian_masukan_seminar_1', compact('mahasiswa'));
    }

    public function pengisianNilaiSeminarII(): View
    {
        // Data Mahasiswa
        $mahasiswa = [
            'kode_fta' => 'FTA-07',
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
                'bobot' => '40 %',
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
                'bobot' => '20 %',
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
                'kriteria' => 'Tanya Jawab',
                'detail_kriteria' => [
                    'Detail Kriteria 3.1'
                ],
                'bobot' => '40 %',
                'rentang_nilai' => '0 - 100',
                'nilai' => [
                    '≥ 80 (A)' => ['Jawaban sangat jelas dan mendalam'],
                    '75 - 79.99 (AB)' => ['Jawaban cukup jelas tetapi kurang detail'],
                    '70 - 74.99 (B)' => ['Jawaban kurang memadai, ada kesalahan'],
                    '65 - 69.99 (BC)' => ['Jawaban tidak sesuai atau ragu-ragu'],
                    '60 - 64.99 (C)' => ['Jawaban tidak sesuai, tidak ada pengetahuan'],
                    '< 60 (CD)' => ['Jawaban tidak sesuai, tidak ada pengetahuan']
                ]
            ]
        ];

        return view('KelolaPenilaianTA.views.pengisian_nilai_seminar_II', compact('mahasiswa', 'penilaian'));
    }

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

        return view('KelolaPenilaianTA.views.pengisian_masukan_seminar_II', compact('mahasiswa'));
    }

    public function pengisianNilaiSeminarIII(): View
    {
        // Data Mahasiswa
        $mahasiswa = [
            'kode_fta' => 'FTA-011',
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

        return view('KelolaPenilaianTA.views.pengisian_nilai_seminar_III', compact('mahasiswa', 'penilaian'));
    }

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

        return view('KelolaPenilaianTA.views.pengisian_masukan_seminar_III', compact('mahasiswa'));
    }

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

        return view('KelolaPenilaianTA.views.pengisian_nilai_sidang_akhir', compact('mahasiswa', 'penilaian'));
    }

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

        return view('KelolaPenilaianTA.views.pengisian_masukan_sidang_akhir', compact('mahasiswa'));
    }

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

        return view('KelolaPenilaianTA.views.pengisian_nilai_tugas_akhir', compact('mahasiswa', 'penilaian'));
    }
}