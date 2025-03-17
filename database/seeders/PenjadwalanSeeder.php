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
            'sesi' => '1', 
            'agenda' => 'seminar_1', 
            'id_ruangan' => '1', 
            'tanggal' => Carbon::create(2025, 3, 17), // Tanggal 17 Maret 2025
            'id_kota' => '2', 
            'nip' => '197312271999031003', 
            'start' => Carbon::create(2025, 3, 17, 8, 0, 0), // 17/03/2025 8:00:00
            'end' => Carbon::create(2025, 3, 17, 10, 0, 0) // 17/03/2025 10:00:00
        ]);

        Penjadwalan::create([
            'sesi' => '2', 
            'agenda' => 'seminar_2', 
            'id_ruangan' => '2', 
            'tanggal' => Carbon::create(2025, 3, 17), // Tanggal 17 Maret 2025
            'id_kota' => '6', 
            'nip' => '196810141993032002', 
            'start' => Carbon::create(2025, 3, 17, 10, 30, 0), 
            'end' => Carbon::create(2025, 3, 17, 12, 30, 0) 
        ]);

        Penjadwalan::create([
            'sesi' => '3', 
            'agenda' => 'seminar_3', 
            'id_ruangan' => '3', 
            'tanggal' => Carbon::create(2025, 3, 17), // Tanggal 17 Maret 2025
            'id_kota' => '1', 
            'nip' => '197201061999031002', 
            'start' => Carbon::create(2025, 3, 17, 13, 30, 0), 
            'end' => Carbon::create(2025, 3, 17, 15, 30, 0) 
        ]);

        Penjadwalan::create([
            'sesi' => '4', 
            'agenda' => 'sidang', 
            'id_ruangan' => '4', 
            'tanggal' => Carbon::create(2025, 3, 18), // Tanggal 18 Maret 2025
            'id_kota' => '5', 
            'nip' => '196012261992031001', 
            'start' => Carbon::create(2025, 3, 18, 10, 00, 0), 
            'end' => Carbon::create(2025, 3, 18, 12, 00, 0) 
        ]);

        Penjadwalan::create([
            'sesi' => '2', 
            'agenda' => 'sidang', 
            'id_ruangan' => '5', 
            'tanggal' => Carbon::create(2025, 3, 18), // Tanggal 18 Maret 2025
            'id_kota' => '9', 
            'nip' => '196101141992021001', 
            'start' => Carbon::create(2025, 3, 18, 13, 00, 0), 
            'end' => Carbon::create(2025, 3, 18, 15, 00, 0) 
        ]);

    }
}
