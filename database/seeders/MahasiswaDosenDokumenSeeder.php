<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Mahasiswa;

class MahasiswaDosenDokumenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        MahasiswaDosenDokumen::create([
            'nip' => '1987654321',
            'nim' => '2201234567',
            'id_dokumen' => 1
        ]);

        MahasiswaDosenDokumen::create([
            'nip' => '1987654322',
            'nim' => '2201234568',
            'id_dokumen' => 2
        ]);
    }
}
