<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Models\AmbangBatas;

class AmbangBatasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (!Schema::hasTable('ambang_batas'))
        {
            return;
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('ambang_batas')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $data = [
            [
                'ambang_batas' => 50.0,
                'status_ambang_batas' => 'digunakan', 
                'nip' => '197312271999031003', 
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'ambang_batas' => 70.0,
                'status_ambang_batas' => 'tidak_digunakan',
                'nip' => '198502102015042001',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($data as $item)
        foreach ($data as $item)
        {
            AmbangBatas::create($item);
        }
    }
}
