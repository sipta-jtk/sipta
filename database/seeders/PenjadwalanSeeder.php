<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Models\Penjadwalan;
use Carbon\Carbon;

class PenjadwalanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (!Schema::hasTable('penjadwalan')) {
            return;
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('penjadwalan')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        Penjadwalan::create([
            'sesi' => 1, 
            'agenda' => 'seminar_1', 
            'id_ruangan' => 1, 
            'tanggal' => Carbon::create(2025, 3, 17),
            'id_kota' => 2, 
            'start' => Carbon::create(2025, 3, 17, 7, 0, 0),
            'end' => Carbon::create(2025, 3, 17, 9, 0, 0)
        ]);

        Penjadwalan::create([
            'sesi' => 2, 
            'agenda' => 'seminar_2', 
            'id_ruangan' => 2, 
            'tanggal' => Carbon::create(2025, 3, 17),
            'id_kota' => 6, 
            'start' => Carbon::create(2025, 3, 17, 9, 0, 0),
            'end' => Carbon::create(2025, 3, 17, 11, 0, 0)
        ]);

        Penjadwalan::create([
            'sesi' => 3, 
            'agenda' => 'seminar_3', 
            'id_ruangan' => 3, 
            'tanggal' => Carbon::create(2025, 3, 17),
            'id_kota' => 1, 
            'start' => Carbon::create(2025, 3, 17, 13, 0, 0),
            'end' => Carbon::create(2025, 3, 17, 15, 0, 0)
        ]);

        Penjadwalan::create([
            'sesi' => 4, 
            'agenda' => 'sidang', 
            'id_ruangan' => 4, 
            'tanggal' => Carbon::create(2025, 3, 17),
            'id_kota' => 5, 
            'start' => Carbon::create(2025, 3, 17, 15, 0, 0),
            'end' => Carbon::create(2025, 3, 17, 17, 0, 0) // Fixed: end time should be after start time
        ]);

        Penjadwalan::create([
            'sesi' => 2, 
            'agenda' => 'sidang', 
            'id_ruangan' => 5, 
            'tanggal' => Carbon::create(2025, 3, 18),
            'id_kota' => 9, 
            'start' => Carbon::create(2025, 3, 18, 7, 0, 0), // Fixed: date matches tanggal
            'end' => Carbon::create(2025, 3, 18, 9, 0, 0) // Fixed: date matches tanggal
        ]);

    }
}
