<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Ruangan;
use Illuminate\Support\Facades\DB;
class RuanganSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('ruangan')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

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
            'status_ruangan' => 'tidak_tersedia',
            'kode_gedung' => 'A',
            'link_ruangan' => 'A102'
        ]);
    }
}
