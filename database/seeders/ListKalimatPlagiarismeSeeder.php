<?php

namespace Database\Seeders; 

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\ListKalimatPlagiarisme;

class ListKalimatPlagiarismeSeeder extends Seeder
{
    public function run()
    {
        // Matikan sementara foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('list_kalimat_plagiarisme')->truncate(); // Kosongkan tabel
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Data dummy untuk seed
        ListKalimatPlagiarisme::create([
            'id_dokumen' => 1,
            'id_jurnal' => 1,
            'kalimat_plagiat' => 'Penelitian ini menunjukkan bahwa metode A lebih efektif dalam kasus X.'
        ]);

        ListKalimatPlagiarisme::create([
            'id_dokumen' => 2,
            'id_jurnal' => 1,
            'kalimat_plagiat' => 'Hasil eksperimen membuktikan adanya korelasi antara faktor Y dan Z.'
        ]);

        ListKalimatPlagiarisme::create([
            'id_dokumen' => 3,
            'id_jurnal' => 2,
            'kalimat_plagiat' => 'Dalam studi ini, ditemukan bahwa penggunaan algoritma B meningkatkan akurasi deteksi.'
        ]);
    }
}