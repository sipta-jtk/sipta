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
                'id' => 1,
                'jadwal_id' => 5,
                'alasan' => 'Salah satu penguji tidak bisa hadir, sehingga jadwal sidang harus diubah.',
                'nip' => '198004192005011002',
                'is_active' => true,
            ],
            [
                'id' => 2,
                'jadwal_id' => 4,
                'alasan' => 'Jadwal bentrok dengan acara lain di kampus yang memerlukan ruangan tersebut.',
                'nip' => '198004192005011002',
                'is_active' => null,
            ],
        ]);
    }
}
