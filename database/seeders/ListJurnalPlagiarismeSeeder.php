<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\ListJurnalPlagiarisme;

class ListJurnalPlagiarismeSeeder extends Seeder
{
    public function run()
    {
        // Matikan sementara foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('list_jurnal_plagiarisme')->truncate(); // Kosongkan tabel
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Data dummy untuk seed
        ListJurnalPlagiarisme::create([
            'link_jurnal' => 'https://example.com/jurnal1',
            'judul' => 'Analisis Plagiarisme dalam Jurnal A',
            'persentase_kemunculan' => 23.5
        ]);

        ListJurnalPlagiarisme::create([
            'link_jurnal' => 'https://example.com/jurnal2',
            'judul' => 'Studi Kasus Plagiarisme di Jurnal B',
            'persentase_kemunculan' => 45.2
        ]);

        ListJurnalPlagiarisme::create([
            'link_jurnal' => 'https://example.com/jurnal3',
            'judul' => 'Deteksi Plagiarisme dengan Algoritma C',
            'persentase_kemunculan' => 12.8
        ]);
    }
}
