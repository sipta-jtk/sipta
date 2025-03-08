<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Models\Penjadwalan;

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
            'sesi' => '1', // Ubah Manual
            'agenda' => 'seminar_1', // Ubah Manual
            'id_ruangan' => '1', // Ubah Manual
            'tanggal' => 'now()', // Ubah Manual
            'id_kota' => '10', // Ubah Manual
            'nip' => '1987654322' // Ubah Manual
        ]);

        Penjadwalan::create([
            'sesi' => '2', // Ubah Manual
            'agenda' => 'seminar_2', // Ubah Manual
            'id_ruangan' => '2', // Ubah Manual
            'tanggal' => 'now()', // Ubah Manual
            'id_kota' => '12', // Ubah Manual
            'nip' => '1987654322' // Ubah Manual
        ]);

    }
}
