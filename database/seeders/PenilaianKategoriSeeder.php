<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Models\PenilaianKategori;

class PenilaianKategoriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (!Schema::hasTable('penilaian_kategori')) {
            return;
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('penilaian_kategori')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        PenilaianKategori::create([
            'nim' => '2201234567', // Ubah Manual
            'id_kategori' => '1', // Ubah Manual
            'nilai_kategori' => '85.5' // Ubah Manual
        ]);

        PenilaianKategori::create([
            'nim' => '2201234568',
            'id_kategori' => '2',
            'nilai_kategori' => '75.5'
        ]);
    }
}
