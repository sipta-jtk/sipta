<?php

namespace Database\Seeders;

use App\Models\MahasiswaDosenDokumen;
use DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MahasiswaDosenDokumenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->call([
            MahasiswaSeeder::class,
            DosenSeeder::class,
        ]);

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('mahasiswa_dosen_dokumen')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        MahasiswaDosenDokumen::create([
            'nip' => '197312271999031003',
            'nim' => '221524059',
            'id_dokumen' => 1
        ]);

        MahasiswaDosenDokumen::create([
            'nip' => '198502102015042001',
            'nim' => '221524049',
            'id_dokumen' => 2
        ]);
    }
}
