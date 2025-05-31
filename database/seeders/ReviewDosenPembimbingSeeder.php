<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Models\ReviewDosenPembimbing;

class ReviewDosenPembimbingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // if (!Schema::hasTable('review_dosen_pembimbing')) {
        //     return;
        // }

        // DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        // DB::table('review_dosen_pembimbing')->truncate();
        // DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // ReviewDosenPembimbing::create([
        //     'review' => 'Untuk penjelasan lebih baik dikutip saja, untuk mengurangi plagiarismenya',
        //     'id_dokumen' => 1,
        //     'nip' => '196101141992021001',
        //     'created_at' => '2024-09-01 00:00:00',
        //     'updated_at' => '2024-09-01 00:00:00'

        // ]);

        // ReviewDosenPembimbing::create([
        //     'review' => 'Gunakan variasi kalimat dan sumber referensi yang lebih luas agar tulisan memiliki keunikan yang lebih tinggi.',
        //     'id_dokumen' => 2,
        //     'nip' => '198009162009122001',
        //     'created_at' => '2024-09-01 00:00:00',
        //     'updated_at' => '2024-09-01 00:00:00'
        // ]);

        // ReviewDosenPembimbing::create([
        //     'review' => 'Cobalah untuk merestrukturisasi kalimat dan mengganti sinonim agar tidak terlalu mirip dengan sumber aslinya.',
        //     'id_dokumen' => 4,
        //     'nip' => '198604122014041001',
        //     'created_at' => '2024-09-01 00:00:00',
        //     'updated_at' => '2024-09-01 00:00:00'
        // ]);
    }
}
