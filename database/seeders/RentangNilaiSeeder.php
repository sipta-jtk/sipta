<?php

namespace Database\Seeders;

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

        $data = [
            [
                'id_nilai' => 'A',
                'batas_bawah' => 80.00,
                'batas_atas' => 100.00
            ],
            [
                'id_nilai' => 'AB',
                'batas_bawah' => 75.00,
                'batas_atas' => 79.99
            ],
            [
                'id_nilai' => 'B',
                'batas_bawah' => 70.00,
                'batas_atas' => 74.99
            ],
            [
                'id_nilai' => 'BC',
                'batas_bawah' => 65.00,
                'batas_atas' => 69.99
            ],
            [
                'id_nilai' => 'C',
                'batas_bawah' => 60.00,
                'batas_atas' => 64.99
            ],
            [
                'id_nilai' => 'CD',
                'batas_bawah' => 0.00,
                'batas_atas' => 59.99
            ]
        ];

        foreach ($data as $item) {
            RentangNilai::create($item);
        }
    }
}
