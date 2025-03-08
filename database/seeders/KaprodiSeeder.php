<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Kaprodi;

class KaprodiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        
        Kaprodi::create([
            'nip' => '19731227 199903 1 003',
            'id_prodi' => 1
        ]);

        Kaprodi::create([
            'nip' => '19850210 201504 2 001',
            'id_prodi' => 2
        ]);
    }
}
