<?php

namespace Database\Seeders;

use App\Models\User;
use DB;
use Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Schema;

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

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('user')->truncate();
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
            ],
            [
                'username' => '2201234567',
                'nama' => 'Mahasiswa Dummy',
                'email' => 'email@dummy.com',
                'password' => Hash::make('password123'),
                'role_user' => 'mahasiswa',
                'no_whatsapp' => '081234567892',
                'photo' => 'default.png'
            ],
            [
                'username' => '2201234568',
                'nama' => 'Mahasiswa Dummy 2',
                'email' => 'emaild@gmail.com',
                'password' => Hash::make('password123'),
                'role_user' => 'mahasiswa',
                'no_whatsapp' => '081234567896',
                'photo' => 'default.png'
            ],
            [
                'username' => '1987654321',
                'nama' => 'Dosen Satu',
                'email' => 'dsn1@gmail.com',
                'password' => Hash::make('password123'),
                'role_user' => 'dosen',
                'no_whatsapp' => '081234567893',
                'photo' => 'default.png'
            ],
            [
                'username' => '1987654322',
                'nama' => 'Dosen Dua',
                'email' => 'dsb2@gmail.com',
                'password' => Hash::make('password123'),
                'role_user' => 'dosen',
                'no_whatsapp' => '081234567894',
                'photo' => 'default.png'
            ],
        ];

        foreach ($data as $item) {
            User::create($item);
        }
    }
}
