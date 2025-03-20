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
                'id_kriteria' => 1,
                'id_fta' => 2,
                'nama_kriteria' => 'Kejelasan Isi Dokumen',
                'bobot_kriteria' => 40
            ],
            [
                'id_kriteria' => 2,
                'id_fta' => 2,
                'nama_kriteria' => 'Presentasi',
                'bobot_kriteria' => 20
            ],
            [
                'id_kriteria' => 3,
                'id_fta' => 2,
                'nama_kriteria' => 'Tanya Jawab',
                'bobot_kriteria' => 40
            ],
            [
                'id_kriteria' => 4,
                'id_fta' => 4,
                'nama_kriteria' => 'Dokumen',
                'bobot_kriteria' => 35
            ],
            [
                'id_kriteria' => 5,
                'id_fta' => 4,
                'nama_kriteria' => 'Presentasi',
                'bobot_kriteria' => 15
            ],
            [
                'id_kriteria' => 6,
                'id_fta' => 4,
                'nama_kriteria' => 'Tanya Jawab',
                'bobot_kriteria' => 35
            ],
            [
                'id_kriteria' => 7,
                'id_fta' => 4,
                'nama_kriteria' => 'Prototype yg dihasilkan',
                'bobot_kriteria' => 15
            ],
            [
                'id_kriteria' => 8,
                'id_fta' => 6,
                'nama_kriteria' => 'Kejelasan Isi Dokumen',
                'bobot_kriteria' => 35
            ],
            [
                'id_kriteria' => 9,
                'id_fta' => 6,
                'nama_kriteria' => 'Presentasi',
                'bobot_kriteria' => 10
            ],
            [
                'id_kriteria' => 10,
                'id_fta' => 6,
                'nama_kriteria' => 'Tanya Jawab',
                'bobot_kriteria' => 30
            ],
            [
                'id_kriteria' => 11,
                'id_fta' => 6,
                'nama_kriteria' => 'Produk Perangkat Lunak',
                'bobot_kriteria' => 25
            ],
            [
                'id_kriteria' => 12,
                'id_fta' => 8,
                'nama_kriteria' => 'Dokumen',
                'bobot_kriteria' => 30
            ],
            [
                'id_kriteria' => 13,
                'id_fta' => 8,
                'nama_kriteria' => 'Produk Perangkat Lunak/Hasil Penelitian',
                'bobot_kriteria' => 30
            ],
            [
                'id_kriteria' => 14,
                'id_fta' => 8,
                'nama_kriteria' => 'Softskill',
                'bobot_kriteria' => 20
            ],
            [
                'id_kriteria' => 15,
                'id_fta' => 8,
                'nama_kriteria' => 'Hardskill',
                'bobot_kriteria' => 20
            ]
        ];

        foreach ($data as $item) {
            KriteriaPenilaian::create($item);
        }
    }
}
