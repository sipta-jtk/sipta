<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\NilaiKategori;

class NilaiKategoriSeeder extends Seeder
{
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('nilai_kategori')->truncate(); // Membersihkan tabel sebelum seeding
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $data = [
            [
                'nim' => '197312271999031003',
                'nip' => '221524059',
                'id_kategori' => 1,
                'nilai' => 20.5,
            ],
            [
                'nim' => '198502102015042001',
                'nip' => '21524049',
                'id_kategori' => 2,
                'nilai' => 50.5,
            ],
        ];

        foreach ($data as $item) {
            NilaiKategori::create($item);
        }
    }
}