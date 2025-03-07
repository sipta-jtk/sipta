<?php

namespace Database\Seeders;

use App\Models\Subkategori;
use DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Schema;

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

        DB::table('subkategori')->truncate();

        $data = [
            ['nama_subkategori' => 'Teknologi'],
            ['nama_subkategori' => 'Pendidikan'],
            ['nama_subkategori' => 'Kesehatan'],
            ['nama_subkategori' => 'Bisnis'],
            ['nama_subkategori' => 'Hiburan'],
        ];

        foreach ($data as $item) {
            Subkategori::create($item);
        }
    }
}
