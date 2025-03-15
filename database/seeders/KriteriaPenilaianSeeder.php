<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Models\KriteriaPenilaian;

class KriteriaPenilaianSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (!Schema::hasTable('kriteria_penilaian')) {
            return;
        }

        // Menonaktifkan foreign key checks sementara
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('kriteria_penilaian')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $data = [
            [
                'kode_fta' => 1,
                'nama_kriteria' => 'Relevansi Topik',
                'bobot_kriteria' => 20,
            ],
            [
                'kode_fta' => 1,
                'nama_kriteria' => 'Kelengkapan Data',
                'bobot_kriteria' => 25,
            ],
            [
                'kode_fta' => 2,
                'nama_kriteria' => 'Kualitas Analisis',
                'bobot_kriteria' => 30,
            ],
            [
                'kode_fta' => 2,
                'nama_kriteria' => 'Kesesuaian Metodologi',
                'bobot_kriteria' => 25,
            ],
            [
                'kode_fta' => 3,
                'nama_kriteria' => 'Kemampuan Presentasi',
                'bobot_kriteria' => 15,
            ],
            [
                'kode_fta' => 3,
                'nama_kriteria' => 'Kejelasan Materi',
                'bobot_kriteria' => 20,
            ],
        ];

        foreach ($data as $item) {
            KriteriaPenilaian::create($item);
        }
    }
}
