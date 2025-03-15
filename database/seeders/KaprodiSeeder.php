<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Models\Kaprodi;

class KaprodiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (!Schema::hasTable('kaprodi')) {
            return;
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('kaprodi')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $data = [
            [
                'nip' => '197312271999031003',
                'id_prodi' => 1
            ],
            [
                'nip' => '198502102015042001',
                'id_prodi' => 2
            ],
        ];

        foreach ($data as $item) {
            Kaprodi::create($item);
        }

        Kaprodi::create([
            'nip' => '197312271999031003',
            'id_prodi' => 1
        ]);

        Kaprodi::create([
            'nip' => '198502102015042001',
            'id_prodi' => 2
        ]);
    }
}
