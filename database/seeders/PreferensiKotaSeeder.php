<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Models\PreferensiKota;

class PreferensiKotaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (!Schema::hasTable('preferensi_kota')) {
            return;
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('preferensi_kota')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $data = [        ];

        foreach ($data as $item) {
            PreferensiKota::create($item);
        }
    }
}
