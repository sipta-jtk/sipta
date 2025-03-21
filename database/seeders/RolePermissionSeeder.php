<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    // permission: add-user,edit-user; 
    // role : dosen, mahasiswa


    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        // List Every Role
        Role::create(['name'=>'admin']);
        Role::create(['name'=>'dosen']);
        Role::create(['name'=>'mahasiswa']);

        
    }
}
