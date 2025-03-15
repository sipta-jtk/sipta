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
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $data = [
            [
                'kode_fta' => 1, // Sesuaikan dengan ID yang valid di form_penilaian
                'nama_aspek_feedback' => 'Kejelasan materi presentasi',
            ],
            [
                'kode_fta' => 1,
                'nama_aspek_feedback' => 'Penguasaan materi oleh pemateri',
            ],
            [
                'kode_fta' => 2,
                'nama_aspek_feedback' => 'Kualitas diskusi dan tanya jawab',
            ],
            [
                'kode_fta' => 3,
                'nama_aspek_feedback' => 'Relevansi konten dengan topik seminar',
            ],
        ];

        foreach ($data as $item) {
            AspekFeedback::create($item);
        }
    }
}