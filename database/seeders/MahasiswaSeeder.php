<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Mahasiswa;

class MahasiswaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('mahasiswa')->truncate();

        $data = [
            [
                'nim' => '221524034',
                'tahun_masuk' => 2022,
                'kelas' => '3A',
                'id_prodi' => 1,
                'status_ta' => 'mahasiswa_non_ta',
                'id_kota' => null
            ],
            [
                'nim' => '221524035',
                'tahun_masuk' => 2022,
                'kelas' => '3A',
                'id_prodi' => 1,
                'status_ta' => 'mahasiswa_non_ta',
                'id_kota' => null
            ],
            [
                'nim' => '221524036',
                'tahun_masuk' => 2022,
                'kelas' => '3A',
                'id_prodi' => 2,
                'status_ta' => 'mahasiswa_ta',
                'id_kota' => 1
            ],
            [
                'nim' => '221524037',
                'tahun_masuk' => 2022,
                'kelas' => '3A',
                'id_prodi' => 2,
                'status_ta' => 'mahasiswa_ta',
                'id_kota' => 1
            ],
        ];

        foreach ($data as $item) {
            Mahasiswa::create($item);
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}