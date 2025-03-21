<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Models\FormPenilaian;
use Carbon\Carbon;

class FormPenilaianSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (!Schema::hasTable('form_penilaian')) {
            return;
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('form_penilaian')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $data = [
            [
                'id_fta' => 1,
                'kode_fta' => 'FTA.04',
                'nama_fta' => 'Seminar I',
                'id_prodi' => 1,
                'jenis_form' => 'feedback',
                'tanggal_tenggat_pengisian' => '2025-02-20',
                'waktu_tenggat_pengisian' => '10:30:00',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'id_fta' => 2,
                'kode_fta' => 'FTA.07',
                'nama_fta' => 'Seminar II',
                'id_prodi' => 1,
                'jenis_form' => 'penilaian',
                'tanggal_tenggat_pengisian' => '2025-03-25',
                'waktu_tenggat_pengisian' => '13:30:00',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'id_fta' => 3,
                'kode_fta' => 'FTA.08',
                'nama_fta' => 'Seminar II',
                'id_prodi' => 1,
                'jenis_form' => 'feedback',
                'tanggal_tenggat_pengisian' => '2025-03-26',
                'waktu_tenggat_pengisian' => '8:30:00',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'id_fta' => 4,
                'kode_fta' => 'FTA.011',
                'nama_fta' => 'Seminar II',
                'id_prodi' => 1,
                'jenis_form' => 'penilaian',
                'tanggal_tenggat_pengisian' => '2025-04-10',
                'waktu_tenggat_pengisian' => '14:30:00',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'id_fta' => 5,
                'kode_fta' => 'FTA.012',
                'nama_fta' => 'Seminar III',
                'id_prodi' => 1,
                'jenis_form' => 'feedback',
                'tanggal_tenggat_pengisian' => '2025-04-11',
                'waktu_tenggat_pengisian' => '10:30:00',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'id_fta' => 6,
                'kode_fta' => 'FTA.015',
                'nama_fta' => 'Sidang Akhir',
                'id_prodi' => 1,
                'jenis_form' => 'penilaian',
                'tanggal_tenggat_pengisian' => '2025-04-11',
                'waktu_tenggat_pengisian' => '9:30:00',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'id_fta' => 7,
                'kode_fta' => 'FTA.015',
                'nama_fta' => 'Sidang Akhir',
                'id_prodi' => 1,
                'jenis_form' => 'feedback',
                'tanggal_tenggat_pengisian' => '2025-04-19',
                'waktu_tenggat_pengisian' => '11:30:00',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'id_fta' => 8,
                'kode_fta' => 'FTA.017',
                'nama_fta' => 'Dosen Pembimbing',
                'id_prodi' => 1,
                'jenis_form' => 'penilaian',
                'tanggal_tenggat_pengisian' => '2025-04-30',
                'waktu_tenggat_pengisian' => '15:30:00',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]
        ];


        foreach ($data as $item) {
            FormPenilaian::create($item);
        }
    }
}
