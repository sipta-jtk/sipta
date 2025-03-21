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

        Rubrik::create([
            'id_kriteria' => 4,
            'nama_rubrik' => 'Kejelasan kaitan antar bab/ sub kajian (hubungan sebab akibat/ reasoning, rasionalitas )'
        ]);

        Rubrik::create([
            'id_kriteria' => 4,
            'nama_rubrik' => 'Kesesuaian dan ketepatan penggunaan metodologi dan modelling tools.'
        ]);

        Rubrik::create([
            'id_kriteria' => 4,
            'nama_rubrik' => 'Kesesuaian studi pustaka dan daftar pustaka yang digunakan.'
        ]);

        Rubrik::create([
            'id_kriteria' => 4,
            'nama_rubrik' => 'Tata tulis laporan
a. Dokumen dapat dibaca dengan baik.
b. Sistematika Penulisan sesuai dengan penulisan TA.
c. Kedalaman konten yang disajikan dapat terlihat'
        ]);

        Rubrik::create([
            'id_kriteria' => 5,
            'nama_rubrik' => 'Materi Presentasi
a. Penguasaan domain TA
b. Penguasaan pelaksanaan cara-cara/metoda pengembangan aplikasi
c. Penguasaan pelaksanaan cara-cara /metoda penggunaan tools pengembangan aplikasi'
        ]);

        Rubrik::create([
            'id_kriteria' => 5,
            'nama_rubrik' => 'Kejelasan presentasi dan kemampuan membangkitkan minat pemirsa.'
        ]);

        Rubrik::create([
            'id_kriteria' => 5,
            'nama_rubrik' => 'Kebebasan dari catatan'
        ]);

        Rubrik::create([
            'id_kriteria' => 6,
            'nama_rubrik' => 'Tanya Jawab (Penguasaan materi terkait tugas yang dikerjakan).'
        ]);

        Rubrik::create([
            'id_kriteria' => 7,
            'nama_rubrik' => 'Prototipe yang dihasilkan'
        ]);
    }
}
