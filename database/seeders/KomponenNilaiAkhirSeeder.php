<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Models\KomponenNilaiAkhir;

class KomponenNilaiAkhirSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (!Schema::hasTable('komponen_nilai_akhir')) {
            return;
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('komponen_nilai_akhir')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $data = [
            [
                'nama_komponen' => 'UTS (Teori)',
                'bobot_komponen' => 40,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nama_komponen' => 'Praktikum ETS',
                'bobot_komponen' => 40,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nama_komponen' => 'Lain - lain ETS',
                'bobot_komponen' => 20,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nama_komponen' => 'UAS (Teori)',
                'bobot_komponen' => 0,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nama_komponen' => 'Praktikum EAS',
                'bobot_komponen' => 0,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nama_komponen' => 'Lain - lain EAS',
                'bobot_komponen' => 0,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nama_komponen' => 'PjBL',
                'bobot_komponen' => 0,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nama_komponen' => 'Partisipatif',
                'bobot_komponen' => 0,
                'created_at' => now(),
                'updated_at' => now()
            ],
        ];

        foreach ($data as $item) {
            KomponenNilaiAkhir::create($item);
        }
    }
}
