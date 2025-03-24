<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\LogAktivitas;

class LogAktivitasSeeder extends Seeder
{
    public function run()
    {
        // Matikan sementara foreign key checks
        // DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        // DB::table('log_aktivitas')->truncate(); // Kosongkan tabel
        // DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // // Data sesuai dengan tabel yang diberikan
        // $logAktivitas = [
        //     [
        //         'id_log_aktivitas' => 1,
        //         'id_kota' => 3,
        //         'username' => '221524040',
        //         'id_dokumen' => 1,
        //         'action' => 'upload',
        //         'waktu_aktivitas' => now()
        //     ],
        //     [
        //         'id_log_aktivitas' => 2,
        //         'id_kota' => 3,
        //         'username' => '221524040',
        //         'id_dokumen' => 3,
        //         'action' => 'delete',
        //         'waktu_aktivitas' => now()
        //     ],
        //     [
        //         'id_log_aktivitas' => 3,
        //         'id_kota' => 3,
        //         'username' => '221524040',
        //         'id_dokumen' => 2,
        //         'action' => 'download',
        //         'waktu_aktivitas' => now()
        //     ],
        //     [
        //         'id_log_aktivitas' => 4,
        //         'id_kota' => 6,
        //         'username' => '221524046',
        //         'id_dokumen' => 4,
        //         'action' => 'review',
        //         'waktu_aktivitas' => now()
        //     ],
        //     [
        //         'id_log_aktivitas' => 5,
        //         'id_kota' => 6,
        //         'username' => '221524046',
        //         'id_dokumen' => 5,
        //         'action' => 'edit',
        //         'waktu_aktivitas' => now()
        //     ],
        //     [
        //         'id_log_aktivitas' => 6,
        //         'id_kota' => 6,
        //         'username' => '221524046',
        //         'id_dokumen' => 6,
        //         'action' => 'edit',
        //         'waktu_aktivitas' => now()
        //     ],
        //     [
        //         'id_log_aktivitas' => 7,
        //         'id_kota' => 6,
        //         'username' => '221524046',
        //         'id_dokumen' => 7,
        //         'action' => 'upload',
        //         'waktu_aktivitas' => now()
        //     ],
        //     [
        //         'id_log_aktivitas' => 8,
        //         'id_kota' => 6,
        //         'username' => '221524046',
        //         'id_dokumen' => 8,
        //         'action' => 'upload',
        //         'waktu_aktivitas' => now()
        //     ],
        // ];

        // foreach ($logAktivitas as $log) {
        //     LogAktivitas::create($log);
        // }
    }
}
