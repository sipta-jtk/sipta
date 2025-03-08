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
        $role = Role::firstOrCreate(['name' => 'admin']);
        //Role::firstOrCreate(['name' => 'mahasiswa']);

        $dosenUsers = User::where('username', 'dosen005')->get();

        foreach ($dosenUsers as $user) {
            $user->assignRole($role); // Assign role dari Spatie ke user
        }

    }
}
