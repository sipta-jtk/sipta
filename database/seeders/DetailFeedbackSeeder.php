<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Models\DetailFeedback;

class DetailFeedbackSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (!Schema::hasTable('detail_feedback')) {
            return;
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('detail_feedback')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $data = [
            [
                'id_feedback' => 1,
                'id_kota' => 2, 
                'nip' => '197312271999031003', 
                'status_penilaian' => 'draf', 
                'isi_feedback' => 'Feedback mengenai presentasi sangat baik dan informatif.',
            ],
            [
                'id_feedback' => 2,
                'id_kota' => 1,
                'nip' => '198502102015042001',
                'status_penilaian' => 'dipublikasikan',
                'isi_feedback' => 'Diskusi cukup baik, namun bisa lebih interaktif.',
            ],
        ];

        foreach ($data as $item) {
            DetailFeedback::create($item);
        }
    }
}
