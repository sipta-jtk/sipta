<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\PasswordReset;

class PasswordResetSeeder extends Seeder
{
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('password_reset')->truncate(); // Membersihkan tabel sebelum seeding
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $data = [
            [
                'email' => 'mhs1@example.com',
                'token' => 'sSdafS'
            ],
            [
                'email' => 'mhs2@example.com',
                'token' => 'sdsa23A'
            ],
        ];

        foreach ($data as $item) {
            PasswordReset::create($item);
        }
    }
}