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
            'nip' => '198502102015042001',
            'tahun_ajaran' => 2021,
            'status_form' => 'draft',
            'created_at' => now(),
            'updated_at' => now()
        ]);
        FormPenilaian::create([
            'nama_formulir_penilaian' => 'Form Penilaian 3',
            'nip' => '197201061999031002',
            'tahun_ajaran' => 2022,
            'status_form' => 'published',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        FormPenilaian::create([
            'nama_formulir_penilaian' => 'Form Penilaian 4',
            'nip' => '196810141993032002',
            'tahun_ajaran' => 2023,
            'status_form' => 'used',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        FormPenilaian::create([
            'nama_formulir_penilaian' => 'Form Penilaian 5',
            'nip' => '197604182001121004',
            'tahun_ajaran' => 2024,
            'status_form' => 'finished',
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
}
