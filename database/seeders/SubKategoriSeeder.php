<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Models\Subkategori;

class SubKategoriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (!Schema::hasTable('subkategori')) {
            return;
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('subkategori')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $data = [
            [
                'id_subkategori' => 1, 
                'nama_subkategori' => 'Pendidikan'
            ],
            [
                'id_subkategori' => 2, 
                'nama_subkategori' => 'Kesehatan'
            ],
            [
                'id_subkategori' => 3, 
                'nama_subkategori' => 'Teknologi'
            ],
            [
                'id_subkategori' => 4, 
                'nama_subkategori' => 'Hiburan'
            ],
            [
                'id_subkategori' => 5, 
                'nama_subkategori' => 'Bisnis'
            ],
            [
                'id_subkategori' => 6, 
                'nama_subkategori' => 'Layanan'
            ],
            [
                'id_subkategori' => 7, 
                'nama_subkategori' => 'Ekonomi'
            ],
            [
                'id_subkategori' => 8, 
                'nama_subkategori' => 'Keamanan'
            ],
            [
                'id_subkategori' => 9, 
                'nama_subkategori' => 'Data'
            ]
        ];

        foreach ($data as $item) {
            Subkategori::create($item);
        }
    }
}
