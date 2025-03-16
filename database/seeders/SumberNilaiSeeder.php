<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Models\SumberNilai;

class SumberNilaiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (!Schema::hasTable('sumber_nilai')) {
            return;
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('sumber_nilai')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        SumberNilai::create([
            'id_komponen' => 1,
            'sumber' => 1
        ]);

        SumberNilai::create([
            'id_komponen' => 2,
            'sumber' => 2
        ]);
    }
}
