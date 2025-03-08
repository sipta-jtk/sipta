<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\MahasiswaDosenDokumen;

class MahasiswaDosenDokumenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        MahasiswaDosenDokumen::create([
            'nip' => '19731227 199903 1 003',
            'nim' => '221524059',
            'id_dokumen' => 1
        ]);

        MahasiswaDosenDokumen::create([
            'nip' => '19850210 201504 2 001',
            'nim' => '221524049',
            'id_dokumen' => 2
        ]);
    }
}
