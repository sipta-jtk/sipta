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
        // admin
        Permission::create(['name'=>'manage-role']);
        Permission::create(['name'=>'lihat-manage-role']);

        // mahasiswa
        Permission::create(['name'=>'lihat-dashboard']);
        Permission::create(['name'=>'edit-profil']);


        // List Every Role
        Role::create(['name'=>'admin']);
        Role::create(['name'=>'dosen']);
        Role::create(['name'=>'mahasiswa']);


        // 
        $roleAdmin = Role::findByName('admin');
        $roleAdmin->givePermissionTo('manage-role');
        $roleAdmin->givePermissionTo('lihat-manage-role');

        $roleMahasiswa = Role::findByName('mahasiswa');
        $roleMahasiswa->givePermissionTo('lihat-dashboard');
        
    }
}
