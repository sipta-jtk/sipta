<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

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

        DB::table('user')->truncate();

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
