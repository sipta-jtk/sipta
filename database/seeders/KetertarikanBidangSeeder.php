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
                'nip' => '19731227 199903 1 003', // AD
                'id_bidang' => 1,
            ],
            [
                'nip' => '19731227 199903 1 003', // AD
                'id_bidang' => 2,
            ],
            [
                'nip' => '19681014 199303 2 002', // AN
                'id_bidang' => 3,
            ],
            [
                'nip' => '19681014 199303 2 002', // AN
                'id_bidang' => 4,
            ],
            [
                'nip' => '19720106 199903 1 002', // BW
                'id_bidang' => 5,
            ],
            [
                'nip' => '19720106 199903 1 002', // BW
                'id_bidang' => 6,
            ],
            [
                'nip' => '19760418 200112 1 004', // IA
                'id_bidang' => 9,
            ],
            [
                'nip' => '19760418 200112 1 004', // IA
                'id_bidang' => 8,
            ]
        ];

        foreach ($ketertarikanBidang as $data) {
            KetertarikanBidang::create($data);
        }

        Schema::enableForeignKeyConstraints();
    }
}
