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
    
        DB::table('password_reset')->insert([
            [
                'email' => 'ade.chandra.test@polban.ac.id',
                'token' => '9f86d081884c7d659a2feaa0c55ad015a3bf4f1b2e8c1a1f7e2c417cb6bfb7c7',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'email' => 'ani.rahmani.test@polban.ac.id',
                'token' => 'd4735e3a265e16eee03f59718b9b5d403f05c1d65b85c1c7e3f6c3f3b8b4a3b6',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'email' => 'najib.alimudin.tif422.test@polban.ac.id',
                'token' => 'e99a18c428cb38d5f260853678922e03abd8330ef9ecf46571b8db90a59d7b9b',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'email' => 'niqa.nabila.tif422.test@polban.ac.id',
                'token' => '8f43434624f0214861c3c832c7d1c55bb3e90a709d5f9d2eabf4fa479ded7e0c',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
        ]);

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

    }
}
