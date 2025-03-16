<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Models\FormPenilaian;

class FormPenilaianSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (!Schema::hasTable('form_penilaian')) {
            return;
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('form_penilaian')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $data = [
            [
                'nama_fta' => 'Form Penilaian 1',
                'id_prodi' => 1, 
                'jenis_form' => 'penilaian',
                'tanggal_tenggat_pengisian' => '2024-06-30',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nama_fta' => 'Form Penilaian 2',
                'id_prodi' => 2,
                'jenis_form' => 'penilaian',
                'tanggal_tenggat_pengisian' => '2024-07-15',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nama_fta' => 'Form Penilaian 3',
                'id_prodi' => 1,
                'jenis_form' => 'feedback',
                'tanggal_tenggat_pengisian' => '2024-08-01',
                'created_at' => now(),
                'updated_at' => now()
            ],
        ];

        foreach ($data as $item) {
            FormPenilaian::create($item);
        }
    }
}
