<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\KetertarikanBidang;

class KetertarikanBidangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {        
        // Schema::disableForeignKeyConstraints();
        // Clear existing data
        KetertarikanBidang::truncate();
        // Schema::enableForeignKeyConstraints();
        
        // Insert sample data
        $ketertarikanBidang = [
            [
                'nip' => '197312271999031003', // AD
                'id_bidang' => 1,
            ],
            [
                'nip' => '197312271999031003', // AD
                'id_bidang' => 2,
            ],
            [
                'nip' => '196810141993032002', // AN
                'id_bidang' => 3,
            ],
            [
                'nip' => '196810141993032002', // AN
                'id_bidang' => 4,
            ],
            [
                'nip' => '197201061999031002', // BW
                'id_bidang' => 5,
            ],
            [
                'nip' => '197201061999031002', // BW
                'id_bidang' => 6,
            ],
            [
                'nip' => '196012261992031001', // DP
                'id_bidang' => 1,
            ],
            [
                'nip' => '196012261992031001', // DP
                'id_bidang' => 3,
            ],
            [
                'nip' => '196101141992021001', // EB
                'id_bidang' => 2,
            ],
            [
                'nip' => '196101141992021001', // EB
                'id_bidang' => 4,
            ],
            [
                'nip' => '198009162009122001', // FI
                'id_bidang' => 5,
            ],
            [
                'nip' => '198009162009122001', // FI
                'id_bidang' => 1,
            ],
            [
                'nip' => '198604122014041001', // GI
                'id_bidang' => 2,
            ],
            [
                'nip' => '198604122014041001', // GI
                'id_bidang' => 3,
            ],
            [
                'nip' => '198502102015042001', // HA
                'id_bidang' => 4,
            ],
            [
                'nip' => '198502102015042001', // HA
                'id_bidang' => 5,
            ],
            [
                'nip' => '197604182001121004', // IA
                'id_bidang' => 9,
            ],
            [
                'nip' => '197604182001121004', // IA
                'id_bidang' => 8,
            ],
            [
                'nip' => '198012122008122001', // ID
                'id_bidang' => 2,
            ],
            [
                'nip' => '198012122008122001', // ID
                'id_bidang' => 7,
            ],
            [
                'nip' => '198004192005011002', // IS
                'id_bidang' => 9,
            ],
            [
                'nip' => '198004192005011002', // IS
                'id_bidang' => 6,
            ],
            [
                'nip' => '196208151990031001', // IW
                'id_bidang' => 10,
            ],
            [
                'nip' => '196208151990031001', // IW
                'id_bidang' => 11,
            ],
        ];
        
        foreach ($ketertarikanBidang as $data) {
            KetertarikanBidang::create($data);
        }
        
        Schema::enableForeignKeyConstraints();
    }
}
