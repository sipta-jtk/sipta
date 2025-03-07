<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (!Schema::hasTable('user')) {
            return;
        }

        // Disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Truncate the table
        DB::table('user')->truncate();

        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $data = [
            [
                'username' => 'admin123',
                'nama' => 'Admin Sistem',
                'email' => 'admin@example.com',
                'password' => Hash::make('password123'),
                'role_user' => 'dosen',
                'no_whatsapp' => '081234567890',
                'photo' => 'default.png'
            ],
            [
                'username' => 'mahasiswa001',
                'nama' => 'Mahasiswa Satu',
                'email' => 'mahasiswa1@example.com',
                'password' => Hash::make('password123'),
                'role_user' => 'mahasiswa',
                'no_whatsapp' => '081234567891',
                'photo' => 'default.png'
            ]
        ];

        foreach ($data as $item) {
            User::create($item);
        }
    }
}