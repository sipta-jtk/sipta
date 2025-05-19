<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Models\Rubrik;

class RubrikSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (!Schema::hasTable('rubrik')) {
            return;
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('rubrik')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $data = [
            ['id_kriteria' => 13, 'nama_rubrik' => 'APE memenuhi kebutuhan eksperimen'],
            ['id_kriteria' => 13, 'nama_rubrik' => 'Kompleksitas pembuatan APE'],
            ['id_kriteria' => 13, 'nama_rubrik' => 'Cara pengembangan APE'],
            ['id_kriteria' => 13, 'nama_rubrik' => 'Keselesaian/Ketuntasan APE'],
            ['id_kriteria' => 14, 'nama_rubrik' => 'Kompleksitas Eksperimen'],
            ['id_kriteria' => 14, 'nama_rubrik' => 'Kualitas data & hasil eksperimen'],
            ['id_kriteria' => 14, 'nama_rubrik' => 'Keselesaian/ketuntasan Eksperimen'],
            ['id_kriteria' => 15, 'nama_rubrik' => 'Kelengkapan konten'],
            ['id_kriteria' => 15, 'nama_rubrik' => 'Kualitas konten'],
            ['id_kriteria' => 15, 'nama_rubrik' => 'Kualitas dan kesesuaian pustaka yang digunakan'],
            ['id_kriteria' => 16, 'nama_rubrik' => 'Penilaian Penguasaan Materi berdasarkan'],
            ['id_kriteria' => 17, 'nama_rubrik' => 'Kesesuaian AYK dengan kebutuhan yang telah ditetapkan'],
            ['id_kriteria' => 17, 'nama_rubrik' => 'Kesesuaian AYK dengan rancangan yang telah ditetapkan'],
            ['id_kriteria' => 17, 'nama_rubrik' => 'Kelengkapan & kesiapan data untuk aplikasi'],
            ['id_kriteria' => 17, 'nama_rubrik' => 'AYK sudah lolos tahapan pengujian (optional - menyesuaikan SDLC model yang dipilih)'],
            ['id_kriteria' => 18, 'nama_rubrik' => 'Kontribusi pembuatan AYK'],
            ['id_kriteria' => 18, 'nama_rubrik' => 'Kelengkapan dokumentasi teknis aplikasi'],
            ['id_kriteria' => 18, 'nama_rubrik' => 'Kualitas analisis, desain, dan implementasi'],
            ['id_kriteria' => 19, 'nama_rubrik' => 'Kelengkapan konten'],
            ['id_kriteria' => 19, 'nama_rubrik' => 'Kualitas konten'],
            ['id_kriteria' => 19, 'nama_rubrik' => 'Kesesuaian dan kualitas pustaka yang digunakan'],
            ['id_kriteria' => 20, 'nama_rubrik' => 'Penguasaan Materi'],
            ['id_kriteria' => 21, 'nama_rubrik' => 'APE memenuhi kebutuhan eksperimen'],
            ['id_kriteria' => 21, 'nama_rubrik' => 'Kompleksitas pembuatan APE'],
            ['id_kriteria' => 21, 'nama_rubrik' => 'Cara pengembangan APE'],
            ['id_kriteria' => 21, 'nama_rubrik' => 'Keselesaian/Ketuntasan APE'],
            ['id_kriteria' => 22, 'nama_rubrik' => 'Kompleksitas Eksperimen'],
            ['id_kriteria' => 22, 'nama_rubrik' => 'Data Eksperimen:'],
            ['id_kriteria' => 22, 'nama_rubrik' => 'Keselesaian/Ketuntasan Eksperimen'],
            ['id_kriteria' => 23, 'nama_rubrik' => 'Keselesaian/Ketuntasan analisis hasil eksperimen'],
            ['id_kriteria' => 24, 'nama_rubrik' => 'Penilaian kesimpulan hasil penelitian berdasarkan:'],
            ['id_kriteria' => 25, 'nama_rubrik' => 'Kelengkapan konten'],
            ['id_kriteria' => 25, 'nama_rubrik' => 'Kualitas konten'],
            ['id_kriteria' => 25, 'nama_rubrik' => 'Kesesuaian dan kualitas pustaka yang digunakan'],
            ['id_kriteria' => 26, 'nama_rubrik' => 'Penilaian Penguasaan Materi'],
            ['id_kriteria' => 27, 'nama_rubrik' => 'Kesesuaian AYK dengan kebutuhan yang telah ditetapkan'],
            ['id_kriteria' => 27, 'nama_rubrik' => 'Kesesuaian AYK dengan rancangan yang telah ditetapkan.'],
            ['id_kriteria' => 27, 'nama_rubrik' => 'Data Aplikasi:'],
            ['id_kriteria' => 27, 'nama_rubrik' => 'AYK sudah lolos tahapan pengujian'],
            ['id_kriteria' => 28, 'nama_rubrik' => 'Kontribusi pembuatan AYK'],
            ['id_kriteria' => 28, 'nama_rubrik' => 'Kelengkapan dokumentasi teknis aplikasi'],
            ['id_kriteria' => 28, 'nama_rubrik' => 'Kualitas analisis, desain, dan implementasi'],
            ['id_kriteria' => 29, 'nama_rubrik' => 'Penilaian analisis terhadap aplikasi hasil pengembangan berdasarkan:'],
            ['id_kriteria' => 30, 'nama_rubrik' => 'Penilaian kesimpulan hasil pengembangan aplikasi berdasarkan:'],
            ['id_kriteria' => 31, 'nama_rubrik' => 'Kelengkapan konten'],
            ['id_kriteria' => 31, 'nama_rubrik' => 'Kualitas konten'],
            ['id_kriteria' => 31, 'nama_rubrik' => 'Kesesuaian dan kualitas pustaka yang digunakan'],
            ['id_kriteria' => 32, 'nama_rubrik' => 'Penilaian Penguasaan Materi berdasarkan:'],
            ['id_kriteria' => 1, 'nama_rubrik' => 'Kejelasan kaitan antar bab/ sub kajian (hubungan sebab akibat/ reasoning, rasionalitas )'],
            ['id_kriteria' => 1, 'nama_rubrik' => 'Kesesuaian dan ketepatan penggunaan metodologi dan modelling tools.'],
            ['id_kriteria' => 1, 'nama_rubrik' => 'Kesesuaian studi pustaka dan daftar pustaka yang digunakan.'],
            ['id_kriteria' => 1, 'nama_rubrik' => "Tata tulis laporan\na) Dokumen dapat dibaca dengan baik.\nb) Sistematika Penulisan sesuai dengan penulisan TA.\nc) Kedalaman konten yang disajikan dapat terlihat"],
            ['id_kriteria' => 2, 'nama_rubrik' => "Materi Presentasi\na) Penguasaan domain TA\nb) Penguasaan pelaksanaan cara-cara/metoda pengembangan aplikasi\nc) Penguasaan pelaksanaan cara-cara /metoda penggunaan tools pengembangan aplikasi"],
            ['id_kriteria' => 2, 'nama_rubrik' => 'Kejelasan presentasi dan kemampuan membangkitkan minat pemirsa.'],
            ['id_kriteria' => 2, 'nama_rubrik' => 'Kebebasan dari catatan'],
            ['id_kriteria' => 3, 'nama_rubrik' => 'Tanya Jawab (Penguasaan materi terkait tugas yang dikerjakan).'],
            ['id_kriteria' => 4, 'nama_rubrik' => 'Prototipe yang dihasilkan'],
        ];

        foreach ($data as $item) {
            Rubrik::create($item);
        }
        
    }
}
