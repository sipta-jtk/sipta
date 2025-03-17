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
                'username' => '197312271999031003',
                'nama' => 'Dosen Satu',
                'email' => 'dosen1@example.com',
                'password' => Hash::make('dosen123'),
                'role_user' => 'dosen',
                'no_whatsapp' => '081234567890',
                'photo' => 'default.png'
            ],
            [
                'username' => '198502102015042001',
                'nama' => 'Dosen Dua',
                'email' => 'dosen2@example.com',
                'password' => Hash::make('dosen123'),
                'role_user' => 'dosen',
                'no_whatsapp' => '081234567891',
                'photo' => 'default.png'
            ],
            [
                'username' => '197201061999031002',
                'nama' => 'Dosen Tiga',
                'email' => 'dosen3@example.com',
                'password' => Hash::make('dosen123'),
                'role_user' => 'dosen',
                'no_whatsapp' => '081234567892',
                'photo' => 'default.png'
            ],
            [
                'username' => '196810141993032002',
                'nama' => 'Dosen Empat',
                'email' => 'dosen4@example.com',
                'password' => Hash::make('dosen123'),
                'role_user' => 'dosen',
                'no_whatsapp' => '081234567893',
                'photo' => 'default.png'
            ],
            [
                'username' => '197604182001121004',
                'nama' => 'Dosen Lima',
                'email' => 'dosen5@example.com',
                'password' => Hash::make('dosen123'),
                'role_user' => 'dosen',
                'no_whatsapp' => '081234567894',
                'photo' => 'default.png'
            ],
            [
                'username' => '221524059',
                'nama' => 'Mahasiswa Satu',
                'email' => 'mahasiswa1@example.com',
                'password' => Hash::make('mahasiswa123'),
                'role_user' => 'mahasiswa',
                'no_whatsapp' => '081234567891',
                'photo' => 'default.png'
            ],
            [
                'username' => '221524049',
                'nama' => 'Mahasiswa Dua',
                'email' => 'mahasiswa2@example.com',
                'password' => Hash::make('mahasiswa123'),
                'role_user' => 'mahasiswa',
                'no_whatsapp' => '081234567892',
                'photo' => 'default.png'
            ],
            [
                'username' => '221524039',
                'nama' => 'Mahasiswa Tiga',
                'email' => 'mahasiswa3@example.com',
                'password' => Hash::make('mahasiswa123'),
                'role_user' => 'mahasiswa',
                'no_whatsapp' => '081234567893',
                'photo' => 'default.png'
            ],
        ];

        foreach ($data as $item) {
            User::create($item);
        }
    }
}
