<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\KriteriaPenilaian;

class KriteriaPenilaianSeeder extends Seeder
{
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('kriteria_penilaian')->truncate(); // Membersihkan tabel sebelum seeding
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $data = [
            [
                'kode_fta' => 1,
                'nama_kriteria' => 'Keterbacaan Kode',
                'bobot_kriteria' => 5
            ],
            [
                'kode_fta' => 1,
                'nama_kriteria' => 'Penggunaan Rujukan yang Mutakhir',
                'bobot_kriteria' => 10
            ],
        ];

        foreach ($data as $item) {
            KriteriaPenilaian::create($item);
        }
    }
}