<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Gedung;

class GedungSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Gedung::create([
            'kode_gedung' => 'A',
            'nama_gedung' => 'Gedung A',
        ]);

        Gedung::create([
            'kode_gedung' => 'B',
            'nama_gedung' => 'Gedung B',
        ]);
    }
}
