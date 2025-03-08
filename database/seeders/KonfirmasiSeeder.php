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
                'nip' => '198502102015042001', // AN
                'status_konfirmasi' => 'tidak_disetujui',
            ]
        ];

        foreach ($konfirmasiData as $data) {
            Konfirmasi::create($data);
        }
    }
}
