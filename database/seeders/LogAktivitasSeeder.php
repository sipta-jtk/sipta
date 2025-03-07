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
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('log_aktivitas')->truncate(); // Kosongkan tabel
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Data dummy untuk seed
        LogAktivitas::create([
            'id_kota' => 1,
            'username' => 'user1',
            'id_dokumen' => 101,
            'action' => 'upload',
            'waktu_aktivitas' => now()
        ]);

        LogAktivitas::create([
            'id_kota' => 2,
            'username' => 'user2',
            'id_dokumen' => 102,
            'action' => 'edit',
            'waktu_aktivitas' => now()
        ]);

        LogAktivitas::create([
            'id_kota' => 3,
            'username' => 'user3',
            'id_dokumen' => 103,
            'action' => 'delete',
            'waktu_aktivitas' => now()
        ]);

        LogAktivitas::create([
            'id_kota' => 4,
            'username' => 'user4',
            'id_dokumen' => 104,
            'action' => 'download',
            'waktu_aktivitas' => now()
        ]);

        LogAktivitas::create([
            'id_kota' => 5,
            'username' => 'user5',
            'id_dokumen' => 105,
            'action' => 'review',
            'waktu_aktivitas' => now()
        ]);
    }
}

