<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;
use App\Models\NilaiKriteria;

class NilaiKriteriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('nilai_kriteria')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        DB::table('nilai_kriteria')->insert([
            [
                'nim' => '221524059',
                'nip' => '197312271999031003',
                'id_kriteria' => 1,
                'nilai_kriteria' => 80.5,
                'status_penilaian' => 'dipublikasikan',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'nim' => '221524049',
                'nip' => '198502102015042001',
                'id_kriteria' => 2,
                'nilai_kriteria' => 90.5,
                'status_penilaian' => 'dipublikasikan',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
        ]);
    }
}
