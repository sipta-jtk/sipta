<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Models\AspekFeedback;

class AspekFeedbackSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (!Schema::hasTable('aspek_feedback')) {
            return;
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('aspek_feedback')->truncate();

        $data = [
            [
                'id_feedback' => 1,
                'id_fta' => 4,
                'nama_aspek_feedback' => 'Deskripsi Topik'
            ],
            [
                'id_feedback' => 2,
                'id_fta' => 4,
                'nama_aspek_feedback' => 'Problem Definition'
            ],
            [
                'id_feedback' => 3,
                'id_fta' => 4,
                'nama_aspek_feedback' => 'Metodologi Penyelesaian TA'
            ],
            [
                'id_feedback' => 4,
                'id_fta' => 7,
                'nama_aspek_feedback' => 'Dokumen'
            ],
            [
                'id_feedback' => 5,
                'id_fta' => 7,
                'nama_aspek_feedback' => 'Presentasi'
            ],
            [
                'id_feedback' => 6,
                'id_fta' => 7,
                'nama_aspek_feedback' => 'Penguasaan Materi'
            ],
            [
                'id_feedback' => 7,
                'id_fta' => 11,
                'nama_aspek_feedback' => 'Dokumen'
            ],
            [
                'id_feedback' => 8,
                'id_fta' => 11,
                'nama_aspek_feedback' => 'Presentasi'
            ],
            [
                'id_feedback' => 9,
                'id_fta' => 11,
                'nama_aspek_feedback' => 'Penguasaan Materi'
            ],
            [
                'id_feedback' => 10,
                'id_fta' => 15,
                'nama_aspek_feedback' => 'Catatan Perbaikan Laporan'
            ]
        ];

        foreach ($data as $item) {
            AspekFeedback::create($item);
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}