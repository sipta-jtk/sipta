<?php

namespace App\Modules\KelolaPenilaianTA\Controllers;

use App\Modules\Controller;
use Illuminate\View\View;

class KelolaPenilaianTAController extends Controller
{
    public function index(): View
    {
        return view('KelolaPenilaianTA.views.view');
    }

    public function pengisianNilaiSeminar2(): View
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
                'bobot' => '30%',
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
                'bobot' => '40%',
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
                'bobot' => '30%',
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

        return view('KelolaPenilaianTA.views.pengisian_nilai_seminar_2', compact('mahasiswa', 'penilaian'));
    }

    public function pengisianFeedbackSeminar2(): View
    {
        // Data Mahasiswa
        $mahasiswa = [
            'kode_fta' => 'FTA-07',
            'tanggal' => '7 Maret 2025',
            'waktu' => '10:00 - 11:00',
            'id_kota' => 'KoTA-313',
            'topik_ta' => 'Analisis Perbandingan Performa Model x dan y dalam Memprediksi Skor Esai pada Automated Essay Scoring',
            'list_mahasiswa' => [
                ['nim' => '221524052', 'nama' => 'Naia Siti Az-zahra'],
                ['nim' => '221524052', 'nama' => 'Naia Siti Az-zahra'],
                ['nim' => '221524052', 'nama' => 'Naia Siti Az-zahra']
            ]
        ];

        return view('KelolaPenilaianTA.views.pengisian_feedback_seminar_2', compact('mahasiswa'));
    }
}