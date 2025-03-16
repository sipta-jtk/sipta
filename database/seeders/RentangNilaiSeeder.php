<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Models\RentangNilai;
class RentangNilaiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (!Schema::hasTable('rentang_nilai')) {
            return;
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('rentang_nilai')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        RentangNilai::create([
            'id_nilai' => 'A',
            'batas_bawah' => 80,
            'batas_atas' => 100
        ]);

        RentangNilai::create([
            'id_nilai' => 'AB',
            'batas_bawah' => 75,
            'batas_atas' => 79
        ]);

        RentangNilai::create([
            'id_nilai' => 'B',
            'batas_bawah' => 70,
            'batas_atas' => 74
        ]);

        RentangNilai::create([
            'id_nilai' => 'BC',
            'batas_bawah' => 65,
            'batas_atas' => 69
        ]);

        RentangNilai::create([
            'id_nilai' => 'C',
            'batas_bawah' => 60,
            'batas_atas' => 64
        ]);

        RentangNilai::create([
            'id_nilai' => 'CD',
            'batas_bawah' => 55,
            'batas_atas' => 59
        ]);

        RentangNilai::create([
            'id_nilai' => 'D',
            'batas_bawah' => 40,
            'batas_atas' => 54
        ]);

        RentangNilai::create([
            'id_nilai' => 'E',
            'batas_bawah' => 0,
            'batas_atas' => 39
        ]);
    }
}
