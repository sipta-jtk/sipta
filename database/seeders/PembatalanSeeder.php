<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PembatalanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('pembatalan')->insert([
            [
                'id_penjadwalan' => 5,
                'alasan_pembatalan' => 'Salah satu penguji tidak bisa hadir, sehingga jadwal sidang harus diubah.',
                'nip' => '198004192005011002',
                'status_pembatalan' => true,
            ],
            [
                'id_penjadwalan' => 4,
                'alasan_pembatalan' => 'Jadwal bentrok dengan acara lain di kampus yang memerlukan ruangan tersebut.',
                'nip' => '198004192005011002',
                'status_pembatalan' => false,
            ],
        ]);
    }
}