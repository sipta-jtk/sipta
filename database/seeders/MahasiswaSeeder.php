<?php

namespace Database\Seeders;

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
        Mahasiswa::create([
            'nim' => '221524020',
            'tahun_masuk' => 2022,
            'kelas' => 'D4A',
            'id_prodi' => 1,
            'status_ta' => 'mahasiswa_ta',
            'nilai_akhir_ta' => 85,
            'id_kota' => 10
        ]);

        Mahasiswa::create([
            'nim' => '221524021',
            'tahun_masuk' => 2022,
            'kelas' => 'D4B',
            'id_prodi' => 1,
            'status_ta' => 'mahasiswa_ta',
            'nilai_akhir_ta' => 90,
            'id_kota' => 12
        ]);
    }
}
