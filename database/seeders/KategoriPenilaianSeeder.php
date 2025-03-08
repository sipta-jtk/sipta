<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\KategoriPenilaian;

class KategoriPenilaianSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Schema::disableForeignKeyConstraints();
        DB::table('kategori_penilaian')->truncate();
        // Form penilaian 1: Seminar Proposal Tugas Akhir
        KategoriPenilaian::create([
            'id_form_penilaian' => 1,
            'nama_kategori' => 'Kualitas Proposal',
            'bobot_kategori' => 25,
        ]);

        KategoriPenilaian::create([
            'id_form_penilaian' => 1,
            'nama_kategori' => 'Metodologi Penelitian',
            'bobot_kategori' => 25,
        ]);

        KategoriPenilaian::create([
            'id_form_penilaian' => 1,
            'nama_kategori' => 'Tinjauan Pustaka',
            'bobot_kategori' => 20,
        ]);

        KategoriPenilaian::create([
            'id_form_penilaian' => 1,
            'nama_kategori' => 'Presentasi',
            'bobot_kategori' => 15,
        ]);

        KategoriPenilaian::create([
            'id_form_penilaian' => 1,
            'nama_kategori' => 'Penguasaan Materi',
            'bobot_kategori' => 15,
        ]);

        // Form penilaian 2: Sidang Tugas Akhir
        KategoriPenilaian::create([
            'id_form_penilaian' => 2,
            'nama_kategori' => 'Hasil Implementasi',
            'bobot_kategori' => 25,
        ]);

        KategoriPenilaian::create([
            'id_form_penilaian' => 2,
            'nama_kategori' => 'Analisis dan Pembahasan',
            'bobot_kategori' => 20,
        ]);

        KategoriPenilaian::create([
            'id_form_penilaian' => 2,
            'nama_kategori' => 'Kualitas Penulisan',
            'bobot_kategori' => 15,
        ]);

        KategoriPenilaian::create([
            'id_form_penilaian' => 2,
            'nama_kategori' => 'Presentasi Hasil',
            'bobot_kategori' => 15,
        ]);

        KategoriPenilaian::create([
            'id_form_penilaian' => 2,
            'nama_kategori' => 'Kemampuan Menjawab Pertanyaan',
            'bobot_kategori' => 25,
        ]);

        // Schema::enableForeignKeyConstraints();
    }
}
