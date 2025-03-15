<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Models\PasswordReset;
use Carbon\Carbon;


class PasswordResetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('password_reset')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        DB::table('password_reset')->insert([
            [
                'email' => 'example1@example.com',
                'token' => '123456',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]
            ,
            [
                'email' => 'example2@example.com',
                'token' => '123456',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]
        ]);
    }
}
