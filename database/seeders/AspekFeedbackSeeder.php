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
                'id_fta' => 1,
                'nama_aspek_feedback' => 'Deskripsi Topik'
            ],
            [
                'id_feedback' => 2,
                'id_fta' => 1,
                'nama_aspek_feedback' => 'Problem Definition'
            ],
            [
                'id_feedback' => 3,
                'id_fta' => 1,
                'nama_aspek_feedback' => 'Metodologi Penyelesaian TA'
            ],
            [
                'id_feedback' => 4,
                'id_fta' => 3,
                'nama_aspek_feedback' => 'Dokumen'
            ],
            [
                'id_feedback' => 5,
                'id_fta' => 3,
                'nama_aspek_feedback' => 'Presentasi'
            ],
            [
                'id_feedback' => 6,
                'id_fta' => 3,
                'nama_aspek_feedback' => 'Penguasaan Materi'
            ],
            [
                'id_feedback' => 7,
                'id_fta' => 5,
                'nama_aspek_feedback' => 'Dokumen'
            ],
            [
                'id_feedback' => 8,
                'id_fta' => 5,
                'nama_aspek_feedback' => 'Presentasi'
            ],
            [
                'id_feedback' => 9,
                'id_fta' => 5,
                'nama_aspek_feedback' => 'Penguasaan Materi'
            ],
            [
                'id_feedback' => 10,
                'id_fta' => 7,
                'nama_aspek_feedback' => 'Catatan Perbaikan Laporan'
            ],
            [
                'id_feedback' => 11,
                'id_fta' => 9,
                'nama_aspek_feedback' => 'Catatan Perbaikan'
            ],
            [
                'id_feedback' => 12,
                'id_fta' => 12,
                'nama_aspek_feedback' => 'Kesiapan Software dan/atau Tools'
            ],
            [
                'id_feedback' => 13,
                'id_fta' => 12,
                'nama_aspek_feedback' => 'Kesiapan Data'
            ],
            [
                'id_feedback' => 14,
                'id_fta' => 12,
                'nama_aspek_feedback' => 'Cara dan / atau Metode Eksperimen/Pengembangan Aplikasi'
            ],
            [
                'id_feedback' => 15,
                'id_fta' => 12,
                'nama_aspek_feedback' => 'Penguasaan Materi'
            ],
            [
                'id_feedback' => 16,
                'id_fta' => 15,
                'nama_aspek_feedback' => 'Aplikasi Pendukung Eksperimen (APE)/Aplikasi yang Dikembangkan (AYK)'
            ],
            [
                'id_feedback' => 17,
                'id_fta' => 15,
                'nama_aspek_feedback' => 'Eksperimen/Pengembangan Aplikasi'
            ],
            [
                'id_feedback' => 18,
                'id_fta' => 15,
                'nama_aspek_feedback' => 'Dokumen TA'
            ],
            [
                'id_feedback' => 19,
                'id_fta' => 15,
                'nama_aspek_feedback' => 'Penguasaan Materi'
            ],
            [
                'id_feedback' => 20,
                'id_fta' => 18,
                'nama_aspek_feedback' => 'Catatan Perbaikan Laporan'
            ],
        ];


        foreach ($data as $item) {
            AspekFeedback::create($item);
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}