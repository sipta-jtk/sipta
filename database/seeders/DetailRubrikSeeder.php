<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Models\DetailRubrik;

class DetailRubrikSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (!Schema::hasTable('detail_rubrik')) {
            return;
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('detail_rubrik')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $data = [
            [
                'id_rubrik' => 1, // Sesuaikan dengan data valid di rubrik
                'detail_rubrik_penilaian' => 'Pemahaman materi sangat baik dan mampu menjawab semua pertanyaan.',
                'id_nilai' => 'A', // Sesuaikan dengan data valid di rentang_nilai
            ],
            [
                'id_rubrik' => 1,
                'detail_rubrik_penilaian' => 'Pemahaman cukup baik namun ada beberapa kesalahan dalam jawaban.',
                'id_nilai' => 'B',
            ],
            [
                'id_rubrik' => 2,
                'detail_rubrik_penilaian' => 'Kurang menguasai materi, jawaban kurang tepat.',
                'id_nilai' => 'C',
            ],
        ];

        foreach ($data as $item) {
            DetailRubrik::create($item);
        }
    }
}
