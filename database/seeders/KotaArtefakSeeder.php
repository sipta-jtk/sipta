<?php

namespace Database\Seeders;

use App\Models\KotaArtefak;
use Illuminate\Database\Seeder;

class KotaArtefakSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

    
    public function run(): void
    {

        KotaArtefak::create([
            'id_kota' => '1',
            'id_artefak' => '8',
            'file_pengumpulan' => 'garerg',
            'waktu_pengumpulan' => '2025-03-24 09:36:17',
        ]);

        KotaArtefak::create([
            'id_kota' => '1',
            'id_artefak' => '9',
            'file_pengumpulan' => 'gareg',
            'waktu_pengumpulan' => '2025-03-24 09:36:17',
        ]);

        KotaArtefak::create([
            'id_kota' => '1',
            'id_artefak' => '10',
            'file_pengumpulan' => 'gagareg',
            'waktu_pengumpulan' => '2025-03-24 09:36:17',
        ]);

        KotaArtefak::create([
            'id_kota' => '1',
            'id_artefak' => '11',
            'file_pengumpulan' => 'gfawfareg',
            'waktu_pengumpulan' => '2025-03-24 09:36:17',
        ]);

        KotaArtefak::create([
            'id_kota' => '1',
            'id_artefak' => '12',
            'file_pengumpulan' => 'gargsfeg',
            'waktu_pengumpulan' => '2025-03-24 09:36:17',
        ]);

        KotaArtefak::create([
            'id_kota' => '1',
            'id_artefak' => '13',
            'file_pengumpulan' => 'gaawfareg',
            'waktu_pengumpulan' => '2025-03-24 09:36:17',
        ]);

    }
}
