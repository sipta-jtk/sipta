<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\FormPenilaian;

class FormPenilaianSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        FormPenilaian::create([
            'nama_formulir_penilaian' => 'Form Penilaian 1',
            'nip' => '197312271999031003',
            'tahun_ajaran' => 2020,
            'status_form' => 'pending',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        FormPenilaian::create([
            'nama_formulir_penilaian' => 'Form Penilaian 2',
            'nip' => '197312271999031003',
            'tahun_ajaran' => 2021,
            'status_form' => 'draft',
            'created_at' => now(),
            'updated_at' => now()
        ]);
        FormPenilaian::create([
            'nama_formulir_penilaian' => 'Form Penilaian 3',
            'nip' => '197312271999031003',
            'tahun_ajaran' => 2022,
            'status_form' => 'published',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        FormPenilaian::create([
            'nama_formulir_penilaian' => 'Form Penilaian 4',
            'nip' => '1234567890',
            'tahun_ajaran' => 2023,
            'status_form' => 'used',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        FormPenilaian::create([
            'nama_formulir_penilaian' => 'Form Penilaian 5',
            'nip' => '1234567890',
            'tahun_ajaran' => 2024,
            'status_form' => 'finished',
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
}
