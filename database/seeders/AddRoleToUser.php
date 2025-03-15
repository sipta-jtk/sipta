<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;


class AddRoleToUser extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Pastikan role sudah ada
        //Role::firstOrCreate(['name' => 'mahasiswa']);

        $admin = User::create([
            'username' => 'adminasli',
            'nama' => 'Mas Admin',
            'email' => 'masadmin@gmail.com',
            'password' => 'password123',
            'role_user' => 'admin',
            'no_whatsapp' => '081234567890',
            'photo' => 'fikri.jpg',
        ]);

        $admin->assignRole('admin');

    }
}
