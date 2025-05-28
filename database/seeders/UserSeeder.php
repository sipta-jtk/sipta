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
            [
                'username' => '197312271999031003',
                'nama' => 'Ade Chandra Nugraha, S.Si., M.T.',
                'email' => 'ade.chandra@polban.ac.id',
                'password' => Hash::make('adec123!#'),
                'role_user' => 'dosen',
                'no_whatsapp' => '081234567912',
                'photo' => 'ade_chandra_nugraha,_s.si.,_m.t..png',
                'status_user' => 'aktif'
            ],
            [
                'username' => '196810141993032002',
                'nama' => 'Ani Rahmani, S.Si., M.T.',
                'email' => 'ani.rahmani@polban.ac.id',
                'password' => Hash::make('anir123!#'),
                'role_user' => 'dosen',
                'no_whatsapp' => '081234567912',
                'photo' => 'ani_rahmani,_s.si.,_m.t..png',
                'status_user' => 'aktif'
            ],
            [
                'username' => '197201061999031002',
                'nama' => 'Bambang Wisnuadhi, S.Si., M.T.',
                'email' => 'bambang.wisnuadhi@polban.ac.id',
                'password' => Hash::make('bamb123!#'),
                'role_user' => 'dosen',
                'no_whatsapp' => '081234567912',
                'photo' => 'bambang_wisnuadhi,_s.si.,_m.t..png',
                'status_user' => 'aktif'
            ],
            [
                'username' => '196012261992031001',
                'nama' => 'Didik Suwito Pribadi, BSCS.',
                'email' => 'didik.suwito@polban.ac.id',
                'password' => Hash::make('didi123!#'),
                'role_user' => 'dosen',
                'no_whatsapp' => '081234567912',
                'photo' => 'didik_suwito_pribadi,_bscs..png',
                'status_user' => 'aktif'
            ],
            [
                'username' => '196101141992021001',
                'nama' => 'Eddy B. Soewono, DRS., M.Kom.',
                'email' => 'eddy.soewono@polban.ac.id',
                'password' => Hash::make('eddy123!#'),
                'role_user' => 'dosen',
                'no_whatsapp' => '081234567913',
                'photo' => 'eddy_b._soewono,_drs.,_m.kom..png',
                'status_user' => 'aktif'
            ],
            [
                'username' => '198009162009122001',
                'nama' => 'Fitri Diani, S.Si., M.T.',
                'email' => 'fitri.diani@polban.ac.id',
                'password' => Hash::make('fitr123!#'),
                'role_user' => 'dosen',
                'no_whatsapp' => '081234567914',
                'photo' => 'fitri_diani,_s.si.,_m.t..png',
                'status_user' => 'aktif'
            ],
            [
                'username' => '198604122014041001',
                'nama' => 'Ghifari Munawar, S.T., M.T.',
                'email' => 'ghifari.munawar@polban.ac.id',
                'password' => Hash::make('ghif123!#'),
                'role_user' => 'dosen',
                'no_whatsapp' => '081234567915',
                'photo' => 'ghifari_munawar,_s.t.,_m.t..png',
                'status_user' => 'aktif'
            ],
            [
                'username' => '198502102015042001',
                'nama' => 'Ade Hodijah, S.T., M.T.',
                'email' => 'ade.hodijah@polban.ac.id',
                'password' => Hash::make('adeh123!#'),
                'role_user' => 'dosen',
                'no_whatsapp' => '081234567916',
                'photo' => 'ade_hodijah,_s.t.,_m.t..png',
                'status_user' => 'aktif'
            ],
            [
                'username' => '197604182001121004',
                'nama' => 'Iwan Awaludin, S.T., M.T.',
                'email' => 'iwan.awaludin@polban.ac.id',
                'password' => Hash::make('iwan123!#'),
                'role_user' => 'dosen',
                'no_whatsapp' => '081234567917',
                'photo' => 'iwan_awaludin,_s.t.,_m.t._.png',
                'status_user' => 'aktif'
            ],
            [
                'username' => '198012122008122001',
                'nama' => 'Ida Suhartini, S.Kom., MMSI.',
                'email' => 'ida.suhartini@polban.ac.id',
                'password' => Hash::make('idas123!#'),
                'role_user' => 'dosen',
                'no_whatsapp' => '081234567918',
                'photo' => 'ida_suhartini,_s.kom.,_mmsi..png',
                'status_user' => 'aktif'
            ],
            [
                'username' => '198004192005011002',
                'nama' => 'Irwan Setiawan, S.Si., M.T.',
                'email' => 'irwan.setiawan@polban.ac.id',
                'password' => Hash::make('irwa123!#'),
                'role_user' => 'dosen',
                'no_whatsapp' => '081234567919',
                'photo' => 'irwan_setiawan,_s.si.,_m.t..png',
                'status_user' => 'aktif'
            ],
            [
                'username' => '196208151990031001',
                'nama' => 'Irawan Thamrin, IR., M.T.',
                'email' => 'irawan.thamrin@polban.ac.id',
                'password' => Hash::make('iraw123!#'),
                'role_user' => 'dosen',
                'no_whatsapp' => '081234567920',
                'photo' => 'irawan_thamrin,_ir.,_m.t..png',
                'status_user' => 'aktif'
            ],
            [
                'username' => '198104072006041001',
                'nama' => 'Dr. Priyanto Hidayatullah, ST.,M.Sc.',
                'email' => 'priyanto.hidayatullah@polban.ac.id',
                'password' => Hash::make('dr.p123!#'),
                'role_user' => 'dosen',
                'no_whatsapp' => '081234567921',
                'photo' => 'dr._priyanto_hidayatullah,_st.,m.sc..png',
                'status_user' => 'aktif'
            ],
            [
                'username' => '196210211993031002',
                'nama' => 'Jonner Hutahaean, BSET., M.Info.Sys.',
                'email' => 'jonner.hutahaean@polban.ac.id',
                'password' => Hash::make('jonn123!#'),
                'role_user' => 'dosen',
                'no_whatsapp' => '081234567922',
                'photo' => 'jonner_hutahaean,_bset.,_m.info.sys..png',
                'status_user' => 'aktif'
            ],
            [
                'username' => '196610181995121001',
                'nama' => 'Joe Lian Min, M.Eng.',
                'email' => 'joe.lian@polban.ac.id',
                'password' => Hash::make('joel123!#'),
                'role_user' => 'dosen',
                'no_whatsapp' => '081234567923',
                'photo' => 'joe_lian_min,_m.eng..png',
                'status_user' => 'aktif'
            ],
            [
                'username' => '196312131992012001',
                'nama' => 'Dr. Nurjannah Syakrani, DRA., M.T.',
                'email' => 'nurjannah.syakrani@polban.ac.id',
                'password' => Hash::make('dr.n123!#'),
                'role_user' => 'dosen',
                'no_whatsapp' => '081234567924',
                'photo' => 'dr._nurjannah_syakrani,_dra.,_m.t..png',
                'status_user' => 'aktif'
            ],
            [
                'username' => '197109031999032001',
                'nama' => 'Santi Sundari, S.Si., M.T.',
                'email' => 'santi.sundari@polban.ac.id',
                'password' => Hash::make('sant123!#'),
                'role_user' => 'dosen',
                'no_whatsapp' => '081234567925',
                'photo' => 'santi_sundari,_s.si.,_m.t..png',
                'status_user' => 'aktif'
            ],
            [
                'username' => '196303161995121001',
                'nama' => 'Suprihanto, BSEE., M.Sc.',
                'email' => 'suprihanto@polban.ac.id',
                'password' => Hash::make('supr123!#'),
                'role_user' => 'dosen',
                'no_whatsapp' => '081234567926',
                'photo' => 'suprihanto,_bsee.,_m.sc..png',
                'status_user' => 'aktif'
            ],
            [
                'username' => '196904041998031001',
                'nama' => 'Setiadi Rachmat, M.Eng.',
                'email' => 'setiadi.rachmat@polban.ac.id',
                'password' => Hash::make('seti123!#'),
                'role_user' => 'dosen',
                'no_whatsapp' => '081234567927',
                'photo' => 'setiadi_rachmat,_m.eng..png',
                'status_user' => 'aktif'
            ],
            [
                'username' => '196111091993032001',
                'nama' => 'Dr. Transmissia Semiawan, BSCS., M.IT.',
                'email' => 'transmissia.semiawan@polban.ac.id',
                'password' => Hash::make('dr.t123!#'),
                'role_user' => 'dosen',
                'no_whatsapp' => '081234567928',
                'photo' => 'dr._transmissia_semiawan,_bscs.,_m.it..png',
                'status_user' => 'aktif'
            ],
            [
                'username' => '196009281994031001',
                'nama' => 'Urip Teguh Setijohatmo, BSCS., M.Kom.',
                'email' => 'urip.setijohatmo@polban.ac.id',
                'password' => Hash::make('urip123!#'),
                'role_user' => 'dosen',
                'no_whatsapp' => '081234567929',
                'photo' => 'urip_teguh_setijohatmo,_bscs.,_m.kom..png',
                'status_user' => 'aktif'
            ],
            [
                'username' => '197912242008121001',
                'nama' => 'Yadhi Adhitia P., S.T.',
                'email' => 'yadhi.adhitia@polban.ac.id',
                'password' => Hash::make('yadh123!#'),
                'role_user' => 'dosen',
                'no_whatsapp' => '081234567930',
                'photo' => 'yadhi_adhitia_p.,_s.t..png',
                'status_user' => 'aktif'
            ],
            [
                'username' => '197407182001121002',
                'nama' => 'Yudi Widhiyasana, S.Si., M.T.',
                'email' => 'yudi.widhiyasana@polban.ac.id',
                'password' => Hash::make('yudi123!#'),
                'role_user' => 'dosen',
                'no_whatsapp' => '081234567931',
                'photo' => 'yudi_widhiyasana,_s.si.,_m.t..png',
                'status_user' => 'aktif'
            ],
            [
                'username' => '198604212018031001',
                'nama' => 'Maisevli Harika, S.ST., M.T., M.Eng',
                'email' => 'maisevli.harika@polban.ac.id',
                'password' => Hash::make('mais123!#'),
                'role_user' => 'dosen',
                'no_whatsapp' => '081234567932',
                'photo' => 'maisevli_harika,_s.st.,_m.t.,_m.eng.png',
                'status_user' => 'aktif'
            ],
            [
                'username' => '198801292015041003',
                'nama' => 'Zulkifli Arsyad, S.T., M.T.',
                'email' => 'zulkifli.arsyad@polban.ac.id',
                'password' => Hash::make('zulk123!#'),
                'role_user' => 'dosen',
                'no_whatsapp' => '081234567933',
                'photo' => 'zulkifli_arsyad,_s.t.,_m.t..png',
                'status_user' => 'aktif'
            ],
            [
                'username' => '198705172019031004',
                'nama' => 'Akhmad Bakhrun, S.Kom, M.T.',
                'email' => 'akhmad.bakhrun@polban.ac.id',
                'password' => Hash::make('akhm123!#'),
                'role_user' => 'dosen',
                'no_whatsapp' => '081234567934',
                'photo' => 'akhmad_bakhrun,_s.kom,_m.t..png',
                'status_user' => 'aktif'
            ],
            [
                'username' => '199304262019032028',
                'nama' => 'Aprianti Nanda Sari, S.T., M.Kom.',
                'email' => 'aprianti.nanda@polban.ac.id',
                'password' => Hash::make('apri123!#'),
                'role_user' => 'dosen',
                'no_whatsapp' => '081234567935',
                'photo' => 'aprianti_nanda_sari,_s.t.,_m.kom..png',
                'status_user' => 'aktif'
            ],
            [
                'username' => '198405122019031008',
                'nama' => 'Ardhian Ekawijana, S.T., M.T.',
                'email' => 'ardhian.ekawijana@polban.ac.id',
                'password' => Hash::make('ardh123!#'),
                'role_user' => 'dosen',
                'no_whatsapp' => '081234567936',
                'photo' => 'ardhian_ekawijana,_s.t.,_m.t..png',
                'status_user' => 'aktif'
            ],
            [
                'username' => '198906102019032019',
                'nama' => 'Asri Maspupah, S.S.T., M.T.',
                'email' => 'asri.maspupah@polban.ac.id',
                'password' => Hash::make('asri123!#'),
                'role_user' => 'dosen',
                'no_whatsapp' => '081234567937',
                'photo' => 'asri_maspupah,_s.s.t.,_m.t..png',
                'status_user' => 'aktif'
            ],
            [
                'username' => '198911032019031019',
                'nama' => 'Beri Noviansyah, S.Kom., M.T.',
                'email' => 'beri.noviansyah@polban.ac.id',
                'password' => Hash::make('beri123!#'),
                'role_user' => 'dosen',
                'no_whatsapp' => '081234567938',
                'photo' => 'beri_noviansyah,_s.kom.,_m.t..png',
                'status_user' => 'aktif'
            ],
            [
                'username' => '198409012019031008',
                'nama' => 'Cholid Fauzi, S.T., M.T.',
                'email' => 'cholid.fauzi@polban.ac.id',
                'password' => Hash::make('chol123!#'),
                'role_user' => 'dosen',
                'no_whatsapp' => '081234567939',
                'photo' => 'cholid_fauzi,_s.t.,_m.t..png',
                'status_user' => 'aktif'
            ],
            [
                'username' => '199210222019032018',
                'nama' => 'Hashri Hayati, S.T., M.T.',
                'email' => 'hashri.hayati@polban.ac.id',
                'password' => Hash::make('hash123!#'),
                'role_user' => 'dosen',
                'no_whatsapp' => '081234567940',
                'photo' => 'hashri_hayati,_s.t.,_m.t..png',
                'status_user' => 'aktif'
            ],
            [
                'username' => '199301062019031017',
                'nama' => 'Lukmannul Hakim Firdaus, S.Kom., M.T.',
                'email' => 'lukmannul.hakim@polban.ac.id',
                'password' => Hash::make('lukm123!#'),
                'role_user' => 'dosen',
                'no_whatsapp' => '081234567941',
                'photo' => 'lukmannul_hakim_firdaus,_s.kom.,_m.t..png',
                'status_user' => 'aktif'
            ],
            [
                'username' => '199105302019031019',
                'nama' => 'Muhammad Rizqi Sholahuddin, S.Si., M.T.',
                'email' => 'muhammad.rizqi@polban.ac.id',
                'password' => Hash::make('muha123!#'),
                'role_user' => 'dosen',
                'no_whatsapp' => '081234567942',
                'photo' => 'muhammad_rizqi_sholahuddin,_s.si.,_m.t..png',
                'status_user' => 'aktif'
            ],
            [
                'username' => '199112182019032014',
                'nama' => 'Siti Dwi Setiarini, S.Si., M.T.',
                'email' => 'siti.setiarini@polban.ac.id',
                'password' => Hash::make('siti123!#'),
                'role_user' => 'dosen',
                'no_whatsapp' => '081234567943',
                'photo' => 'siti_dwi_setiarini,_s.si.,_m.t..png',
                'status_user' => 'aktif'
            ],
            [
                'username' => '198903252019032023',
                'nama' => 'Sri Ratna Wulan, S.Pd., M.T.',
                'email' => 'sri.ratna@polban.ac.id',
                'password' => Hash::make('srir123!#'),
                'role_user' => 'dosen',
                'no_whatsapp' => '081234567944',
                'photo' => 'sri_ratna_wulan,_s.pd.,_m.t..png',
                'status_user' => 'aktif'
            ],
            [
                'username' => '198608202019031014',
                'nama' => 'Trisna Gelar, S.T., M.Kom.',
                'email' => 'trisna.gelar@polban.ac.id',
                'password' => Hash::make('tris123!#'),
                'role_user' => 'dosen',
                'no_whatsapp' => '081234567945',
                'photo' => 'trisna_gelar,_s.t.,_m.kom..png',
                'status_user' => 'aktif'
            ],
            [
                'username' => '198706302019031011',
                'nama' => 'Wendi Wirasta, S.T., M.T.',
                'email' => 'wendi.wirasta@polban.ac.id',
                'password' => Hash::make('wend123!#'),
                'role_user' => 'dosen',
                'no_whatsapp' => '081234567946',
                'photo' => 'wendi_wirasta,_s.t.,_m.t..png',
                'status_user' => 'aktif'
            ],
            [
                'username' => '199003022019032019',
                'nama' => 'Rahil Jumiyani, S.ST., M.Sc.',
                'email' => 'rahil.jumiyani@polban.ac.id',
                'password' => Hash::make('rahi123!#'),
                'role_user' => 'dosen',
                'no_whatsapp' => '081234567947',
                'photo' => 'rahil_jumiyani,_s.st.,_m.sc..png',
                'status_user' => 'aktif'
            ],
            [
                'username' => '199312282019031013',
                'nama' => 'Djoko Cahyo Utomo Lieharyani, S.Kom., M.MT.',
                'email' => 'djoko.lieharyani@polban.ac.id',
                'password' => Hash::make('djok123!#'),
                'role_user' => 'dosen',
                'no_whatsapp' => '081234567948',
                'photo' => 'djoko_cahyo_utomo_lieharyani,_s.kom.,_m.mt..png',
                'status_user' => 'aktif'
            ],
            [
                'username' => '199209092019031015',
                'nama' => 'Muhammad Riza Alifi, S.T., M.T.',
                'email' => 'muhammad.riza@polban.ac.id',
                'password' => Hash::make('muha123!#'),
                'role_user' => 'dosen',
                'no_whatsapp' => '081234567949',
                'photo' => 'muhammad_riza_alifi,_s.t.,_m.t..png',
                'status_user' => 'aktif'
            ],
            [
                'username' => '199106142019032022',
                'nama' => 'Sofy Fitriani, S.S.T., M.Kom',
                'email' => 'sofy.fitriani@polban.ac.id',
                'password' => Hash::make('sofy123!#'),
                'role_user' => 'dosen',
                'no_whatsapp' => '081234567950',
                'photo' => 'sofy_fitriani,_s.s.t.,_m.kom.png',
                'status_user' => 'aktif'
            ],
            [
                'username' => '199106142019032000',
                'nama' => 'Lia Rahmawati',
                'email' => 'lia.rahmawati@polban.ac.id',
                'password' => Hash::make('liar123!#'),
                'role_user' => 'admin',
                'no_whatsapp' => '081234567950',
                'photo' => 'lia_rahmawati.png',
                'status_user' => 'aktif'
            ],
            [
                'username' => '221524033',
                'nama' => 'Alisha Nara Chandrakirana',
                'email' => 'alisha.nara.tif422@polban.ac.id',
                'password' => Hash::make('alis123!#'),
                'role_user' => 'mahasiswa',
                'no_whatsapp' => '081234567951',
                'photo' => 'alisha_nara_chandrakirana.png',
                'status_user' => 'aktif'
            ],
            [
                'username' => '221524034',
                'nama' => 'Arnanda Prasatya',
                'email' => 'arnanda.prasatya.tif422@polban.ac.id',
                'password' => Hash::make('arna123!#'),
                'role_user' => 'mahasiswa',
                'no_whatsapp' => '081234567952',
                'photo' => 'arnanda_prasatya.png',
                'status_user' => 'aktif'
            ],
            [
                'username' => '221524035',
                'nama' => 'Asri Husnul Rosadi',
                'email' => 'asri.husnul.tif422@polban.ac.id',
                'password' => Hash::make('asri123!#'),
                'role_user' => 'mahasiswa',
                'no_whatsapp' => '081234567953',
                'photo' => 'asri_husnul_rosadi.png',
                'status_user' => 'aktif'
            ],
            [
                'username' => '221524036',
                'nama' => 'Banteng Harisantoso',
                'email' => 'banteng.harisantoso.tif422@polban.ac.id',
                'password' => Hash::make('bant123!#'),
                'role_user' => 'mahasiswa',
                'no_whatsapp' => '081234567954',
                'photo' => 'banteng_harisantoso.png',
                'status_user' => 'aktif'
            ],
            [
                'username' => '221524037',
                'nama' => 'Bhisma Chandra Yudha Setiawan',
                'email' => 'bhisma.chandra.tif422@polban.ac.id',
                'password' => Hash::make('bhis123!#'),
                'role_user' => 'mahasiswa',
                'no_whatsapp' => '081234567955',
                'photo' => 'bhisma_chandra_yudha_setiawan.png',
                'status_user' => 'aktif'
            ],
            [
                'username' => '221524039',
                'nama' => 'Farhan Muhammad Luthfi',
                'email' => 'farhan.muhammad.tif422@polban.ac.id',
                'password' => Hash::make('farh123!#'),
                'role_user' => 'mahasiswa',
                'no_whatsapp' => '081234567956',
                'photo' => 'farhan_muhammad_luthfi.png',
                'status_user' => 'aktif'
            ],
            [
                'username' => '221524040',
                'nama' => 'Faris Abulkhoir',
                'email' => 'faris.abulkhoir.tif422@polban.ac.id',
                'password' => Hash::make('fari123!#'),
                'role_user' => 'mahasiswa',
                'no_whatsapp' => '081234567957',
                'photo' => 'faris_abulkhoir.png',
                'status_user' => 'aktif'
            ],
            [
                'username' => '221524041',
                'nama' => 'Ferdi Ahmad Ariesta',
                'email' => 'ferdi.ahmad.tif422@polban.ac.id',
                'password' => Hash::make('ferd123!#'),
                'role_user' => 'mahasiswa',
                'no_whatsapp' => '081234567958',
                'photo' => 'ferdi_ahmad_ariesta.png',
                'status_user' => 'aktif'
            ],
            [
                'username' => '221524042',
                'nama' => 'Jeihan Ilham Kusumawardhana',
                'email' => 'jeihan.ilham.tif422@polban.ac.id',
                'password' => Hash::make('jeih123!#'),
                'role_user' => 'mahasiswa',
                'no_whatsapp' => '081234567959',
                'photo' => 'jeihan_ilham_kusumawardhana.png',
                'status_user' => 'aktif'
            ],
            [
                'username' => '221524043',
                'nama' => 'Keanu Rayhan Harits',
                'email' => 'keanu.rayhan.tif422@polban.ac.id',
                'password' => Hash::make('kean123!#'),
                'role_user' => 'mahasiswa',
                'no_whatsapp' => '081234567960',
                'photo' => 'keanu_rayhan_harits.png',
                'status_user' => 'aktif'
            ],
            [
                'username' => '221524044',
                'nama' => 'Mahardika Pratama',
                'email' => 'mahardika.pratama.tif422@polban.ac.id',
                'password' => Hash::make('maha123!#'),
                'role_user' => 'mahasiswa',
                'no_whatsapp' => '081234567961',
                'photo' => 'mahardika_pratama.png',
                'status_user' => 'aktif'
            ],
            [
                'username' => '221524045',
                'nama' => 'Mochamad Fathur Rabbani',
                'email' => 'mochamad.fathur.tif422@polban.ac.id',
                'password' => Hash::make('moch123!#'),
                'role_user' => 'mahasiswa',
                'no_whatsapp' => '081234567962',
                'photo' => 'mochamad_fathur_rabbani.png',
                'status_user' => 'aktif'
            ],
            [
                'username' => '221524046',
                'nama' => 'Muhamad Agim',
                'email' => 'muhamad.agim.tif422@polban.ac.id',
                'password' => Hash::make('muha123!#'),
                'role_user' => 'mahasiswa',
                'no_whatsapp' => '081234567963',
                'photo' => 'muhamad_agim.png',
                'status_user' => 'aktif'
            ],
            [
                'username' => '221524047',
                'nama' => 'Muhamad Fahri Yuwan Dwi Putra',
                'email' => 'muhamad.fahri.tif422@polban.ac.id',
                'password' => Hash::make('muha123!#'),
                'role_user' => 'mahasiswa',
                'no_whatsapp' => '081234567964',
                'photo' => 'muhamad_fahri_yuwan_dwi_putra.png',
                'status_user' => 'aktif'
            ],
            [
                'username' => '221524049',
                'nama' => 'Muhammad Daffa',
                'email' => 'muhammad.daffa.tif422@polban.ac.id',
                'password' => Hash::make('muha123!#'),
                'role_user' => 'mahasiswa',
                'no_whatsapp' => '081234567965',
                'photo' => 'muhammad_daffa.png',
                'status_user' => 'aktif'
            ],
            [
                'username' => '221524050',
                'nama' => 'Muhammad Hanif',
                'email' => 'muhammad.hanif.tif422@polban.ac.id',
                'password' => Hash::make('muha123!#'),
                'role_user' => 'mahasiswa',
                'no_whatsapp' => '081234567966',
                'photo' => 'muhammad_hanif.png',
                'status_user' => 'aktif'
            ],
            [
                'username' => '221524051',
                'nama' => 'Muhammad Rizki Nurmuttaqin',
                'email' => 'muhammad.rizki.tif422@polban.ac.id',
                'password' => Hash::make('muha123!#'),
                'role_user' => 'mahasiswa',
                'no_whatsapp' => '081234567967',
                'photo' => 'muhammad_rizki_nurmuttaqin.png',
                'status_user' => 'aktif'
            ],
            [
                'username' => '221524052',
                'nama' => 'Naia Siti Az-Zahra',
                'email' => 'naia.siti.tif422@polban.ac.id',
                'password' => Hash::make('naia123!#'),
                'role_user' => 'mahasiswa',
                'no_whatsapp' => '081234567968',
                'photo' => 'naia_siti_az-zahra.png',
                'status_user' => 'aktif'
            ],
            [
                'username' => '221524053',
                'nama' => 'Najib Alimudin Fajri',
                'email' => 'najib.alimudin.tif422@polban.ac.id',
                'password' => Hash::make('naji123!#'),
                'role_user' => 'mahasiswa',
                'no_whatsapp' => '081234567969',
                'photo' => 'najib_alimudin_fajri.png',
                'status_user' => 'aktif'
            ],
            [
                'username' => '221524054',
                'nama' => 'Niqa Nabila Nur Ihsani',
                'email' => 'niqa.nabila.tif422@polban.ac.id',
                'password' => Hash::make('niqa123!#'),
                'role_user' => 'mahasiswa',
                'no_whatsapp' => '081234567970',
                'photo' => 'niqa_nabila_nur_ihsani.png',
                'status_user' => 'aktif'
            ],
            [
                'username' => '221524055',
                'nama' => 'Rafif Shabi Prasetyo',
                'email' => 'rafif.shabi.tif422@polban.ac.id',
                'password' => Hash::make('rafi123!#'),
                'role_user' => 'mahasiswa',
                'no_whatsapp' => '081234567971',
                'photo' => 'rafif_shabi_prasetyo.png',
                'status_user' => 'aktif'
            ],
            [
                'username' => '221524056',
                'nama' => 'Revandi Faudiamar Putra Sitepu',
                'email' => 'revandi.faudiamar.tif422@polban.ac.id',
                'password' => Hash::make('reva123!#'),
                'role_user' => 'mahasiswa',
                'no_whatsapp' => '081234567972',
                'photo' => 'revandi_faudiamar_putra_sitepu.png',
                'status_user' => 'aktif'
            ],
            [
                'username' => '221524057',
                'nama' => 'Reza Maulana Aziz',
                'email' => 'reza.maulana.tif422@polban.ac.id',
                'password' => Hash::make('reza123!#'),
                'role_user' => 'mahasiswa',
                'no_whatsapp' => '081234567973',
                'photo' => 'reza_maulana_aziz.png',
                'status_user' => 'aktif'
            ],
            [
                'username' => '221524058',
                'nama' => 'Salsabil Khoirunisa',
                'email' => 'salsabil.khoirunisa.tif422@polban.ac.id',
                'password' => Hash::make('sals123!#'),
                'role_user' => 'mahasiswa',
                'no_whatsapp' => '081234567974',
                'photo' => 'salsabil_khoirunisa.png',
                'status_user' => 'aktif'
            ],
            [
                'username' => '221524059',
                'nama' => 'Sarah',
                'email' => 'sarah.tif422@polban.ac.id',
                'password' => Hash::make('sara123!#'),
                'role_user' => 'mahasiswa',
                'no_whatsapp' => '081234567975',
                'photo' => 'sarah.png',
                'status_user' => 'aktif'
            ],
            [
                'username' => '221524060',
                'nama' => 'Septyana Agustina',
                'email' => 'septyana.agustina.tif422@polban.ac.id',
                'password' => Hash::make('sept123!#'),
                'role_user' => 'mahasiswa',
                'no_whatsapp' => '081234567976',
                'photo' => 'septyana_agustina.png',
                'status_user' => 'aktif'
            ],
            [
                'username' => '221524061',
                'nama' => 'Thoriq Muhammad Fadhli',
                'email' => 'thoriq.muhammad.tif422@polban.ac.id',
                'password' => Hash::make('thor123!#'),
                'role_user' => 'mahasiswa',
                'no_whatsapp' => '081234567977',
                'photo' => 'thoriq_muhammad_fadhli.png',
                'status_user' => 'aktif'
            ],
            [
                'username' => '221524062',
                'nama' => 'Yusuf',
                'email' => 'yusuf.tif422@polban.ac.id',
                'password' => Hash::make('yusu123!#'),
                'role_user' => 'mahasiswa',
                'no_whatsapp' => '081234567978',
                'photo' => 'yusuf.png',
                'status_user' => 'aktif'
            ],
            [
                'username' => '221524027',
                'nama' => 'Rayhan',
                'email' => 'rayhan.fanez.tif422@polban.ac.id',
                'password' => Hash::make('yusu123!#'),
                'role_user' => 'mahasiswa',
                'no_whatsapp' => '081234567978',
                'photo' => 'rayhan.png',
                'status_user' => 'aktif'
            ],
            [
                'username' => '221524030',
                'nama' => 'Roy',
                'email' => 'roy.aziz.tif422@polban.ac.id',
                'password' => Hash::make('yusu123!#'),
                'role_user' => 'mahasiswa',
                'no_whatsapp' => '081234567978',
                'photo' => 'roy.png',
                'status_user' => 'aktif'
            ],
            [
                'username' => '221524063',
                'nama' => 'Zahran Anugerah Rizqullah',
                'email' => 'zahran.anugerah.tif422@polban.ac.id',
                'password' => Hash::make('zahr123!#'),
                'role_user' => 'mahasiswa',
                'no_whatsapp' => '081234567979',
                'photo' => 'zahran_anugerah_rizqullah.png',
                'status_user' => 'aktif'
            ]
        ];


        foreach ($data as $item) {
            User::create($item);
        }
    }
}