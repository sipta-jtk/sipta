<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\Konfirmasi;

class KonfirmasiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ensure table is empty
        // Schema::disableForeignKeyConstraints();
        DB::table('konfirmasi')->truncate();
        // Schema::enableForeignKeyConstraints();

        // Create sample konfirmasi records
        $konfirmasiData = [
            [
            'id_penjadwalan' => 1,
            'nip' => '197312271999031003', // AD
            'status_konfirmasi' => 'disetujui',
            ],
            [
            'id_penjadwalan' => 2,
            'nip' => '196810141993032002', // AN
            'status_konfirmasi' => 'tidak_disetujui',
            ],
            [
            'id_penjadwalan' => 1,
            'nip' => '197201061999031002', // BW
            'status_konfirmasi' => 'tidak_disetujui',
            ],
            [
            'id_penjadwalan' => 2,
            'nip' => '196012261992031001', // DP
            'status_konfirmasi' => 'disetujui',
            ],
            [
            'id_penjadwalan' => 1,
            'nip' => '196101141992021001', // EB
            'status_konfirmasi' => 'disetujui',
            ],
            [
            'id_penjadwalan' => 1,
            'nip' => '198009162009122001', // FI
            'status_konfirmasi' => 'tidak_disetujui',
            ],
            [
            'id_penjadwalan' => 1,
            'nip' => '198604122014041001', // GI
            'status_konfirmasi' => 'disetujui',
            ],
            [
            'id_penjadwalan' => 2,
            'nip' => '198502102015042001', // HA
            'status_konfirmasi' => 'tidak_disetujui',
            ],
            [
            'id_penjadwalan' => 2,
            'nip' => '197604182001121004', // IA
            'status_konfirmasi' => 'tidak_disetujui',
            ],
            [
            'id_penjadwalan' => 2,
            'nip' => '198012122008122001', // ID
            'status_konfirmasi' => 'disetujui',
            ],
            [
            'id_penjadwalan' => 2,
            'nip' => '198004192005011002', // IS
            'status_konfirmasi' => 'tidak_disetujui',
            ],
            [
            'id_penjadwalan' => 1,
            'nip' => '196208151990031001', // IW
            'status_konfirmasi' => 'disetujui',
            ],
        ];

        foreach ($konfirmasiData as $data) {
            Konfirmasi::create($data);
        }
    }
}
