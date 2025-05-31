<?php

namespace Database\Seeders;

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
        // DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        // DB::table('nilai_kriteria')->truncate();

        // $data = [
        //     [
        //         'nim' => '221524036',
        //         'nip' => '196904041998031001',
        //         'id_kriteria' => 2,
        //         'nilai_kriteria' => 78.00,
        //         'created_at' => Carbon::now(),
        //         'updated_at' => Carbon::now(),
        //     ],
        //     [
        //         'nim' => '221524036',
        //         'nip' => '196904041998031002',
        //         'id_kriteria' => 3,
        //         'nilai_kriteria' => 76.54,
        //         'created_at' => Carbon::now(),
        //         'updated_at' => Carbon::now(),
        //     ],
        //     [
        //         'nim' => '221524036',
        //         'nip' => '196904041998031003',
        //         'id_kriteria' => 4,
        //         'nilai_kriteria' => 77.00,
        //         'created_at' => Carbon::now(),
        //         'updated_at' => Carbon::now(),
        //     ],
        //     [
        //         'nim' => '221524039',
        //         'nip' => '196904041998031004',
        //         'id_kriteria' => 2,
        //         'nilai_kriteria' => 78.55,
        //         'created_at' => Carbon::now(),
        //         'updated_at' => Carbon::now(),
        //     ],
        //     [
        //         'nim' => '221524039',
        //         'nip' => '196904041998031002',
        //         'id_kriteria' => 3,
        //         'nilai_kriteria' => 76.54,
        //         'created_at' => Carbon::now(),
        //         'updated_at' => Carbon::now(),
        //     ],
        //     [
        //         'nim' => '221524039',
        //         'nip' => '196904041998031003',
        //         'id_kriteria' => 4,
        //         'nilai_kriteria' => 78.98,
        //         'created_at' => Carbon::now(),
        //         'updated_at' => Carbon::now(),
        //     ],
        // ];

        // foreach ($data as $item) {
        //     NilaiKriteria::create($item);
        // }

        // DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
