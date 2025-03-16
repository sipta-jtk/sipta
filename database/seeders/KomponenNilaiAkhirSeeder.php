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
                'nama_komponen' => 'uts',
                'bobot_komponen' => 30,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nama_komponen' => 'uas',
                'bobot_komponen' => 40,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nama_komponen' => 'lain_lain',
                'bobot_komponen' => 30,
                'created_at' => now(),
                'updated_at' => now()
            ],
        ];

        foreach ($data as $item) {
            KomponenNilaiAkhir::create($item);
        }
    }
}
