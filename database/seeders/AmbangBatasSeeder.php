<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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

        DB::table('ambang_batas')->truncate();

        $data = [
            [
                'id_ambang_batas' => '1',
                'ambang_batas' => 50.0
            ],
            [
                'id_ambang_batas' => '2',
                'ambang_batas' => 70.0
            ],
        ];

        foreach ($data as $item)
        {
            AmbangBatas::create($item);
        }
    }
}
