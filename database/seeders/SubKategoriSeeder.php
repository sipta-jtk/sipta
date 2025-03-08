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
