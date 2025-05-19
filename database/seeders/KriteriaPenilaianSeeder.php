<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Models\KriteriaPenilaian;

class KriteriaPenilaianSeeder extends Seeder
{
    public function run(): void
    {
        if (!Schema::hasTable('kriteria_penilaian')) {
            return;
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('kriteria_penilaian')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $data = [
            ['id_kriteria' => 1, 'nama_kriteria' => 'Dokumen', 'bobot_kriteria' => 35, 'id_fta' => 4],
            ['id_kriteria' => 2, 'nama_kriteria' => 'Presentasi', 'bobot_kriteria' => 15, 'id_fta' => 4],
            ['id_kriteria' => 3, 'nama_kriteria' => 'Tanya Jawab (penguasaan materi terkait tugas yang dikerjakan)', 'bobot_kriteria' => 35, 'id_fta' => 4],
            ['id_kriteria' => 4, 'nama_kriteria' => 'Prototipe yang dihasilkan', 'bobot_kriteria' => 15, 'id_fta' => 4],
            ['id_kriteria' => 5, 'nama_kriteria' => 'Kejelasan isi dokumen', 'bobot_kriteria' => 35, 'id_fta' => 6],
            ['id_kriteria' => 6, 'nama_kriteria' => 'Presentasi', 'bobot_kriteria' => 10, 'id_fta' => 6],
            ['id_kriteria' => 7, 'nama_kriteria' => 'Tanya Jawab (penguasaan materi terkait tugas yang dikerjakan)', 'bobot_kriteria' => 30, 'id_fta' => 6],
            ['id_kriteria' => 8, 'nama_kriteria' => 'Produk perangkat lunak', 'bobot_kriteria' => 25, 'id_fta' => 6],
            ['id_kriteria' => 9, 'nama_kriteria' => 'Dokumen', 'bobot_kriteria' => 30, 'id_fta' => 8],
            ['id_kriteria' => 10, 'nama_kriteria' => 'Produk Perangkat Lunak / Hasil Penelitian', 'bobot_kriteria' => 30, 'id_fta' => 8],
            ['id_kriteria' => 11, 'nama_kriteria' => 'Softskill', 'bobot_kriteria' => 20, 'id_fta' => 8],
            ['id_kriteria' => 12, 'nama_kriteria' => 'Hardskill', 'bobot_kriteria' => 20, 'id_fta' => 8],
            ['id_kriteria' => 13, 'nama_kriteria' => 'Aplikasi Pendukung Eksperimen (APE)', 'bobot_kriteria' => 20, 'id_fta' => 13],
            ['id_kriteria' => 14, 'nama_kriteria' => 'Eksperimen', 'bobot_kriteria' => 20, 'id_fta' => 13],
            ['id_kriteria' => 15, 'nama_kriteria' => 'Dokumen TA', 'bobot_kriteria' => 30, 'id_fta' => 13],
            ['id_kriteria' => 16, 'nama_kriteria' => 'Penguasaan Materi TA', 'bobot_kriteria' => 30, 'id_fta' => 13],
            ['id_kriteria' => 17, 'nama_kriteria' => 'Aplikasi yang dikembangkan (AYK) - Penilaian Produk:', 'bobot_kriteria' => 20, 'id_fta' => 14],
            ['id_kriteria' => 18, 'nama_kriteria' => 'Pengembangan Aplikasi - Penilaian Proses Pengembangan Produk Aplikasi', 'bobot_kriteria' => 30, 'id_fta' => 14],
            ['id_kriteria' => 19, 'nama_kriteria' => 'Dokumen TA', 'bobot_kriteria' => 25, 'id_fta' => 14],
            ['id_kriteria' => 20, 'nama_kriteria' => 'Penguasaan Materi TA', 'bobot_kriteria' => 25, 'id_fta' => 14],
            ['id_kriteria' => 21, 'nama_kriteria' => 'Aplikasi Pendukung Eksperimen (APE)', 'bobot_kriteria' => 15, 'id_fta' => 16],
            ['id_kriteria' => 22, 'nama_kriteria' => 'Eksperimen', 'bobot_kriteria' => 20, 'id_fta' => 16],
            ['id_kriteria' => 23, 'nama_kriteria' => 'Kesimpulan Hasil Eksperimen', 'bobot_kriteria' => 15, 'id_fta' => 16],
            ['id_kriteria' => 24, 'nama_kriteria' => 'Kesimpulan Hasil Penelitian', 'bobot_kriteria' => 15, 'id_fta' => 16],
            ['id_kriteria' => 25, 'nama_kriteria' => 'Dokumen TA', 'bobot_kriteria' => 15, 'id_fta' => 16],
            ['id_kriteria' => 26, 'nama_kriteria' => 'Penguasaan Materi TA', 'bobot_kriteria' => 20, 'id_fta' => 16],
            ['id_kriteria' => 27, 'nama_kriteria' => 'Aplikasi yang dikembangkan (AYK) - Penilaian Produk:', 'bobot_kriteria' => 20, 'id_fta' => 17],
            ['id_kriteria' => 28, 'nama_kriteria' => 'Pengembangan Aplikasi - Penilaian Proses Pengembangan Produk Aplikasi', 'bobot_kriteria' => 15, 'id_fta' => 17],
            ['id_kriteria' => 29, 'nama_kriteria' => 'Analisis terhadap Aplikasi Hasil Pengembangan (AHP)', 'bobot_kriteria' => 15, 'id_fta' => 17],
            ['id_kriteria' => 30, 'nama_kriteria' => 'Kesimpulan Hasil Pengembangan Aplikasi', 'bobot_kriteria' => 15, 'id_fta' => 17],
            ['id_kriteria' => 31, 'nama_kriteria' => 'Dokumen TA', 'bobot_kriteria' => 15, 'id_fta' => 17],
            ['id_kriteria' => 32, 'nama_kriteria' => 'Penguasaan Materi TA', 'bobot_kriteria' => 20, 'id_fta' => 17],
            ['id_kriteria' => 33, 'nama_kriteria' => 'Dokumen', 'bobot_kriteria' => 30, 'id_fta' => 19],
            ['id_kriteria' => 34, 'nama_kriteria' => 'Produk Perangkat Lunak / Hasil Penelitian', 'bobot_kriteria' => 30, 'id_fta' => 19],
            ['id_kriteria' => 35, 'nama_kriteria' => 'Softskill', 'bobot_kriteria' => 20, 'id_fta' => 19],
            ['id_kriteria' => 36, 'nama_kriteria' => 'Hardskill', 'bobot_kriteria' => 20, 'id_fta' => 19],
        ];

        foreach ($data as $item) {
            KriteriaPenilaian::create($item);
        }
    }
}
