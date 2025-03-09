<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Ruangan;

class RuanganSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Ruangan::create([
            'kode_ruangan' => 'A101',
            'nama_ruangan' => 'Ruang A101',
            'status_ruangan' => 'tersedia',
            'kode_gedung' => 'A',
            'link_ruangan' => 'A101'
        ]);

        Ruangan::create([
            'kode_ruangan' => 'A102',
            'nama_ruangan' => 'Ruang A102',
            'status_ruangan' => 'tidak tersedia',
            'kode_gedung' => 'A',
            'link_ruangan' => 'A102'
        ]);
    }
}
