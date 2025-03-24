<?php

namespace Database\Seeders;

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
            // Admin
            [
                'username' => '199106142019032000',
                'nama' => 'Lia Rahmawati',
                'email' => 'lia.rahmawati@polban.ac.id',
                'password' => Hash::make('liar123!#'),
                'role_user' => 'admin',
                'no_whatsapp' => '081234567950',
                'photo' => 'lia_rahmawati.png'
            ],
            // Koordinator TA
            [
                'username' => '196610181995121001',
                'nama' => 'Joe Lian Min, M.Eng.',
                'email' => 'joe.lian@polban.ac.id',
                'password' => Hash::make('joel123!#'),
                'role_user' => 'dosen',
                'no_whatsapp' => '081234567923',
                'photo' => 'joe_lian_min,_m.eng..png'
            ],
            // Dosen
            [
                'username' => '198706302019031011',
                'nama' => 'Wendi Wirasta, S.T., M.T.',
                'email' => 'wendi.wirasta@polban.ac.id',
                'password' => Hash::make('wend123!#'),
                'role_user' => 'dosen',
                'no_whatsapp' => '081234567946',
                'photo' => 'wendi_wirasta,_s.t.,_m.t..png'
            ],
            [
                'username' => '197109031999032001',
                'nama' => 'Santi Sundari, S.Si., M.T.',
                'email' => 'santi.sundari@polban.ac.id',
                'password' => Hash::make('sant123!#'),
                'role_user' => 'dosen',
                'no_whatsapp' => '081234567925',
                'photo' => 'santi_sundari,_s.si.,_m.t..png'
            ],
            [
                'username' => '198104072006041001',
                'nama' => 'Dr. Priyanto Hidayatullah, ST.,M.Sc.',
                'email' => 'priyanto.hidayatullah@polban.ac.id',
                'password' => Hash::make('dr.p123!#'),
                'role_user' => 'dosen',
                'no_whatsapp' => '081234567921',
                'photo' => 'dr._priyanto_hidayatullah,_st.,m.sc..png'
            ],
            [
                'username' => '198502102015042001',
                'nama' => 'Ade Hodijah, S.T., M.T.',
                'email' => 'ade.hodijah@polban.ac.id',
                'password' => Hash::make('adeh123!#'),
                'role_user' => 'dosen',
                'no_whatsapp' => '081234567916',
                'photo' => 'ade_hodijah,_s.t.,_m.t..png'
            ],
            [
                'username' => '199301062019031017',
                'nama' => 'Lukmannul Hakim Firdaus, S.Kom., M.T.',
                'email' => 'lukmannul.hakim@polban.ac.id',
                'password' => Hash::make('lukm123!#'),
                'role_user' => 'dosen',
                'no_whatsapp' => '081234567941',
                'photo' => 'lukmannul_hakim_firdaus,_s.kom.,_m.t..png'
            ],
            // Mahasiswa
            [
                'username' => '221524034',
                'nama' => 'Arnanda Prasatya',
                'email' => 'arnanda.prasatya.tif422@polban.ac.id',
                'password' => Hash::make('arna123!#'),
                'role_user' => 'mahasiswa',
                'no_whatsapp' => '081234567952',
                'photo' => 'arnanda_prasatya.png'
            ],
            [
                'username' => '221524035',
                'nama' => 'Asri Husnul Rosadi',
                'email' => 'asri.husnul.tif422@polban.ac.id',
                'password' => Hash::make('asri123!#'),
                'role_user' => 'mahasiswa',
                'no_whatsapp' => '081234567953',
                'photo' => 'asri_husnul_rosadi.png'
            ],
            [
                'username' => '221524036',
                'nama' => 'Banteng Harisantoso',
                'email' => 'banteng.harisantoso.tif422@polban.ac.id',
                'password' => Hash::make('bant123!#'),
                'role_user' => 'mahasiswa',
                'no_whatsapp' => '081234567954',
                'photo' => 'banteng_harisantoso.png'
            ],
            [
                'username' => '221524037',
                'nama' => 'Bhisma Chandra Yudha Setiawan',
                'email' => 'bhisma.chandra.tif422@polban.ac.id',
                'password' => Hash::make('bhis123!#'),
                'role_user' => 'mahasiswa',
                'no_whatsapp' => '081234567955',
                'photo' => 'bhisma_chandra_yudha_setiawan.png'
            ],
        ];

        foreach ($data as $item) {
            User::create($item);
        }
    }
}
