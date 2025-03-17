<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Models\KetertarikanBidang;

class KetertarikanBidangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (!Schema::hasTable('ketertarikan_bidang')) {
            return;
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('ketertarikan_bidang')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

    {
        // Schema::disableForeignKeyConstraints();
        // Clear existing data
        KetertarikanBidang::truncate();
        // Schema::enableForeignKeyConstraints();

        // Insert sample data
        $ketertarikanBidang = [
            ['nip' => '197312271999031003', 'id_bidang' => 1],
            ['nip' => '197312271999031003', 'id_bidang' => 2],
            ['nip' => '196810141993032002', 'id_bidang' => 3],
            ['nip' => '196810141993032002', 'id_bidang' => 4],
            ['nip' => '197201061999031002', 'id_bidang' => 5],
            ['nip' => '197201061999031002', 'id_bidang' => 6],
            ['nip' => '197604182001121004', 'id_bidang' => 7],
            ['nip' => '197604182001121004', 'id_bidang' => 8],
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
}
