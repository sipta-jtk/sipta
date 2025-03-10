<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Mahasiswa;

class MahasiswaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Menonaktifkan foreign key checks sementara
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Menghapus data yang ada di tabel mahasiswa
        DB::table('mahasiswa')->truncate();

        // Menambahkan data mahasiswa
        Mahasiswa::create([
            'nim' => '221524059',
            'tahun_masuk' => 2022,
            'kelas' => 'D4A',
            'id_prodi' => 1,
            'status_ta' => 'mahasiswa_ta',
            'nilai_akhir_ta' => 85,
            'id_kota' => 1
        ]);

        Mahasiswa::create([
            'nim' => '221524049',
            'tahun_masuk' => 2022,
            'kelas' => 'D4B',
            'id_prodi' => 1,
            'status_ta' => 'mahasiswa_ta',
            'nilai_akhir_ta' => 90,
            'id_kota' => 2
        ]);

        // Mengaktifkan kembali foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
