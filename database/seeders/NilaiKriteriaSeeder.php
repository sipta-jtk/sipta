<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\NilaiKriteria;

class NilaiKriteriaSeeder extends Seeder
{
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('nilai_kriteria')->truncate(); // Membersihkan tabel sebelum seeding
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $data = [
            [
                'nim' => '197312271999031003',
                'nip' => '221524059',
                'id_kriteria' => 1,
                'nilai_kriteria' => 20.5,
                'status_penilaian' => 'draf'
            ],
            [
                'nim' => '198502102015042001',
                'nip' => '221524049',
                'id_kriteria' => 2,
                'nilai_kriteria' => 20.5,
                'status_penilaian' => 'dipublikasikan'
            ],
        ];

        foreach ($data as $item) {
            NilaiKriteria::create($item);
        }
    }
}