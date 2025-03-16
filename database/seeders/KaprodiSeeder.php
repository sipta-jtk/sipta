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
            'nip' => '197312271999031003',
            'id_prodi' => 1
        ]);

        Kaprodi::create([
            'nip' => '198502102015042001',
            'id_prodi' => 2
        ]);
    }
}
