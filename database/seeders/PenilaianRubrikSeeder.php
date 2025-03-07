<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Models\PenilaianRubrik;

class PenilaianRubrikSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (!Schema::hasTable('penilaian_rubrik')) {
            return;
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('penilaian_rubrik')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        PenilaianRubrik::create([
            'nim' => '221524029', // Ubah Manual
            'nip' => '123456789', // Ubah Manual
            'id_rubrik' => '1', // Ubah Manual
            'nilai_rubrik' => '90', // Ubah Manual
            'detail_feedback' => 'Mantap Jiwa' // Ubah Manual
        ]);

        PenilaianRubrik::create([
            'nim' => '221524030', // Ubah Manual
            'nip' => '123456780', // Ubah Manual
            'id_rubrik' => '2', // Ubah Manual
            'nilai_rubrik' => '10', // Ubah Manual
            'detail_feedback' => 'Jos' // Ubah Manual
        ]);
    }
}
