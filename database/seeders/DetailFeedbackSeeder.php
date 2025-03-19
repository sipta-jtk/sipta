<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Models\DetailFeedback;
use Carbon\Carbon;

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
                'id_fta' => 1,
                'kode_fta' => 4,
                'nama_fta' => 'Seminar I',
                'id_prodi' => 1,
                'jenis_form' => 'feedback',
                'tanggal_tenggat_pengisian' => '20/02/2025',
                'waktu_tenggat_pengisian' => '10:30:00',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'id_fta' => 2,
                'kode_fta' => 7,
                'nama_fta' => 'Seminar II',
                'id_prodi' => 1,
                'jenis_form' => 'penilaian',
                'tanggal_tenggat_pengisian' => '25/03/2025',
                'waktu_tenggat_pengisian' => '13:30:00',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'id_fta' => 3,
                'kode_fta' => 7,
                'nama_fta' => 'Seminar II',
                'id_prodi' => 1,
                'jenis_form' => 'feedback',
                'tanggal_tenggat_pengisian' => '26/03/2025',
                'waktu_tenggat_pengisian' => '8:30:00',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'id_fta' => 4,
                'kode_fta' => 11,
                'nama_fta' => 'Seminar III',
                'id_prodi' => 1,
                'jenis_form' => 'penilaian',
                'tanggal_tenggat_pengisian' => '10/04/2025',
                'waktu_tenggat_pengisian' => '14:30:00',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'id_fta' => 5,
                'kode_fta' => 11,
                'nama_fta' => 'Seminar III',
                'id_prodi' => 1,
                'jenis_form' => 'feedback',
                'tanggal_tenggat_pengisian' => '11/04/2025',
                'waktu_tenggat_pengisian' => '10:30:00',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'id_fta' => 6,
                'kode_fta' => 15,
                'nama_fta' => 'Sidang Akhir',
                'id_prodi' => 1,
                'jenis_form' => 'penilaian',
                'tanggal_tenggat_pengisian' => '18/04/2025',
                'waktu_tenggat_pengisian' => '9:30:00',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'id_fta' => 7,
                'kode_fta' => 15,
                'nama_fta' => 'Sidang Akhir',
                'id_prodi' => 1,
                'jenis_form' => 'feedback',
                'tanggal_tenggat_pengisian' => '19/04/2025',
                'waktu_tenggat_pengisian' => '11:30:00',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'id_fta' => 8,
                'kode_fta' => 17,
                'nama_fta' => 'Dosen Pembimbing',
                'id_prodi' => 1,
                'jenis_form' => 'penilaian',
                'tanggal_tenggat_pengisian' => '30/04/2025',
                'waktu_tenggat_pengisian' => '15:30:00',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]
        ];

        foreach ($data as $item) {
            DetailFeedback::create($item);
        }
    }
}
