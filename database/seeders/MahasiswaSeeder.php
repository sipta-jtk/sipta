<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Mahasiswa;

class MahasiswaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
        ]);

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('mahasiswa')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        Mahasiswa::create([
            'nim' => '2201234567',
            'tahun_masuk' => 2022,
            'kelas' => 'TI4A',
            // 'id_prodi' => 1,
            'status_ta' => 'mahasiswa_ta',
            'nilai_akhir_ta' => 85,
            // 'id_kota' => 10
        ]);

        Mahasiswa::create([
            'nim' => '2201234568',
            'tahun_masuk' => 2022,
            'kelas' => 'TI4B',
            // 'id_prodi' => 1,
            'status_ta' => 'mahasiswa_ta',
            'nilai_akhir_ta' => 90,
            // 'id_kota' => 12
        ]);
    }
}