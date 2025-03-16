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
                'nip' => '197604182001121004', // IA
                'id_bidang' => 9,
            ],
            [
                'nip' => '197604182001121004', // IA
                'id_bidang' => 8,
            ]
        ];

        foreach ($ketertarikanBidang as $data) {
            KetertarikanBidang::create($data);
        }

        Schema::enableForeignKeyConstraints();
    }
}
