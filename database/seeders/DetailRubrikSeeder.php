<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Models\DetailRubrik;

class DetailRubrikSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (!Schema::hasTable('detail_rubrik')) {
            return;
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('detail_rubrik')->truncate();
        
        $data = [
            [
                'id_detail_rubrik' => '1',
                'id_rubrik' => '1',
                'detail_rubrik_penilaian' => 'Dokumen yang dibuat memiliki konten yang sangat lengkap dan keterkaitan antar bab/ sub kajian sangat erat, serta dapat dipertanggung jawabkan.',
                'id_nilai' => 'A'
            ],
            [
                'id_detail_rubrik' => '2',
                'id_rubrik' => '1',
                'detail_rubrik_penilaian' => 'Dokumen yang dibuat memiliki konten yang  lengkap dan keterkaitan antar bab/ sub kajian kurang erat, serta dapat dipertanggung jawabkan.',
                'id_nilai' => 'AB'
            ],
            [
                'id_detail_rubrik' => '3',
                'id_rubrik' => '1',
                'detail_rubrik_penilaian' => 'Dokumen yang dibuat memiliki konten yang  lengkap dan keterkaitan antar bab/ sub kajian tidak erat, serta dapat dipertanggung jawabkan.',
                'id_nilai' => 'B'
            ],
            [
                'id_detail_rubrik' => '4',
                'id_rubrik' => '1',
                'detail_rubrik_penilaian' => 'Dokumen yang dibuat memiliki konten yang  tidak lengkap dan keterkaitan antar bab/ sub kajian erat, serta dapat dipertanggung jawabkan.',
                'id_nilai' => 'BC'
            ],
            [
                'id_detail_rubrik' => '5',
                'id_rubrik' => '1',
                'detail_rubrik_penilaian' => 'Dokumen yang dibuat memiliki konten yang  tidak lengkap dan keterkaitan antar bab/ sub kajian tidak erat, serta kurang dapat dipertanggung jawabkan.',
                'id_nilai' => 'C'
            ],
            [
                'id_detail_rubrik' => '6',
                'id_rubrik' => '1',
                'detail_rubrik_penilaian' => 'Dokumen yang dibuat memiliki konten yang  tidak lengkap dan keterkaitan antar bab/ sub kajian tidak erat, serta tidak dapat dipertanggung jawabkan.',
                'id_nilai' => 'CD'
            ],
            [
                'id_detail_rubrik' => '7',
                'id_rubrik' => '2',
                'detail_rubrik_penilaian' => 'Mahasiswa menguasai serta memahami penerapan cara-cara/ metoda pengembangan aplikasi dan modelling tools pada pengembangan aplikasi',
                'id_nilai' => 'A'
            ],
            [
                'id_detail_rubrik' => '8',
                'id_rubrik' => '2',
                'detail_rubrik_penilaian' => 'Mahasiswa menguasai penerapan cara-cara/ metoda pengembangan aplikasi tetapi tidak menguasai modelling tools pada pengembangan aplikasi.',
                'id_nilai' => 'AB'
            ],
            [
                'id_detail_rubrik' => '9',
                'id_rubrik' => '2',
                'detail_rubrik_penilaian' => 'Mahasiswa tidak menguasai penerapan metoda pengembangan aplikasi. tetapi  menguasai penggunaan modelling tools pada pengembangan aplikasi',
                'id_nilai' => 'B'
            ],
            [
                'id_detail_rubrik' => '10',
                'id_rubrik' => '2',
                'detail_rubrik_penilaian' => 'Mahasiswa menguasai  penerapan cara-cara/metoda pengembangan aplikasi tetapi tidak menguasai modelling tools pada pengembangan aplikasi.',
                'id_nilai' => 'BC'
            ],
            [
                'id_detail_rubrik' => '11',
                'id_rubrik' => '2',
                'detail_rubrik_penilaian' => 'Mahasiswa tidak menguasai atau penerapan cara-cara/metoda pengembangan aplikasi dan modelling tools pada pengembangan aplikasi',
                'id_nilai' => 'C'
            ],
            [
                'id_detail_rubrik' => '12',
                'id_rubrik' => '2',
                'detail_rubrik_penilaian' => 'Tidak ada metodologi dan modelling tools yang digunakan pada pengembangan aplikasi.',
                'id_nilai' => 'CD'
            ],
            [
                'id_detail_rubrik' => '13',
                'id_rubrik' => '3',
                'detail_rubrik_penilaian' => 'Pustaka yang digunakan sangat sesuai dengan penelitian dan kualitas pustaka yang sangat baik',
                'id_nilai' => 'A'
            ],
            [
                'id_detail_rubrik' => '14',
                'id_rubrik' => '3',
                'detail_rubrik_penilaian' => 'Pustaka yang digunakan sesuai dengan penelitian dan kualitas pustaka yang baik',
                'id_nilai' => 'AB'
            ],
            [
                'id_detail_rubrik' => '15',
                'id_rubrik' => '3',
                'detail_rubrik_penilaian' => 'Pustaka yang digunakan sesuai dengan penelitian tetapi kualitas pustaka hanya cukup baik.',
                'id_nilai' => 'B'
            ],
            [
                'id_detail_rubrik' => '16',
                'id_rubrik' => '3',
                'detail_rubrik_penilaian' => 'Pustaka yang digunakan cukup sesuai dengan penelitian dan kualitas yang cukup baik',
                'id_nilai' => 'BC'
            ],
            [
                'id_detail_rubrik' => '17',
                'id_rubrik' => '3',
                'detail_rubrik_penilaian' => 'Pustaka yang digunakan cukup sesuai dengan penelitian tetapi kualitas pustaka hanya kurang baik',
                'id_nilai' => 'C'
            ],
            [
                'id_detail_rubrik' => '18',
                'id_rubrik' => '3',
                'detail_rubrik_penilaian' => 'Pustaka yang digunakan tidak sesuai dengan penelitian dan kualitas pustaka hanya kurang baik',
                'id_nilai' => 'CD'
            ],
            [
                'id_detail_rubrik' => '19',
                'id_rubrik' => '4',
                'detail_rubrik_penilaian' => 'Dokumen yang dibuat memenuhi kriteria a, b, dan c dengan sangat baik/jelas',
                'id_nilai' => 'A'
            ],
            [
                'id_detail_rubrik' => '20',
                'id_rubrik' => '4',
                'detail_rubrik_penilaian' => 'Dokumen yang dibuat memenuhi kriteria a, b, dan c dengan baik/jelas',
                'id_nilai' => 'AB'
            ],
            [
                'id_detail_rubrik' => '21',
                'id_rubrik' => '4',
                'detail_rubrik_penilaian' => 'Dokumen yang dibuat memenuhi kriteria a dan c dengan baik/jelas',
                'id_nilai' => 'B'
            ],
            [
                'id_detail_rubrik' => '22',
                'id_rubrik' => '4',
                'detail_rubrik_penilaian' => 'Dokumen yang dibuat memenuhi kriteria a dan c dengan cukup baik/jelas',
                'id_nilai' => 'BC'
            ],
            [
                'id_detail_rubrik' => '23',
                'id_rubrik' => '4',
                'detail_rubrik_penilaian' => 'Dokumen yang dibuat memenuhi kriteria a dan b dengan baik/jelas',
                'id_nilai' => 'C'
            ],
            [
                'id_detail_rubrik' => '24',
                'id_rubrik' => '4',
                'detail_rubrik_penilaian' => 'Dokumen yang dibuat memenuhi kriteria b saja dengan baik/jelas',
                'id_nilai' => 'CD'
            ],
            [
                'id_detail_rubrik' => '25',
                'id_rubrik' => '5',
                'detail_rubrik_penilaian' => 'Mahasiswa menguasai serta memahami domain TA yang dikerjakan, penerapan cara-cara/metoda pengembangan aplikasi dan cara/metoda penggunaan tools pengembangan aplikasi (kriteria a, b, c)',
                'id_nilai' => 'A'
            ],
            [
                'id_detail_rubrik' => '26',
                'id_rubrik' => '5',
                'detail_rubrik_penilaian' => 'Mahasiswa menguasai domain TA yang dikerjakan dan penerapan cara-cara/metoda pengembangan aplikasi (kriteria a, b) tetapi tidak menguasai atau memahami cara/metoda penggunaan tools pengembangan aplikasi (kriteria c)',
                'id_nilai' => 'AB'
            ],
            [
                'id_detail_rubrik' => '27',
                'id_rubrik' => '5',
                'detail_rubrik_penilaian' => 'Mahasiswa menguasai domain TA yang dikerjakan dan cara/metoda penggunaan tools pengembangan aplikasi (kriteria a, c) tetapi tidak menguasai atau memahami penerapan cara-cara/metoda pengembangan aplikasi (kriteria b)',
                'id_nilai' => 'B'
            ],
            [
                'id_detail_rubrik' => '28',
                'id_rubrik' => '5',
                'detail_rubrik_penilaian' => 'Mahasiswa menguasai  penerapan cara-cara/metoda pengembangan aplikasi dan cara/metoda penggunaan tools pengembangan aplikasi (kriteria b, c) tetapi tidak menguasai atau memahami domain TA yang dikerjakan (kriteria a)',
                'id_nilai' => 'BC'
            ],
            [
                'id_detail_rubrik' => '29',
                'id_rubrik' => '5',
                'detail_rubrik_penilaian' => 'Mahasiswa menguasai atau memahami salah satu dari: domain TA yang dikerjakan, penerapan cara-cara/metoda pengembangan aplikasi dan cara/metoda penggunaan tools pengembangan aplikasi (Salah satu dari kriteria a, b, c)',
                'id_nilai' => 'C'
            ],
            [
                'id_detail_rubrik' => '30',
                'id_rubrik' => '5',
                'detail_rubrik_penilaian' => 'Mahasiswa Tidak Menguasai atau memahami seluruh kriteria berikut: domain TA yang dikerjakan, penerapan cara-cara/metoda pengembangan aplikasi dan cara/metoda penggunaan tools pengembangan aplikasi (kriteria a, b, c)',
                'id_nilai' => 'CD'
            ],
            [
                'id_detail_rubrik' => '31',
                'id_rubrik' => '6',
                'detail_rubrik_penilaian' => 'Mahasiswa menjelaskan dengan sangat baik dan menyeluruh serta membangkitkan antusiasme pemirsa untuk menyimak dari awal hingga akhir presentasi.',
                'id_nilai' => 'A'
            ],
            [
                'id_detail_rubrik' => '32',
                'id_rubrik' => '6',
                'detail_rubrik_penilaian' => 'Mahasiswa menjelaskan dengan  baik dan menyeluruh serta membangkitkan antusiasme pemirsa untuk menyimak presentasi.',
                'id_nilai' => 'AB'
            ],
            [
                'id_detail_rubrik' => '33',
                'id_rubrik' => '6',
                'detail_rubrik_penilaian' => 'Mahasiswa menjelaskan dengan kurang baik dan menyeluruh serta kurang membangkitkan antusiasme pemirsa untuk menyimak presentasi.',
                'id_nilai' => 'B'
            ],
            [
                'id_detail_rubrik' => '34',
                'id_rubrik' => '6',
                'detail_rubrik_penilaian' => 'Mahasiswa menjelaskan dengan kurang baik dan kurang menyeluruh serta kurang membangkitkan antusiasme pemirsa untuk menyimak presentasi.',
                'id_nilai' => 'BC'
            ],
            [
                'id_detail_rubrik' => '35',
                'id_rubrik' => '6',
                'detail_rubrik_penilaian' => 'Mahasiswa menjelaskan dengan kurang baik dan kurang menyeluruh serta tidak membangkitkan antusiasme pemirsa untuk menyimak presentasi.',
                'id_nilai' => 'C'
            ],
            [
                'id_detail_rubrik' => '36',
                'id_rubrik' => '6',
                'detail_rubrik_penilaian' => 'Mahasiswa menjelaskan dengan tidak baik dan tidak menyeluruh serta tidak membangkitkan antusiasme pemirsa untuk menyimak presentasi.',
                'id_nilai' => 'CD'
            ],
            [
                'id_detail_rubrik' => '37',
                'id_rubrik' => '7',
                'detail_rubrik_penilaian' => 'Mahasiswa menjelaskan berdasarkan poin - poin penting pada bahan presentasi',
                'id_nilai' => 'A'
            ],
            [
                'id_detail_rubrik' => '38',
                'id_rubrik' => '7',
                'detail_rubrik_penilaian' => 'Mahasiswa menjelaskan berdasarkan poin - poin penting dengan uraiannya  pada bahan presentasi.',
                'id_nilai' => 'AB'
            ],
            [
                'id_detail_rubrik' => '39',
                'id_rubrik' => '7',
                'detail_rubrik_penilaian' => 'Mahasiswa menjelaskan dengan membaca poin - poin penting dengan uraiannya  pada bahan presentasi.',
                'id_nilai' => 'B'
            ],
            [
                'id_detail_rubrik' => '40',
                'id_rubrik' => '7',
                'detail_rubrik_penilaian' => 'Mahasiswa hanya membaca poin - poin penting dengan uraiannya  pada bahan presentasi.',
                'id_nilai' => 'BC'
            ],
            [
                'id_detail_rubrik' => '41',
                'id_rubrik' => '7',
                'detail_rubrik_penilaian' => 'Mahasiswa tidak menjelaskan poin - poin penting dan uraiannya pada bahan presentasi',
                'id_nilai' => 'C'
            ],
            [
                'id_detail_rubrik' => '42',
                'id_rubrik' => '7',
                'detail_rubrik_penilaian' => 'Tidak ada poin penting pada uraian pembahasan pada bahan presentasi.',
                'id_nilai' => 'CD'
            ],
            [
                'id_detail_rubrik' => '43',
                'id_rubrik' => '8',
                'detail_rubrik_penilaian' => 'Mahasiswa dapat menjawab dengan sangat baik beserta reasoning dan rasionalitas yang tinggi.',
                'id_nilai' => 'A'
            ],
            [
                'id_detail_rubrik' => '44',
                'id_rubrik' => '8',
                'detail_rubrik_penilaian' => 'Mahasiswa dapat menjawab dengan baik beserta reasoning dan rasionalitas yang cukup.',
                'id_nilai' => 'AB'
            ],
            [
                'id_detail_rubrik' => '45',
                'id_rubrik' => '8',
                'detail_rubrik_penilaian' => 'Mahasiswa dapat menjawab dengan baik beserta reasoning dan rasionalitas yang kurang.',
                'id_nilai' => 'B'
            ],
            [
                'id_detail_rubrik' => '46',
                'id_rubrik' => '8',
                'detail_rubrik_penilaian' => 'Mahasiswa dapat menjawab dengan kurang baik beserta reasoning dan rasionalitas yang kurang.',
                'id_nilai' => 'BC'
            ],
            [
                'id_detail_rubrik' => '47',
                'id_rubrik' => '8',
                'detail_rubrik_penilaian' => 'Mahasiswa dapat menjawab dengan kurang baik beserta tidak ada reasoning dan rasionalitas.',
                'id_nilai' => 'C'
            ],
            [
                'id_detail_rubrik' => '48',
                'id_rubrik' => '8',
                'detail_rubrik_penilaian' => 'Mahasiswa tidak dapat menjawab.',
                'id_nilai' => 'CD'
            ],
            [
                'id_detail_rubrik' => '49',
                'id_rubrik' => '9',
                'detail_rubrik_penilaian' => 'Produk yang dihasilkan sesuai dengan target Seminar III, memenuhi spesifikasi, dan rancangan yang sesuai spesifikasi (sufficient).',
                'id_nilai' => 'A'
            ],
            [
                'id_detail_rubrik' => '50',
                'id_rubrik' => '9',
                'detail_rubrik_penilaian' => 'Produk yang dihasilkan sesuai dengan target Seminar III, memenuhi spesifikasi, dan rancangan yang kurang sesuai spesifikasi (less sufficient).',
                'id_nilai' => 'AB'
            ],
            [
                'id_detail_rubrik' => '51',
                'id_rubrik' => '9',
                'detail_rubrik_penilaian' => 'Produk yang dihasilkan kurang dari target Seminar III, tidak memenuhi spesifikasi, dan rancangan yang tidak sesuai spesifikasi (not sufficient)',
                'id_nilai' => 'B'
            ],
            [
                'id_detail_rubrik' => '52',
                'id_rubrik' => '9',
                'detail_rubrik_penilaian' => 'Produk yang dihasilkan kurang dari target Seminar III, tidak memenuhi spesifikasi, dan rancangan yang tidak sesuai spesifikasi (not sufficient).',
                'id_nilai' => 'BC'
            ],
            [
                'id_detail_rubrik' => '53',
                'id_rubrik' => '9',
                'detail_rubrik_penilaian' => 'Produk yang dihasilkan kurang dari target Seminar III, tidak memenuhi spesifikasi, dan tidak ada rancangan.',
                'id_nilai' => 'C'
            ],
            [
                'id_detail_rubrik' => '54',
                'id_rubrik' => '9',
                'detail_rubrik_penilaian' => 'Produk yang dihasilkan tidak memenuhi target Seminar III dan tidak memenuhi spesifikasi.',
                'id_nilai' => 'CD'
            ]
        ];

        foreach ($data as $item) {
            DetailRubrik::create($item);
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
