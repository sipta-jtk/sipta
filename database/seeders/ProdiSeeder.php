<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Prodi;

class ProdiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Prodi::create([
            'nama_prodi' => 'Teknik Informatika',
            'maksimal_anggota_kota' => 3
        ]);

        Prodi::create([
            'nama_prodi' => 'Teknik Elektro',
            'maksimal_anggota_kota' => 3
        ]);
    }
}
