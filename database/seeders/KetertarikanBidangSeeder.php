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
        Schema::disableForeignKeyConstraints();

        DB::table('ketertarikan_bidang')->truncate();

        $ketertarikanBidang = [
            ['id_ketertarikan_bidang' => 1, 'nip' => '198706302019031011', 'id_bidang' => 3],
            ['id_ketertarikan_bidang' => 2, 'nip' => '198706302019031011', 'id_bidang' => 4],
            ['id_ketertarikan_bidang' => 3, 'nip' => '197109031999032001', 'id_bidang' => 4],
            ['id_ketertarikan_bidang' => 4, 'nip' => '197109031999032001', 'id_bidang' => 5],
            ['id_ketertarikan_bidang' => 5, 'nip' => '198104072006041001', 'id_bidang' => 2],
            ['id_ketertarikan_bidang' => 6, 'nip' => '198104072006041001', 'id_bidang' => 11],
            ['id_ketertarikan_bidang' => 7, 'nip' => '198104072006041001', 'id_bidang' => 14],
            ['id_ketertarikan_bidang' => 8, 'nip' => '198502102015042001', 'id_bidang' => 2],
            ['id_ketertarikan_bidang' => 9, 'nip' => '199301062019031017', 'id_bidang' => 3],
        ];

        DB::table('ketertarikan_bidang')->insert($ketertarikanBidang);

        Schema::enableForeignKeyConstraints();
    }
}
