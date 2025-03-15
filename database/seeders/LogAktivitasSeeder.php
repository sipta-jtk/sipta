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
            'username' => '221524039',
            'id_dokumen' => 1,
            'action' => 'upload',
            'waktu_aktivitas' => now()
        ]);

        LogAktivitas::create([
            'id_kota' => 2,
            'username' => '221524049',
            'id_dokumen' => 2,
            'action' => 'edit',
            'waktu_aktivitas' => now()
        ]);

        LogAktivitas::create([
            'id_kota' => 3,
            'username' => '221524059',
            'id_dokumen' => 3,
            'action' => 'delete',
            'waktu_aktivitas' => now()
        ]);

        
    }
}

