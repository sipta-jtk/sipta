<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LogLoginSeeder extends Seeder
{
    public function run()
    {
        DB::table('log_login')->insert([
            [
                'username' => '221524050',
                'ip_address' => '192.168.1.10',
                'waktu_aktivitas' => Carbon::now()->subMinutes(10),
            ],
        ]);
    }
}