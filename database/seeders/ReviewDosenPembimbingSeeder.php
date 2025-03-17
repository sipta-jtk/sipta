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
        if (!Schema::hasTable('review_dosen_pembimbing')) {
            return;
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('review_dosen_pembimbing')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        ReviewDosenPembimbing::create([
            'review' => 'Baik',
            'id_dokumen' => 1,
            'nip' => '197312271999031003',
            'created_at' => '2024-09-01 00:00:00',
            'updated_at' => '2024-09-01 00:00:00'

        ]);

        ReviewDosenPembimbing::create([
            'review' => 'Cukup',
            'id_dokumen' => 2,
            'nip' => '198502102015042001',
            'created_at' => '2024-09-01 00:00:00',
            'updated_at' => '2024-09-01 00:00:00'
        ]);
    }
}
