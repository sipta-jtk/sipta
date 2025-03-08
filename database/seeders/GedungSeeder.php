<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Gedung;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class GedungSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Nonaktifkan foreign key checks sementara
        Schema::disableForeignKeyConstraints();

        // Menghapus data yang sudah ada sebelumnya
        DB::table('gedung')->truncate();

        // Menambahkan data baru
        Gedung::create([
            'kode_gedung' => 'A',
            'nama_gedung' => 'Gedung A',
        ]);

        Gedung::create([
            'kode_gedung' => 'B',
            'nama_gedung' => 'Gedung B',
        ]);

        // Mengaktifkan kembali foreign key checks
        Schema::enableForeignKeyConstraints();
    }
}
