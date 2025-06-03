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
                'email' => 'dummy@polban.ac.id', // Diubah
                'password' => Hash::make('liar123!#'),
                'role_user' => 'admin',
                'no_whatsapp' => '081234567950',
                'photo' => 'lia_rahmawati.png'
            ],
            // Koordinator TA
            [
                'username' => '199301062019031017',
                'nama' => 'Lukmannul Hakim Firdaus, S.Kom., M.T.',
                'email' => 'lukmannul.hakim@polban.ac.id', // Tetap
                'password' => Hash::make('lukm123!#'),
                'role_user' => 'dosen',
                'no_whatsapp' => '081234567941',
                'photo' => 'lukmannul_hakim_firdaus,_s.kom.,_m.t..png',
                'status_user' => 'aktif'
            ],
            [
                'username' => '198903252019032023',
                'nama' => 'Sri Ratna Wulan, S.Pd., M.T.',
                'email' => 'sri.ratna@polban.ac.id', // Tetap
                'password' => Hash::make('srir123!#'),
                'role_user' => 'dosen',
                'no_whatsapp' => '081234567944',
                'photo' => 'sri_ratna_wulan,_s.pd.,_m.t..png',
                'status_user' => 'aktif'
            ],
            // Dosen
            [
                'username' => '197312271999031003',
                'nama' => 'Ade Chandra Nugraha, S.Si., M.T.',
                'email' => 'ade.chandra.test@polban.ac.id',
                'password' => Hash::make('adec123!#'),
                'role_user' => 'dosen',
                'no_whatsapp' => '081234567912',
                'photo' => 'ade_chandra_nugraha,_s.si.,_m.t..png',
                'status_user' => 'aktif'
            ],
            [
                'username' => '196810141993032002',
                'nama' => 'Ani Rahmani, S.Si., M.T.',
                'email' => 'ani.rahmani.test@polban.ac.id',
                'password' => Hash::make('anir123!#'),
                'role_user' => 'dosen',
                'no_whatsapp' => '081234567912',
                'photo' => 'ani_rahmani,_s.si.,_m.t..png',
                'status_user' => 'aktif'
            ],
            [
                'username' => '197201061999031002',
                'nama' => 'Bambang Wisnuadhi, S.Si., M.T.',
                'email' => 'bambang.wisnuadhi.test@polban.ac.id',
                'password' => Hash::make('bamb123!#'),
                'role_user' => 'dosen',
                'no_whatsapp' => '081234567912',
                'photo' => 'bambang_wisnuadhi,_s.si.,_m.t..png',
                'status_user' => 'aktif'
            ],
            [
                'username' => '196012261992031001',
                'nama' => 'Didik Suwito Pribadi, BSCS.',
                'email' => 'didik.suwito.test@polban.ac.id',
                'password' => Hash::make('didi123!#'),
                'role_user' => 'dosen',
                'no_whatsapp' => '081234567912',
                'photo' => 'didik_suwito_pribadi,_bscs..png',
                'status_user' => 'aktif'
            ],
            [
                'username' => '196101141992021001',
                'nama' => 'Eddy B. Soewono, DRS., M.Kom.',
                'email' => 'eddy.soewono.test@polban.ac.id',
                'password' => Hash::make('eddy123!#'),
                'role_user' => 'dosen',
                'no_whatsapp' => '081234567913',
                'photo' => 'eddy_b._soewono,_drs.,_m.kom..png',
                'status_user' => 'aktif'
            ],
            [
                'username' => '198009162009122001',
                'nama' => 'Fitri Diani, S.Si., M.T.',
                'email' => 'fitri.diani.test@polban.ac.id',
                'password' => Hash::make('fitr123!#'),
                'role_user' => 'dosen',
                'no_whatsapp' => '081234567914',
                'photo' => 'fitri_diani,_s.si.,_m.t..png',
                'status_user' => 'aktif'
            ],
            [
                'username' => '198604122014041001',
                'nama' => 'Ghifari Munawar, S.T., M.T.',
                'email' => 'ghifari.munawar.test@polban.ac.id',
                'password' => Hash::make('ghif123!#'),
                'role_user' => 'dosen',
                'no_whatsapp' => '081234567915',
                'photo' => 'ghifari_munawar,_s.t.,_m.t..png',
                'status_user' => 'aktif'
            ],
            [
                'username' => '198502102015042001',
                'nama' => 'Ade Hodijah, S.T., M.T.',
                'email' => 'ade.hodijah.test@polban.ac.id',
                'password' => Hash::make('adeh123!#'),
                'role_user' => 'dosen',
                'no_whatsapp' => '081234567916',
                'photo' => 'ade_hodijah,_s.t.,_m.t..png',
                'status_user' => 'aktif'
            ],
            [
                'username' => '197604182001121004',
                'nama' => 'Iwan Awaludin, S.T., M.T.',
                'email' => 'iwan.awaludin.test@polban.ac.id',
                'password' => Hash::make('iwan123!#'),
                'role_user' => 'dosen',
                'no_whatsapp' => '081234567917',
                'photo' => 'iwan_awaludin,_s.t.,_m.t._.png',
                'status_user' => 'aktif'
            ],
            [
                'username' => '198012122008122001',
                'nama' => 'Ida Suhartini, S.Kom., MMSI.',
                'email' => 'ida.suhartini.test@polban.ac.id',
                'password' => Hash::make('idas123!#'),
                'role_user' => 'dosen',
                'no_whatsapp' => '081234567918',
                'photo' => 'ida_suhartini,_s.kom.,_mmsi..png',
                'status_user' => 'aktif'
            ],
            [
                'username' => '198004192005011002',
                'nama' => 'Irwan Setiawan, S.Si., M.T.',
                'email' => 'irwan.setiawan.test@polban.ac.id',
                'password' => Hash::make('irwa123!#'),
                'role_user' => 'dosen',
                'no_whatsapp' => '081234567919',
                'photo' => 'irwan_setiawan,_s.si.,_m.t..png',
                'status_user' => 'aktif'
            ],
            [
                'username' => '196208151990031001',
                'nama' => 'Irawan Thamrin, IR., M.T.',
                'email' => 'irawan.thamrin.test@polban.ac.id',
                'password' => Hash::make('iraw123!#'),
                'role_user' => 'dosen',
                'no_whatsapp' => '081234567920',
                'photo' => 'irawan_thamrin,_ir.,_m.t..png',
                'status_user' => 'aktif'
            ],
            [
                'username' => '198104072006041001',
                'nama' => 'Dr. Priyanto Hidayatullah, ST.,M.Sc.',
                'email' => 'priyanto.hidayatullah.test@polban.ac.id',
                'password' => Hash::make('dr.p123!#'),
                'role_user' => 'dosen',
                'no_whatsapp' => '081234567921',
                'photo' => 'dr._priyanto_hidayatullah,_st.,m.sc..png',
                'status_user' => 'aktif'
            ],
            [
                'username' => '196210211993031002',
                'nama' => 'Jonner Hutahaean, BSET., M.Info.Sys.',
                'email' => 'jonner.hutahaean.test@polban.ac.id',
                'password' => Hash::make('jonn123!#'),
                'role_user' => 'dosen',
                'no_whatsapp' => '081234567922',
                'photo' => 'jonner_hutahaean,_bset.,_m.info.sys..png',
                'status_user' => 'aktif'
            ],
            [
                'username' => '196610181995121001',
                'nama' => 'Joe Lian Min, M.Eng.',
                'email' => 'joe.lian.test@polban.ac.id',
                'password' => Hash::make('joel123!#'),
                'role_user' => 'dosen',
                'no_whatsapp' => '081234567923',
                'photo' => 'joe_lian_min,_m.eng..png',
                'status_user' => 'aktif'
            ],
            [
                'username' => '196312131992012001',
                'nama' => 'Dr. Nurjannah Syakrani, DRA., M.T.',
                'email' => 'nurjannah.syakrani.test@polban.ac.id',
                'password' => Hash::make('dr.n123!#'),
                'role_user' => 'dosen',
                'no_whatsapp' => '081234567924',
                'photo' => 'dr._nurjannah_syakrani,_dra.,_m.t..png',
                'status_user' => 'aktif'
            ],
            [
                'username' => '197109031999032001',
                'nama' => 'Santi Sundari, S.Si., M.T.',
                'email' => 'santi.sundari.test@polban.ac.id',
                'password' => Hash::make('sant123!#'),
                'role_user' => 'dosen',
                'no_whatsapp' => '081234567925',
                'photo' => 'santi_sundari,_s.si.,_m.t..png',
                'status_user' => 'aktif'
            ],
            [
                'username' => '196303161995121001',
                'nama' => 'Suprihanto, BSEE., M.Sc.',
                'email' => 'suprihanto.test@polban.ac.id',
                'password' => Hash::make('supr123!#'),
                'role_user' => 'dosen',
                'no_whatsapp' => '081234567926',
                'photo' => 'suprihanto,_bsee.,_m.sc..png',
                'status_user' => 'aktif'
            ],
            [
                'username' => '196904041998031001',
                'nama' => 'Setiadi Rachmat, M.Eng.',
                'email' => 'setiadi.rachmat.test@polban.ac.id',
                'password' => Hash::make('seti123!#'),
                'role_user' => 'dosen',
                'no_whatsapp' => '081234567927',
                'photo' => 'setiadi_rachmat,_m.eng..png',
                'status_user' => 'aktif'
            ],
            [
                'username' => '196111091993032001',
                'nama' => 'Dr. Transmissia Semiawan, BSCS., M.IT.',
                'email' => 'transmissia.semiawan.test@polban.ac.id',
                'password' => Hash::make('dr.t123!#'),
                'role_user' => 'dosen',
                'no_whatsapp' => '081234567928',
                'photo' => 'dr._transmissia_semiawan,_bscs.,_m.it..png',
                'status_user' => 'aktif'
            ],
            [
                'username' => '196009281994031001',
                'nama' => 'Urip Teguh Setijohatmo, BSCS., M.Kom.',
                'email' => 'urip.setijohatmo.test@polban.ac.id',
                'password' => Hash::make('urip123!#'),
                'role_user' => 'dosen',
                'no_whatsapp' => '081234567929',
                'photo' => 'urip_teguh_setijohatmo,_bscs.,_m.kom..png',
                'status_user' => 'aktif'
            ],
            [
                'username' => '197912242008121001',
                'nama' => 'Yadhi Adhitia P., S.T.',
                'email' => 'yadhi.adhitia.test@polban.ac.id',
                'password' => Hash::make('yadh123!#'),
                'role_user' => 'dosen',
                'no_whatsapp' => '081234567930',
                'photo' => 'yadhi_adhitia_p.,_s.t..png',
                'status_user' => 'aktif'
            ],
            [
                'username' => '197407182001121002',
                'nama' => 'Yudi Widhiyasana, S.Si., M.T.',
                'email' => 'yudi.widhiyasana.test@polban.ac.id',
                'password' => Hash::make('yudi123!#'),
                'role_user' => 'dosen',
                'no_whatsapp' => '081234567931',
                'photo' => 'yudi_widhiyasana,_s.si.,_m.t..png',
                'status_user' => 'aktif'
            ],
            [
                'username' => '198604212018031001',
                'nama' => 'Maisevli Harika, S.ST., M.T., M.Eng',
                'email' => 'maisevli.harika.test@polban.ac.id',
                'password' => Hash::make('mais123!#'),
                'role_user' => 'dosen',
                'no_whatsapp' => '081234567932',
                'photo' => 'maisevli_harika,_s.st.,_m.t.,_m.eng.png',
                'status_user' => 'aktif'
            ],
            [
                'username' => '198801292015041003',
                'nama' => 'Zulkifli Arsyad, S.T., M.T.',
                'email' => 'zulkifli.arsyad.test@polban.ac.id',
                'password' => Hash::make('zulk123!#'),
                'role_user' => 'dosen',
                'no_whatsapp' => '081234567933',
                'photo' => 'zulkifli_arsyad,_s.t.,_m.t..png',
                'status_user' => 'aktif'
            ],
            [
                'username' => '198705172019031004',
                'nama' => 'Akhmad Bakhrun, S.Kom, M.T.',
                'email' => 'akhmad.bakhrun.test@polban.ac.id',
                'password' => Hash::make('akhm123!#'),
                'role_user' => 'dosen',
                'no_whatsapp' => '081234567934',
                'photo' => 'akhmad_bakhrun,_s.kom,_m.t..png',
                'status_user' => 'aktif'
            ],
            [
                'username' => '199304262019032028',
                'nama' => 'Aprianti Nanda Sari, S.T., M.Kom.',
                'email' => 'aprianti.nanda.test@polban.ac.id',
                'password' => Hash::make('apri123!#'),
                'role_user' => 'dosen',
                'no_whatsapp' => '081234567935',
                'photo' => 'aprianti_nanda_sari,_s.t.,_m.kom..png',
                'status_user' => 'aktif'
            ],
            [
                'username' => '198405122019031008',
                'nama' => 'Ardhian Ekawijana, S.T., M.T.',
                'email' => 'ardhian.ekawijana.test@polban.ac.id',
                'password' => Hash::make('ardh123!#'),
                'role_user' => 'dosen',
                'no_whatsapp' => '081234567936',
                'photo' => 'ardhian_ekawijana,_s.t.,_m.t..png',
                'status_user' => 'aktif'
            ],
            [
                'username' => '198906102019032019',
                'nama' => 'Asri Maspupah, S.S.T., M.T.',
                'email' => 'asri.maspupah.test@polban.ac.id',
                'password' => Hash::make('asri123!#'),
                'role_user' => 'dosen',
                'no_whatsapp' => '081234567937',
                'photo' => 'asri_maspupah,_s.s.t.,_m.t..png',
                'status_user' => 'aktif'
            ],
            [
                'username' => '198911032019031019',
                'nama' => 'Beri Noviansyah, S.Kom., M.T.',
                'email' => 'beri.noviansyah.test@polban.ac.id',
                'password' => Hash::make('beri123!#'),
                'role_user' => 'dosen',
                'no_whatsapp' => '081234567938',
                'photo' => 'beri_noviansyah,_s.kom.,_m.t..png',
                'status_user' => 'aktif'
            ],
            [
                'username' => '198409012019031008',
                'nama' => 'Cholid Fauzi, S.T., M.T.',
                'email' => 'cholid.fauzi.test@polban.ac.id',
                'password' => Hash::make('chol123!#'),
                'role_user' => 'dosen',
                'no_whatsapp' => '081234567939',
                'photo' => 'cholid_fauzi,_s.t.,_m.t..png',
                'status_user' => 'aktif'
            ],
            [
                'username' => '199210222019032018',
                'nama' => 'Hashri Hayati, S.T., M.T.',
                'email' => 'hashri.hayati.test@polban.ac.id',
                'password' => Hash::make('hash123!#'),
                'role_user' => 'dosen',
                'no_whatsapp' => '081234567940',
                'photo' => 'hashri_hayati,_s.t.,_m.t..png',
                'status_user' => 'aktif'
            ],
            [
                'username' => '199301062019031017',
                'nama' => 'Lukmannul Hakim Firdaus, S.Kom., M.T.',
                'email' => 'lukmannul.hakim.test@polban.ac.id',
                'password' => Hash::make('lukm123!#'),
                'role_user' => 'dosen',
                'no_whatsapp' => '081234567941',
                'photo' => 'lukmannul_hakim_firdaus,_s.kom.,_m.t..png',
                'status_user' => 'aktif'
            ],
            [
                'username' => '199105302019031019',
                'nama' => 'Muhammad Rizqi Sholahuddin, S.Si., M.T.',
                'email' => 'muhammad.rizqi.test@polban.ac.id',
                'password' => Hash::make('muha123!#'),
                'role_user' => 'dosen',
                'no_whatsapp' => '081234567942',
                'photo' => 'muhammad_rizqi_sholahuddin,_s.si.,_m.t..png',
                'status_user' => 'aktif'
            ],
            [
                'username' => '199112182019032014',
                'nama' => 'Siti Dwi Setiarini, S.Si., M.T.',
                'email' => 'siti.setiarini.test@polban.ac.id',
                'password' => Hash::make('siti123!#'),
                'role_user' => 'dosen',
                'no_whatsapp' => '081234567943',
                'photo' => 'siti_dwi_setiarini,_s.si.,_m.t..png',
                'status_user' => 'aktif'
            ],
            [
                'username' => '198903252019032023',
                'nama' => 'Sri Ratna Wulan, S.Pd., M.T.',
                'email' => 'sri.ratna.test@polban.ac.id',
                'password' => Hash::make('srir123!#'),
                'role_user' => 'dosen',
                'no_whatsapp' => '081234567944',
                'photo' => 'sri_ratna_wulan,_s.pd.,_m.t..png',
                'status_user' => 'aktif'
            ],
            [
                'username' => '198608202019031014',
                'nama' => 'Trisna Gelar, S.T., M.Kom.',
                'email' => 'trisna.gelar.test@polban.ac.id',
                'password' => Hash::make('tris123!#'),
                'role_user' => 'dosen',
                'no_whatsapp' => '081234567945',
                'photo' => 'trisna_gelar,_s.t.,_m.kom..png',
                'status_user' => 'aktif'
            ],
            [
                'username' => '198706302019031011',
                'nama' => 'Wendi Wirasta, S.T., M.T.',
                'email' => 'wendi.wirasta.test@polban.ac.id',
                'password' => Hash::make('wend123!#'),
                'role_user' => 'dosen',
                'no_whatsapp' => '081234567946',
                'photo' => 'wendi_wirasta,_s.t.,_m.t..png',
                'status_user' => 'aktif'
            ],
            [
                'username' => '199003022019032019',
                'nama' => 'Rahil Jumiyani, S.ST., M.Sc.',
                'email' => 'rahil.jumiyani.test@polban.ac.id',
                'password' => Hash::make('rahi123!#'),
                'role_user' => 'dosen',
                'no_whatsapp' => '081234567947',
                'photo' => 'rahil_jumiyani,_s.st.,_m.sc..png',
                'status_user' => 'aktif'
            ],
            [
                'username' => '199312282019031013',
                'nama' => 'Djoko Cahyo Utomo Lieharyani, S.Kom., M.MT.',
                'email' => 'djoko.lieharyani.test@polban.ac.id',
                'password' => Hash::make('djok123!#'),
                'role_user' => 'dosen',
                'no_whatsapp' => '081234567948',
                'photo' => 'djoko_cahyo_utomo_lieharyani,_s.kom.,_m.mt..png',
                'status_user' => 'aktif'
            ],
            [
                'username' => '199209092019031015',
                'nama' => 'Muhammad Riza Alifi, S.T., M.T.',
                'email' => 'muhammad.riza.test@polban.ac.id',
                'password' => Hash::make('muha123!#'),
                'role_user' => 'dosen',
                'no_whatsapp' => '081234567949',
                'photo' => 'muhammad_riza_alifi,_s.t.,_m.t..png',
                'status_user' => 'aktif'
            ],
            [
                'username' => '199106142019032022',
                'nama' => 'Sofy Fitriani, S.S.T., M.Kom',
                'email' => 'sofy.fitriani.test@polban.ac.id',
                'password' => Hash::make('sofy123!#'),
                'role_user' => 'dosen',
                'no_whatsapp' => '081234567950',
                'photo' => 'sofy_fitriani,_s.s.t.,_m.kom.png',
                'status_user' => 'aktif'
            ],
            // Mahasiswa
            [
                'username' => '221511001',
                'nama' => 'Agam Andika',
                'email' => 'agam.andika.tif22.test@polban.ac.id',
                'password' => Hash::make('agam123!#'),
                'role_user' => 'mahasiswa',
                'no_whatsapp' => '0895344350956',
                'photo' => 'agam_andika.png',
                'status_user' => 'aktif'
            ],
            [
                'username' => '221511021',
                'nama' => 'Muhammad Jalaludin Qurthubi',
                'email' => 'muhammad.jalaludin.tif22.test@polban.ac.id',
                'password' => Hash::make('muha123!#'),
                'role_user' => 'mahasiswa',
                'no_whatsapp' => '088219868501',
                'photo' => 'muhammad_jalaludin_qurthubi.png',
                'status_user' => 'aktif'
            ],
            [
                'username' => '221511028',
                'nama' => 'Ridha Septiaji',
                'email' => 'ridha.septiaji.tif22.test@polban.ac.id',
                'password' => Hash::make('ridh123!#'),
                'role_user' => 'mahasiswa',
                'no_whatsapp' => '082388611299',
                'photo' => 'ridha_septiaji.png',
                'status_user' => 'aktif'
            ],
            [
                'username' => '221511002',
                'nama' => 'Aryagara Kristandy Rukmana Putra',
                'email' => 'aryagara.kristandy.tif22.test@polban.ac.id',
                'password' => Hash::make('arya123!#'),
                'role_user' => 'mahasiswa',
                'no_whatsapp' => '082217456337',
                'photo' => 'aryagara_kristandy_rukmana_putra.png',
                'status_user' => 'aktif'
            ],
            [
                'username' => '221511022',
                'nama' => 'Mutia Hardita',
                'email' => 'mutia.hardita.tif22.test@polban.ac.id',
                'password' => Hash::make('muti123!#'),
                'role_user' => 'mahasiswa',
                'no_whatsapp' => '082298980104',
                'photo' => 'mutia_hardita.png',
                'status_user' => 'aktif'
            ],
            [
                'username' => '221511032',
                'nama' => 'Xaviera Sadiya Salsabeel',
                'email' => 'xaviera.sadiya.tif22.test@polban.ac.id',
                'password' => Hash::make('xavi123!#'),
                'role_user' => 'mahasiswa',
                'no_whatsapp' => '081380565026',
                'photo' => 'xaviera_sadiya_salsabeel.png',
                'status_user' => 'aktif'
            ],
            [
                'username' => '221511003',
                'nama' => 'Athalie Aurora Puspanegara',
                'email' => 'athalie.aurora.tif22.test@polban.ac.id',
                'password' => Hash::make('atha123!#'),
                'role_user' => 'mahasiswa',
                'no_whatsapp' => '082119319812',
                'photo' => 'athalie_aurora_puspanegara.png',
                'status_user' => 'aktif'
            ],
            [
                'username' => '221511004',
                'nama' => 'Aulia Aziizah Fauziyyah',
                'email' => 'aulia.aziizah.tif22.test@polban.ac.id',
                'password' => Hash::make('auli123!#'),
                'role_user' => 'mahasiswa',
                'no_whatsapp' => '081222875167',
                'photo' => 'aulia_aziizah_fauziyyah.png',
                'status_user' => 'aktif'
            ],
            [
                'username' => '221511015',
                'nama' => 'Jonanda Pantas Agitha Brahmana',
                'email' => 'jonanda.pantas.tif22.test@polban.ac.id',
                'password' => Hash::make('jona123!#'),
                'role_user' => 'mahasiswa',
                'no_whatsapp' => '082360579753',
                'photo' => 'jonanda_pantas_agitha_brahmana.png',
                'status_user' => 'aktif'
            ],
            [
                'username' => '221511026',
                'nama' => 'Paulina Lestari Simatupang',
                'email' => 'paulina.lestari.tif22.test@polban.ac.id',
                'password' => Hash::make('paul123!#'),
                'role_user' => 'mahasiswa',
                'no_whatsapp' => '085959592907',
                'photo' => 'paulina_lestari_simatupang.png',
                'status_user' => 'aktif'
            ],
            [
                'username' => '221511005',
                'nama' => 'Aulia Nurul Fauziah',
                'email' => 'aulia.nurul.tif22.test@polban.ac.id',
                'password' => Hash::make('auli123!#'),
                'role_user' => 'mahasiswa',
                'no_whatsapp' => '085797978572',
                'photo' => 'aulia_nurul_fauziah.png',
                'status_user' => 'aktif'
            ],
            [
                'username' => '221511009',
                'nama' => 'Fathia Qurrata Aini Yuner',
                'email' => 'fathia.qurrata.tif22.test@polban.ac.id',
                'password' => Hash::make('fath123!#'),
                'role_user' => 'mahasiswa',
                'no_whatsapp' => '081311335085',
                'photo' => 'fathia_qurrata_aini_yuner.png',
                'status_user' => 'aktif'
            ],
            [
                'username' => '221511029',
                'nama' => 'Ryanda Afriza',
                'email' => 'ryanda.afriza.tif22.test@polban.ac.id',
                'password' => Hash::make('ryan123!#'),
                'role_user' => 'mahasiswa',
                'no_whatsapp' => '0881022129810',
                'photo' => 'ryanda_afriza.png',
                'status_user' => 'aktif'
            ],
            [
                'username' => '221511006',
                'nama' => 'Brahmantya Ndaru Taja Bagus Santoso',
                'email' => 'brahmantya.ndaru.tif22.test@polban.ac.id',
                'password' => Hash::make('brah123!#'),
                'role_user' => 'mahasiswa',
                'no_whatsapp' => '085793156416',
                'photo' => 'brahmantya_ndaru_taja_bagus_santoso.png',
                'status_user' => 'aktif'
            ],
            [
                'username' => '221511013',
                'nama' => 'Hafidzon Al Hibrizi',
                'email' => 'hafidzon.al.tif22.test@polban.ac.id',
                'password' => Hash::make('hafi123!#'),
                'role_user' => 'mahasiswa',
                'no_whatsapp' => '085157883965',
                'photo' => 'hafidzon_al_hibrizi.png',
                'status_user' => 'aktif'
            ],
            [
                'username' => '221511031',
                'nama' => 'Vico Triansyah Nasril',
                'email' => 'vico.triansyah.tif22.test@polban.ac.id',
                'password' => Hash::make('vico123!#'),
                'role_user' => 'mahasiswa',
                'no_whatsapp' => '081224065281',
                'photo' => 'vico_triansyah_nasril.png',
                'status_user' => 'aktif'
            ],
            // Untuk keperluan demo
            // [
            //     'username' => '221511007',
            //     'nama' => 'Canandra Eka Mukti',
            //     'email' => 'canandra.eka.tif22.test@polban.ac.id',
            //     'password' => Hash::make('cana123!#'),
            //     'role_user' => 'mahasiswa',
            //     'no_whatsapp' => '087820236109',
            //     'photo' => 'canandra_eka_mukti.png',
            //     'status_user' => 'aktif'
            // ],
            [
                'username' => '221511023',
                'nama' => 'Najwan Zaky Ahmad',
                'email' => 'najwan.zaky.tif22.test@polban.ac.id',
                'password' => Hash::make('najw123!#'),
                'role_user' => 'mahasiswa',
                'no_whatsapp' => '087846667722',
                'photo' => 'najwan_zaky_ahmad.png',
                'status_user' => 'aktif'
            ],
            [
                'username' => '221511030',
                'nama' => 'Stefanus Rico Pandapotan Situngkir',
                'email' => 'stefanus.rico.tif22.test@polban.ac.id',
                'password' => Hash::make('stef123!#'),
                'role_user' => 'mahasiswa',
                'no_whatsapp' => '081211356560',
                'photo' => 'stefanus_rico_pandapotan_situngkir.png',
                'status_user' => 'aktif'
            ],
            [
                'username' => '221511008',
                'nama' => 'Claudia Berlian Harli',
                'email' => 'claudia.berlian.tif22.test@polban.ac.id',
                'password' => Hash::make('clau123!#'),
                'role_user' => 'mahasiswa',
                'no_whatsapp' => '08987418711',
                'photo' => 'claudia_berlian_harli.png',
                'status_user' => 'aktif'
            ],
            [
                'username' => '221511011',
                'nama' => 'Gavrila Hana Simanjuntak',
                'email' => 'gavrila.hana.tif22.test@polban.ac.id',
                'password' => Hash::make('gavr123!#'),
                'role_user' => 'mahasiswa',
                'no_whatsapp' => '0895703057807',
                'photo' => 'gavrila_hana_simanjuntak.png',
                'status_user' => 'aktif'
            ],
            [
                'username' => '221511027',
                'nama' => 'Rahaditya Muhammad Damar Riyadhi',
                'email' => 'rahaditya.muhammad.tif22.test@polban.ac.id',
                'password' => Hash::make('raha123!#'),
                'role_user' => 'mahasiswa',
                'no_whatsapp' => '08979270002',
                'photo' => 'rahaditya_muhammad_damar_riyadhi.png',
                'status_user' => 'aktif'
            ],
            [
                'username' => '221511010',
                'nama' => 'Fauza Naylassana',
                'email' => 'fauza.naylassana.tif22.test@polban.ac.id',
                'password' => Hash::make('fauz123!#'),
                'role_user' => 'mahasiswa',
                'no_whatsapp' => '085939272379',
                'photo' => 'fauza_naylassana.png',
                'status_user' => 'aktif'
            ],
            [
                'username' => '221511020',
                'nama' => 'Muhammad Difa Alghifary',
                'email' => 'muhammad.difa.tif22.test@polban.ac.id',
                'password' => Hash::make('muha123!#'),
                'role_user' => 'mahasiswa',
                'no_whatsapp' => '087746503986',
                'photo' => 'muhammad_difa_alghifary.png',
                'status_user' => 'aktif'
            ],
            [
                'username' => '221511025',
                'nama' => 'Nisrina Wafa Zakiya Hamdani',
                'email' => 'nisrina.wafa.tif22.test@polban.ac.id',
                'password' => Hash::make('nisr123!#'),
                'role_user' => 'mahasiswa',
                'no_whatsapp' => '089626188265',
                'photo' => 'nisrina_wafa_zakiya_hamdani.png',
                'status_user' => 'aktif'
            ],
            [
                'username' => '221511012',
                'nama' => 'Gian Vilcan Patra',
                'email' => 'gian.vilcan.tif22.test@polban.ac.id',
                'password' => Hash::make('gian123!#'),
                'role_user' => 'mahasiswa',
                'no_whatsapp' => '082283398557',
                'photo' => 'gian_vilcan_patra.png',
                'status_user' => 'aktif'
            ],
            [
                'username' => '221511016',
                'nama' => 'M Naufal Fadil Aziz',
                'email' => 'm.naufal.tif22.test@polban.ac.id',
                'password' => Hash::make('mnau123!#'),
                'role_user' => 'mahasiswa',
                'no_whatsapp' => '088905298517',
                'photo' => 'm_naufal_fadil_aziz.png',
                'status_user' => 'aktif'
            ],
            [
                'username' => '221511018',
                'nama' => 'Muhamad Fatah Rozaq',
                'email' => 'muhamad.fatah.tif22.test@polban.ac.id',
                'password' => Hash::make('muha123!#'),
                'role_user' => 'mahasiswa',
                'no_whatsapp' => '083829534910',
                'photo' => 'muhamad_fatah_rozaq.png',
                'status_user' => 'aktif'
            ],
            [
                'username' => '221511033',
                'nama' => 'Yosua Balingga',
                'email' => 'yosua.balingga.tif22.test@polban.ac.id',
                'password' => Hash::make('yosu123!#'),
                'role_user' => 'mahasiswa', 
                'no_whatsapp' => '0882001927007',
                'photo' => 'yosua_balingga.png',
                'status_user' => 'aktif'
            ],
            [
                'username' => '221511024',
                'nama' => 'Naufal Syafiq Somantri',
                'email' => 'naufal.syafiq.tif22.test@polban.ac.id',
                'password' => Hash::make('nauf123!#'),
                'role_user' => 'mahasiswa',
                'no_whatsapp' => '085156185355',
                'photo' => 'naufal_syafiq_somantri.png',
                'status_user' => 'aktif'
            ],
            [
                'username' => '221511034',
                'nama' => 'Adhiya Rahma Anzani',
                'email' => 'adhiya.rahma.tif22.test@polban.ac.id',
                'password' => Hash::make('adhi123!#'),
                'role_user' => 'mahasiswa',
                'no_whatsapp' => '0895344276708',
                'photo' => 'adhiya_rahma_anzani.png',
                'status_user' => 'aktif'
            ],
            [
                'username' => '221511046',
                'nama' => 'Danendra Gafrila',
                'email' => 'danendra.gafrila.tif22.test@polban.ac.id',
                'password' => Hash::make('dane123!#'),
                'role_user' => 'mahasiswa',
                'no_whatsapp' => '085156313240',
                'photo' => 'danendra_gafrila.png',
                'status_user' => 'aktif'
            ],
            [
                'username' => '221511052',
                'nama' => 'Linda Santika',
                'email' => 'linda.santika.tif22.test@polban.ac.id',
                'password' => Hash::make('lind123!#'),
                'role_user' => 'mahasiswa',
                'no_whatsapp' => '083896783162',
                'photo' => 'linda_santika.png',
                'status_user' => 'aktif'
            ],
            [
                'username' => '221511035',
                'nama' => 'Adinda Raisa Az-Zahra',
                'email' => 'adinda.raisa.tif22.test@polban.ac.id',
                'password' => Hash::make('adin123!#'),
                'role_user' => 'mahasiswa',
                'no_whatsapp' => '08990918911',
                'photo' => 'adinda_raisa_az-zahra.png',
                'status_user' => 'aktif'
            ],
            [
                'username' => '221511041',
                'nama' => 'Alfien Sukma Prawira',
                'email' => 'alfien.sukma.tif22.test@polban.ac.id',
                'password' => Hash::make('alfi123!#'),
                'role_user' => 'mahasiswa',
                'no_whatsapp' => '081221945135',
                'photo' => 'alfien_sukma_prawira.png',
                'status_user' => 'aktif'
            ],
            [
                'username' => '221511054',
                'nama' => 'Mahesya Setia Nugraha',
                'email' => 'mahesya.setia.tif22.test@polban.ac.id',
                'password' => Hash::make('mahe123!#'),
                'role_user' => 'mahasiswa',
                'no_whatsapp' => '085163150713',
                'photo' => 'mahesya_setia_nugraha.png',
                'status_user' => 'aktif'
            ],
            [
                'username' => '221511037',
                'nama' => 'Afyar Siti Ababil',
                'email' => 'afyar.siti.tif22.test@polban.ac.id',
                'password' => Hash::make('afya123!#'),
                'role_user' => 'mahasiswa',
                'no_whatsapp' => '082119867092',
                'photo' => 'afyar_siti_ababil.png',
                'status_user' => 'aktif'
            ],
            [
                'username' => '221511058',
                'nama' => 'Muhammad Ikhsan Maulana Taqwim',
                'email' => 'muhammad.ikhsan.tif22.test@polban.ac.id',
                'password' => Hash::make('muha123!#'),
                'role_user' => 'mahasiswa',
                'no_whatsapp' => '081563939174',
                'photo' => 'muhammad_ikhsan_maulana_taqwim.png',
                'status_user' => 'aktif'
            ],
            [
                'username' => '221511063',
                'nama' => 'Rizki Gunawan',
                'email' => 'rizki.gunawan.tif22.test@polban.ac.id',
                'password' => Hash::make('rizk123!#'),
                'role_user' => 'mahasiswa',
                'no_whatsapp' => '082118078627',
                'photo' => 'rizki_gunawan.png',
                'status_user' => 'aktif'
            ],
            [
                'username' => '221511042',
                'nama' => 'Alya Angraini',
                'email' => 'alya.angraini.tif22.test@polban.ac.id',
                'password' => Hash::make('alya123!#'),
                'role_user' => 'mahasiswa',
                'no_whatsapp' => '087781168620',
                'photo' => 'alya_angraini.png',
                'status_user' => 'aktif'
            ],
            [
                'username' => '221511045',
                'nama' => 'Barry Arganeza',
                'email' => 'barry.arganeza.tif22.test@polban.ac.id',
                'password' => Hash::make('barr123!#'),
                'role_user' => 'mahasiswa',
                'no_whatsapp' => '081222702696',
                'photo' => 'barry_arganeza.png',
                'status_user' => 'aktif'
            ],
            [
                'username' => '221511048',
                'nama' => 'Fadel Mohammad Fadillah',
                'email' => 'fadel.mohammad.tif22.test@polban.ac.id',
                'password' => Hash::make('fade123!#'),
                'role_user' => 'mahasiswa',
                'no_whatsapp' => '087802548693',
                'photo' => 'fadel_mohammad_fadillah.png',
                'status_user' => 'aktif'
            ],
            [
                'username' => '221511043',
                'nama' => 'Aqila Ghifari Wandana',
                'email' => 'aqila.ghifari.tif22.test@polban.ac.id',
                'password' => Hash::make('aqil123!#'),
                'role_user' => 'mahasiswa',
                'no_whatsapp' => '081122333518',
                'photo' => 'aqila_ghifari_wandana.png',
                'status_user' => 'aktif'
            ],
            [
                'username' => '221511051',
                'nama' => 'Fikri Hairul Fahri',
                'email' => 'fikri.hairul.tif22.test@polban.ac.id',
                'password' => Hash::make('fikr123!#'),
                'role_user' => 'mahasiswa',
                'no_whatsapp' => '085771600674',
                'photo' => 'fikri_hairul_fahri.png',
                'status_user' => 'aktif'
            ],
            [
                'username' => '221511065',
                'nama' => 'Taufik Muhamad Ramadhan',
                'email' => 'taufik.muhamad.tif22.test@polban.ac.id',
                'password' => Hash::make('tauf123!#'),
                'role_user' => 'mahasiswa',
                'no_whatsapp' => '085975160501',
                'photo' => 'taufik_muhamad_ramadhan.png',
                'status_user' => 'aktif'
            ],
            [
                'username' => '221511040',
                'nama' => 'Ahmad Fauzy',
                'email' => 'ahmad.fauzy.tif22.test@polban.ac.id',
                'password' => Hash::make('ahma123!#'),
                'role_user' => 'mahasiswa',
                'no_whatsapp' => '081312396135',
                'photo' => 'ahmad_fauzy.png',
                'status_user' => 'aktif'
            ],
            [
                'username' => '221511060',
                'nama' => 'Muhammad Syaifullah',
                'email' => 'muhammad.syaifullah.tif22.test@polban.ac.id',
                'password' => Hash::make('muha123!#'),
                'role_user' => 'mahasiswa',
                'no_whatsapp' => '081215951245',
                'photo' => 'muhammad_syaifullah.png',
                'status_user' => 'aktif'
            ],
            [
                'username' => '221511066',
                'nama' => 'Tendy Wijaya',
                'email' => 'tendy.wijaya.tif22.test@polban.ac.id',
                'password' => Hash::make('tend123!#'),
                'role_user' => 'mahasiswa',
                'no_whatsapp' => '087782131088',
                'photo' => 'tendy_wijaya.png',
                'status_user' => 'aktif'
            ],
            [
                'username' => '221511039',
                'nama' => 'Ahmad Al-Fazri Kusmana',
                'email' => 'Ahmad.alfazri.tif22.test@gmail.com',
                'password' => Hash::make('ahma123!#'),
                'role_user' => 'mahasiswa',
                'no_whatsapp' => '089513810625',
                'photo' => 'ahmad_al-fazri_kusmana.png',
                'status_user' => 'aktif'
            ],
            [
                'username' => '221511057',
                'nama' => 'Muhammad Faisal Adha',
                'email' => 'muhammad.faisal.tif22.test@polban.ac.id',
                'password' => Hash::make('muha123!#'),
                'role_user' => 'mahasiswa',
                'no_whatsapp' => '085155072811',
                'photo' => 'muhammad_faisal_adha.png',
                'status_user' => 'aktif'
            ],
            [
                'username' => '221511059',
                'nama' => 'Muhammad Rafi Atha Syauqi',
                'email' => 'muhammad.rafi.tif22.test@polban.ac.id',
                'password' => Hash::make('muha123!#'),
                'role_user' => 'mahasiswa',
                'no_whatsapp' => '082318943335',
                'photo' => 'muhammad_rafi_atha_syauqi.png',
                'status_user' => 'aktif'
            ],
            [
                'username' => '221511038',
                'nama' => 'Agista Diva Briliani',
                'email' => 'agista.diva.tif22.test@polban.ac.id',
                'password' => Hash::make('agis123!#'),
                'role_user' => 'mahasiswa',
                'no_whatsapp' => '085624334143',
                'photo' => 'agista_diva_briliani.png',
                'status_user' => 'aktif'
            ],
            [
                'username' => '221511062',
                'nama' => 'Reno Sebastian Nugraha',
                'email' => 'reno.sebastian.tif22.test@polban.ac.id',
                'password' => Hash::make('reno123!#'),
                'role_user' => 'mahasiswa',
                'no_whatsapp' => '082258790636',
                'photo' => 'reno_sebastian_nugraha.png',
                'status_user' => 'aktif'
            ],
            [
                'username' => '221511064',
                'nama' => 'Syira Khoerunisa',
                'email' => 'syira.khoerunisa.tif22.test@polban.ac.id',
                'password' => Hash::make('syir123!#'),
                'role_user' => 'mahasiswa',
                'no_whatsapp' => '083829885982',
                'photo' => 'syira_khoerunisa.png',
                'status_user' => 'aktif'
            ],
            [
                'username' => '221511044',
                'nama' => 'Arya Putra Kusumah',
                'email' => 'arya.putra.tif22.test@polban.ac.id',
                'password' => Hash::make('arya123!#'),
                'role_user' => 'mahasiswa',
                'no_whatsapp' => '083804430116',
                'photo' => 'arya_putra_kusumah.png',
                'status_user' => 'aktif'
            ],
            [
                'username' => '221511049',
                'nama' => 'Fahrizal Mudzaqi Maulana',
                'email' => 'fahrizal.mudzaqi.tif22.test@polban.ac.id',
                'password' => Hash::make('fahr123!#'),
                'role_user' => 'mahasiswa',
                'no_whatsapp' => '088224844088',
                'photo' => 'fahrizal_mudzaqi_maulana.png',
                'status_user' => 'aktif'
            ],
            [
                'username' => '221511050',
                'nama' => 'Faras Rama Mahadika',
                'email' => 'faras.rama.tif22.test@polban.ac.id',
                'password' => Hash::make('fara123!#'),
                'role_user' => 'mahasiswa',
                'no_whatsapp' => '00000000000',
                'photo' => 'faras_rama_mahadika.png',
                'status_user' => 'aktif'
            ],
            [
                'username' => '221511036',
                'nama' => 'Adrian Eka Saputra',
                'email' => 'adrian.eka.tif22.test@polban.ac.id',
                'password' => Hash::make('adri123!#'),
                'role_user' => 'mahasiswa',
                'no_whatsapp' => '085156670790',
                'photo' => 'adrian_eka_saputra.png',
                'status_user' => 'aktif'
            ],
            [
                'username' => '221511014',
                'nama' => 'Hasby Raihan',
                'email' => 'hasby.raihan.tif22.test@polban.ac.id',
                'password' => Hash::make('hasb123!#'),
                'role_user' => 'mahasiswa',
                'no_whatsapp' => '082128579762',
                'photo' => 'hasby_raihan.png',
                'status_user' => 'aktif'
            ],
            [
                'username' => '221511056',
                'nama' => 'Muhammad Adi Saputera',
                'email' => 'muhammad.adi.tif22.test@polban.ac.id',
                'password' => Hash::make('muha123!#'),
                'role_user' => 'mahasiswa',
                'no_whatsapp' => '08130128028',
                'photo' => 'muhammad_adi_saputera.png',
                'status_user' => 'aktif'
            ],
            // [
            //     'username' => '201511051',
            //     'nama' => 'Muhammad Fadhlan Athhar Fadhilah',
            //     'email' => 'muhammad.fadhlan.tif20@polban.ac.id',
            //     'password' => Hash::make('muha123!#'),
            //     'role_user' => 'mahasiswa',
            //     'no_whatsapp' => '00000000000',
            //     'photo' => 'muhammad_fadhlan_athhar_fadhilah.png',
            //     'status_user' => 'aktif'
            // ],
            //D4
            [
                'username' => '201524017',
                'nama' => 'Muhammad Fikri Hidayatulloh',
                'email' => 'muhammad.fikri.tif420.test@polban.ac.id',
                'password' => Hash::make('muhammad123!#'),
                'role_user' => 'mahasiswa',
                'no_whatsapp' => '000000',
                'photo' => 'muhammad_fikri_hidayatulloh.png',
                'status_user' => 'aktif'
            ],
            // Untuk keperluan demo
            // [
            //     'username' => '211524001',
            //     'nama' => 'Adinda Faayza Malika',
            //     'email' => 'adinda.faayza.tif421.test@polban.ac.id',
            //     'password' => Hash::make('adinda123!#'),
            //     'role_user' => 'mahasiswa',
            //     'no_whatsapp' => '000000',
            //     'photo' => 'adinda_faayza_malika.png',
            //     'status_user' => 'aktif'
            // ],
            [
                'username' => '211524003',
                'nama' => 'Annisa Dinda Gantini',
                'email' => 'annisa.dinda.tif421.test@polban.ac.id',
                'password' => Hash::make('annisa123!#'),
                'role_user' => 'mahasiswa',
                'no_whatsapp' => '000000',
                'photo' => 'annisa_dinda_gantini.png',
                'status_user' => 'aktif'
            ],
            [
                'username' => '211524002',
                'nama' => 'Amelia Dewi Agustiani',
                'email' => 'amelia.dewi.tif421.test@polban.ac.id',
                'password' => Hash::make('amelia123!#'),
                'role_user' => 'mahasiswa',
                'no_whatsapp' => '000000',
                'photo' => 'amelia_dewi_agustiani.png',
                'status_user' => 'aktif'
            ],
            [
                'username' => '211524026',
                'nama' => 'Salsabila Maharani Putri',
                'email' => 'salsabila.maharani.tif421.test@polban.ac.id',
                'password' => Hash::make('salsabila123!#'),
                'role_user' => 'mahasiswa',
                'no_whatsapp' => '000000',
                'photo' => 'salsabila_maharani_putri.png',
                'status_user' => 'aktif'
            ],
            [
                'username' => '211524004',
                'nama' => 'Berliana Elfada',
                'email' => 'berliana.elfada.tif421.test@polban.ac.id',
                'password' => Hash::make('berliana123!#'),
                'role_user' => 'mahasiswa',
                'no_whatsapp' => '000000',
                'photo' => 'berliana_elfada.png',
                'status_user' => 'aktif'
            ],
            [
                'username' => '211524027',
                'nama' => 'Suci Awalia Gardara',
                'email' => 'suci.awalia.tif421.test@polban.ac.id',
                'password' => Hash::make('suci123!#'),
                'role_user' => 'mahasiswa',
                'no_whatsapp' => '000000',
                'photo' => 'suci_awalia_gardara.png',
                'status_user' => 'aktif'
            ],
            [
                'username' => '211524005',
                'nama' => 'Cintia Ningsih',
                'email' => 'cintia.ningsih.tif421.test@polban.ac.id',
                'password' => Hash::make('cintia123!#'),
                'role_user' => 'mahasiswa',
                'no_whatsapp' => '000000',
                'photo' => 'cintia_ningsih.png',
                'status_user' => 'aktif'
            ],
            [
                'username' => '211524029',
                'nama' => 'Yane Pradita',
                'email' => 'yane.pradita.tif421.test@polban.ac.id',
                'password' => Hash::make('yane123!#'),
                'role_user' => 'mahasiswa',
                'no_whatsapp' => '000000',
                'photo' => 'yane_pradita.png',
                'status_user' => 'aktif'
            ],
            [
                'username' => '211524006',
                'nama' => 'Dafa Nurul Fauziansyah',
                'email' => 'dafa.nurul.tif421.test@polban.ac.id',
                'password' => Hash::make('dafa123!#'),
                'role_user' => 'mahasiswa',
                'no_whatsapp' => '000000',
                'photo' => 'dafa_nurul_fauziansyah.png',
                'status_user' => 'aktif'
            ],
            [
                'username' => '211524017',
                'nama' => 'Muhammad Deo Audha Rizki',
                'email' => 'muhammad.deo.tif421.test@polban.ac.id',
                'password' => Hash::make('muhammad123!#'),
                'role_user' => 'mahasiswa',
                'no_whatsapp' => '000000',
                'photo' => 'muhammad_deo_audha_rizki.png',
                'status_user' => 'aktif'
            ],
            [
                'username' => '211524009',
                'nama' => 'Fardan Al Jihad',
                'email' => 'fardan.al.tif421.test@polban.ac.id',
                'password' => Hash::make('fardan123!#'),
                'role_user' => 'mahasiswa',
                'no_whatsapp' => '000000',
                'photo' => 'fardan_al_jihad.png',
                'status_user' => 'aktif'
            ],
            [
                'username' => '211524030',
                'nama' => 'Yayang Setia Budi',
                'email' => 'yayang.setia.tif421.test@polban.ac.id',
                'password' => Hash::make('yayang123!#'),
                'role_user' => 'mahasiswa',
                'no_whatsapp' => '000000',
                'photo' => 'yayang_setia_budi.png',
                'status_user' => 'aktif'
            ],
            [
                'username' => '211524010',
                'nama' => 'Fariz Muhamad Ibnu Hisyam',
                'email' => 'fariz.muhamad.tif421.test@polban.ac.id',
                'password' => Hash::make('fariz123!#'),
                'role_user' => 'mahasiswa',
                'no_whatsapp' => '000000',
                'photo' => 'fariz_muhamad_ibnu_hisyam.png',
                'status_user' => 'aktif'
            ],
            [
                'username' => '211524016',
                'nama' => 'Muhamad Naufal Al Ghani',
                'email' => 'muhamad.naufal.tif421.test@polban.ac.id',
                'password' => Hash::make('muhamad123!#'),
                'role_user' => 'mahasiswa',
                'no_whatsapp' => '000000',
                'photo' => 'muhamad_naufal_al_ghani.png',
                'status_user' => 'aktif'
            ],
            [
                'username' => '211524012',
                'nama' => 'Hanri Fajar Ramadhan',
                'email' => 'hanri.fajar.tif421.test@polban.ac.id',
                'password' => Hash::make('hanri123!#'),
                'role_user' => 'mahasiswa',
                'no_whatsapp' => '000000',
                'photo' => 'hanri_fajar_ramadhan.png',
                'status_user' => 'aktif'
            ],
            [
                'username' => '211524032',
                'nama' => 'Zahri Al Adzani Hidayat',
                'email' => 'zahri.al.tif421.test@polban.ac.id',
                'password' => Hash::make('zahri123!#'),
                'role_user' => 'mahasiswa',
                'no_whatsapp' => '000000',
                'photo' => 'zahri_al_adzani_hidayat.png',
                'status_user' => 'aktif'
            ],
            [
                'username' => '211524015',
                'nama' => 'Mohammad Fathul`ibad',
                'email' => 'mohammad.fathul.tif421.test@polban.ac.id',
                'password' => Hash::make('mohammad123!#'),
                'role_user' => 'mahasiswa',
                'no_whatsapp' => '000000',
                'photo' => 'mohammad_fathul’ibad.png',
                'status_user' => 'aktif'
            ],
            [
                'username' => '211524013',
                'nama' => 'Maolana Firmansyah',
                'email' => 'maolana.firmansyah.tif421.test@polban.ac.id',
                'password' => Hash::make('maolana123!#'),
                'role_user' => 'mahasiswa',
                'no_whatsapp' => '000000',
                'photo' => 'maolana_firmansyah.png',
                'status_user' => 'aktif'
            ],
            [
                'username' => '211524018',
                'nama' => 'Muhammad Dyfan Ramadhan',
                'email' => 'muhammad.dyfan.tif421.test@polban.ac.id',
                'password' => Hash::make('muhammad123!#'),
                'role_user' => 'mahasiswa',
                'no_whatsapp' => '000000',
                'photo' => 'muhammad_dyfan_ramadhan.png',
                'status_user' => 'aktif'
            ],
            [
                'username' => '211524024',
                'nama' => 'Reka Briyan Cahya Heryana',
                'email' => 'reka.briyan.tif421.test@polban.ac.id',
                'password' => Hash::make('reka123!#'),
                'role_user' => 'mahasiswa',
                'no_whatsapp' => '000000',
                'photo' => 'reka_briyan_cahya_heryana.png',
                'status_user' => 'aktif'
            ],
            [
                'username' => '211524007',
                'nama' => 'Dhafin Rizqi Fadhilah',
                'email' => 'dhafin.rizqi.tif421.test@polban.ac.id',
                'password' => Hash::make('dhafin123!#'),
                'role_user' => 'mahasiswa',
                'no_whatsapp' => '000000',
                'photo' => 'dhafin_rizqi_fadhilah.png',
                'status_user' => 'aktif'
            ],
            [
                'username' => '211524019',
                'nama' => 'Muhammad Fadhil',
                'email' => 'muhammad.fadhil.tif421.test@polban.ac.id',
                'password' => Hash::make('muhammad123!#'),
                'role_user' => 'mahasiswa',
                'no_whatsapp' => '000000',
                'photo' => 'muhammad_fadhil.png',
                'status_user' => 'aktif'
            ],
            [
                'username' => '211524021',
                'nama' => 'Raditya Pasya Heryandi',
                'email' => 'raditya.pasya.tif421.test@polban.ac.id',
                'password' => Hash::make('raditya123!#'),
                'role_user' => 'mahasiswa',
                'no_whatsapp' => '000000',
                'photo' => 'raditya_pasya_heryandi.png',
                'status_user' => 'aktif'
            ],
            [
                'username' => '211524022',
                'nama' => 'Raihan Fuad Syakir',
                'email' => 'raihan.fuad.tif421.test@polban.ac.id',
                'password' => Hash::make('raihan123!#'),
                'role_user' => 'mahasiswa',
                'no_whatsapp' => '000000',
                'photo' => 'raihan_fuad_syakir.png',
                'status_user' => 'aktif'
            ],
            [
                'username' => '211524025',
                'nama' => 'Salma Edyna Putri',
                'email' => 'salma.edyna.tif421.test@polban.ac.id',
                'password' => Hash::make('salma123!#'),
                'role_user' => 'mahasiswa',
                'no_whatsapp' => '000000',
                'photo' => 'salma_edyna_putri.png',
                'status_user' => 'aktif'
            ],
            [
                'username' => '211524028',
                'nama' => 'Tabitha Salsabila Permana',
                'email' => 'tabitha.salsabila.tif421.test@polban.ac.id',
                'password' => Hash::make('tabitha123!#'),
                'role_user' => 'mahasiswa',
                'no_whatsapp' => '000000',
                'photo' => 'tabitha_salsabila_permana.png',
                'status_user' => 'aktif'
            ],
            [
                'username' => '211524008',
                'nama' => 'Fadhil Radja Assyidiq',
                'email' => 'fadhil.radja.tif421.test@polban.ac.id',
                'password' => Hash::make('fadhil123!#'),
                'role_user' => 'mahasiswa',
                'no_whatsapp' => '000000',
                'photo' => 'fadhil_radja_assyidiq.png',
                'status_user' => 'aktif'
            ],
            [
                'username' => '211524031',
                'nama' => 'Zacky Faishal Abror',
                'email' => 'zacky.faishal.tif421.test@polban.ac.id',
                'password' => Hash::make('zacky123!#'),
                'role_user' => 'mahasiswa',
                'no_whatsapp' => '000000',
                'photo' => 'zacky_faishal_abror.png',
                'status_user' => 'aktif'
            ],
            [
                'username' => '211524033',
                'nama' => 'Aini Diah Rahmawati',
                'email' => 'aini.diah.tif421.test@polban.ac.id',
                'password' => Hash::make('aini123!#'),
                'role_user' => 'mahasiswa',
                'no_whatsapp' => '000000',
                'photo' => 'aini_diah_rahmawati.png',
                'status_user' => 'aktif'
            ],
            [
                'username' => '211524060',
                'nama' => 'Rivaldo Fauzan Robani',
                'email' => 'rivaldo.fauzan.tif421.test@polban.ac.id',
                'password' => Hash::make('rivaldo123!#'),
                'role_user' => 'mahasiswa',
                'no_whatsapp' => '000000',
                'photo' => 'rivaldo_fauzan_robani.png',
                'status_user' => 'aktif'
            ],
            [
                'username' => '211524034',
                'nama' => 'Aini Nurul Azizah',
                'email' => 'aini.nurul.tif421.test@polban.ac.id',
                'password' => Hash::make('aini123!#'),
                'role_user' => 'mahasiswa',
                'no_whatsapp' => '000000',
                'photo' => 'aini_nurul_azizah.png',
                'status_user' => 'aktif'
            ],
            [
                'username' => '211524044',
                'nama' => 'Helsa Alika Femiani',
                'email' => 'helsa.alika.tif421.test@polban.ac.id',
                'password' => Hash::make('helsa123!#'),
                'role_user' => 'mahasiswa',
                'no_whatsapp' => '000000',
                'photo' => 'helsa_alika_femiani.png',
                'status_user' => 'aktif'
            ],
            [
                'username' => '211524036',
                'nama' => 'Amelia Nathasa',
                'email' => 'amelia.nathasa.tif421.test@polban.ac.id',
                'password' => Hash::make('amelia123!#'),
                'role_user' => 'mahasiswa',
                'no_whatsapp' => '000000',
                'photo' => 'amelia_nathasa.png',
                'status_user' => 'aktif'
            ],
            [
                'username' => '211524041',
                'nama' => 'Falia Davina Gustaman',
                'email' => 'falia.davina.tif421.test@polban.ac.id',
                'password' => Hash::make('falia123!#'),
                'role_user' => 'mahasiswa',
                'no_whatsapp' => '000000',
                'photo' => 'falia_davina_gustaman.png',
                'status_user' => 'aktif'
            ],
            [
                'username' => '211524037',
                'nama' => 'Danu Mahesa',
                'email' => 'danu.mahesa.tif421.test@polban.ac.id',
                'password' => Hash::make('danu123!#'),
                'role_user' => 'mahasiswa',
                'no_whatsapp' => '000000',
                'photo' => 'danu_mahesa.png',
                'status_user' => 'aktif'
            ],
            [
                'username' => '211524057',
                'nama' => 'Regi Purnama',
                'email' => 'regi.purnama.tif421.test@polban.ac.id',
                'password' => Hash::make('regi123!#'),
                'role_user' => 'mahasiswa',
                'no_whatsapp' => '000000',
                'photo' => 'regi_purnama.png',
                'status_user' => 'aktif'
            ],
            [
                'username' => '211524038',
                'nama' => 'Dea Salma Isnaini',
                'email' => 'dea.salma.tif421.test@polban.ac.id',
                'password' => Hash::make('dea123!#'),
                'role_user' => 'mahasiswa',
                'no_whatsapp' => '000000',
                'photo' => 'dea_salma_isnaini.png',
                'status_user' => 'aktif'
            ],
            [
                'username' => '211524048',
                'nama' => 'Mey Meizia Galtiady',
                'email' => 'mey.meizia.tif421.test@polban.ac.id',
                'password' => Hash::make('mey123!#'),
                'role_user' => 'mahasiswa',
                'no_whatsapp' => '000000',
                'photo' => 'mey_meizia_galtiady.png',
                'status_user' => 'aktif'
            ],
            [
                'username' => '211524039',
                'nama' => 'Delvito Rahim Derivansyah',
                'email' => 'delvito.rahim.tif421.test@polban.ac.id',
                'password' => Hash::make('delvito123!#'),
                'role_user' => 'mahasiswa',
                'no_whatsapp' => '000000',
                'photo' => 'delvito_rahim_derivansyah.png',
                'status_user' => 'aktif'
            ],
            [
                'username' => '211524047',
                'nama' => 'Mentari Ayu Alysia Sudrajat',
                'email' => 'mentari.ayu.tif421.test@polban.ac.id',
                'password' => Hash::make('mentari123!#'),
                'role_user' => 'mahasiswa',
                'no_whatsapp' => '000000',
                'photo' => 'mentari_ayu_alysia_sudrajat.png',
                'status_user' => 'aktif'
            ],
            [
                'username' => '211524042',
                'nama' => 'Ghessa Theniana',
                'email' => 'ghessa.theniana.tif421.test@polban.ac.id',
                'password' => Hash::make('ghessa123!#'),
                'role_user' => 'mahasiswa',
                'no_whatsapp' => '000000',
                'photo' => 'ghessa_theniana.png',
                'status_user' => 'aktif'
            ],
            [
                'username' => '211524062',
                'nama' => 'Sendi Setiawan',
                'email' => 'sendi.setiawan.tif421.test@polban.ac.id',
                'password' => Hash::make('sendi123!#'),
                'role_user' => 'mahasiswa',
                'no_whatsapp' => '000000',
                'photo' => 'sendi_setiawan.png',
                'status_user' => 'aktif'
            ],
            [
                'username' => '211524056',
                'nama' => 'Raka Mahardika Maulana',
                'email' => 'raka.mahardika.tif421.test@polban.ac.id',
                'password' => Hash::make('raka123!#'),
                'role_user' => 'mahasiswa',
                'no_whatsapp' => '000000',
                'photo' => 'raka_mahardika_maulana.png',
                'status_user' => 'aktif'
            ],
            [
                'username' => '211524043',
                'nama' => 'Gian Sandrova',
                'email' => 'gian.sandrova.tif421.test@polban.ac.id',
                'password' => Hash::make('gian123!#'),
                'role_user' => 'mahasiswa',
                'no_whatsapp' => '000000',
                'photo' => 'gian_sandrova.png',
                'status_user' => 'aktif'
            ],
            [
                'username' => '211524045',
                'nama' => 'Husna Maulana',
                'email' => 'husna.maulana.tif421.test@polban.ac.id',
                'password' => Hash::make('husna123!#'),
                'role_user' => 'mahasiswa',
                'no_whatsapp' => '000000',
                'photo' => 'husna_maulana.png',
                'status_user' => 'aktif'
            ],
            [
                'username' => '211524051',
                'nama' => 'Muhammad Rafi Farhan',
                'email' => 'muhammad.rafi.tif421.test@polban.ac.id',
                'password' => Hash::make('muhammad123!#'),
                'role_user' => 'mahasiswa',
                'no_whatsapp' => '000000',
                'photo' => 'muhammad_rafi_farhan.png',
                'status_user' => 'aktif'
            ],
            [
                'username' => '211524046',
                'nama' => 'Jovan Shelomo',
                'email' => 'jovan.shelomo.tif421.test@polban.ac.id',
                'password' => Hash::make('jovan123!#'),
                'role_user' => 'mahasiswa',
                'no_whatsapp' => '000000',
                'photo' => 'jovan_shelomo.png',
                'status_user' => 'aktif'
            ],
            [
                'username' => '211524054',
                'nama' => 'Rachmat Purwa Saputra',
                'email' => 'rachmat.purwa.tif421.test@polban.ac.id',
                'password' => Hash::make('rachmat123!#'),
                'role_user' => 'mahasiswa',
                'no_whatsapp' => '000000',
                'photo' => 'rachmat_purwa_saputra.png',
                'status_user' => 'aktif'
            ],
            [
                'username' => '211524049',
                'nama' => 'Mochamad Ferdy Fauzan',
                'email' => 'mochamad.ferdy.tif421.test@polban.ac.id',
                'password' => Hash::make('mochamad123!#'),
                'role_user' => 'mahasiswa',
                'no_whatsapp' => '000000',
                'photo' => 'mochamad_ferdy_fauzan.png',
                'status_user' => 'aktif'
            ],
            [
                'username' => '211524050',
                'nama' => 'Muhammad Daffa Raihandika',
                'email' => 'muhammad.daffa.tif421.test@polban.ac.id',
                'password' => Hash::make('muhammad123!#'),
                'role_user' => 'mahasiswa',
                'no_whatsapp' => '000000',
                'photo' => 'muhammad_daffa_raihandika.png',
                'status_user' => 'aktif'
            ],
            [
                'username' => '211524052',
                'nama' => 'Nayara Saffa',
                'email' => 'nayara.saffa.tif421.test@polban.ac.id',
                'password' => Hash::make('nayara123!#'),
                'role_user' => 'mahasiswa',
                'no_whatsapp' => '000000',
                'photo' => 'nayara_saffa.png',
                'status_user' => 'aktif'
            ],
            [
                'username' => '211524063',
                'nama' => 'Syifa Khairina',
                'email' => 'syifa.khairina.tif421.test@polban.ac.id',
                'password' => Hash::make('syifa123!#'),
                'role_user' => 'mahasiswa',
                'no_whatsapp' => '000000',
                'photo' => 'syifa_khairina.png',
                'status_user' => 'aktif'
            ],
            [
                'username' => '211524053',
                'nama' => 'Novia Nur Azizah',
                'email' => 'novia.nur.tif421.test@polban.ac.id',
                'password' => Hash::make('novia123!#'),
                'role_user' => 'mahasiswa',
                'no_whatsapp' => '000000',
                'photo' => 'novia_nur_azizah.png',
                'status_user' => 'aktif'
            ],
            [
                'username' => '211524061',
                'nama' => 'Rofa`ul Akrom Hendrawan',
                'email' => 'rofaul.akrom.tif421.test@polban.ac.id',
                'password' => Hash::make('rofaul123!#'),
                'role_user' => 'mahasiswa',
                'no_whatsapp' => '000000',
                'photo' => 'rofaul_akrom_hendrawan.png',
                'status_user' => 'aktif'
            ],
            [
                'username' => '211524059',
                'nama' => 'Reza Ananta Permadi Supriyo',
                'email' => 'reza.ananta.tif421.test@polban.ac.id',
                'password' => Hash::make('reza123!#'),
                'role_user' => 'mahasiswa',
                'no_whatsapp' => '000000',
                'photo' => 'reza_ananta_permadi_supriyo.png',
                'status_user' => 'aktif'
            ],
            [
                'username' => '211524055',
                'nama' => 'Rahma Alia Latifa',
                'email' => 'rahma.alia.tif421.test@polban.ac.id',
                'password' => Hash::make('rahma123!#'),
                'role_user' => 'mahasiswa',
                'no_whatsapp' => '000000',
                'photo' => 'rahma_alia_latifa.png',
                'status_user' => 'aktif'
            ],
            [
                'username' => '211524064',
                'nama' => 'Yasmin Azizah Tuhfah',
                'email' => 'yasmin.azizah.tif421.test@polban.ac.id',
                'password' => Hash::make('yasmin123!#'),
                'role_user' => 'mahasiswa',
                'no_whatsapp' => '000000',
                'photo' => 'yasmin_azizah_tuhfah.png',
                'status_user' => 'aktif'
            ],
            [
                'username' => '211524058',
                'nama' => 'Reihan Hadi Fauzan',
                'email' => 'reihan.hadi.tif421.test@polban.ac.id',
                'password' => Hash::make('reihan123!#'),
                'role_user' => 'mahasiswa',
                'no_whatsapp' => '000000',
                'photo' => 'reihan_hadi_fauzan.png',
                'status_user' => 'aktif'
            ],
            [
                'username' => '211524011',
                'nama' => 'Fariz Rahman Maulana',
                'email' => 'fariz.rahman.tif421.test@polban.ac.id',
                'password' => Hash::make('fariz123!#'),
                'role_user' => 'mahasiswa',
                'no_whatsapp' => '000000',
                'photo' => 'fariz_rahman_maulana.png',
                'status_user' => 'aktif'
            ],
            [
                'username' => '211524023',
                'nama' => 'Raihan Shidqi Pangestu',
                'email' => 'raihan.shidqi.tif421.test@polban.ac.id',
                'password' => Hash::make('raihan123!#'),
                'role_user' => 'mahasiswa',
                'no_whatsapp' => '000000',
                'photo' => 'raihan_shidqi_pangestu.png',
                'status_user' => 'aktif'
            ],
            [
                'username' => '211524040',
                'nama' => 'Egi Satria Dharma Yudha Wicaksana',
                'email' => 'egi.satria.tif421.test@polban.ac.id',
                'password' => Hash::make('egi123!#'),
                'role_user' => 'mahasiswa',
                'no_whatsapp' => '000000',
                'photo' => 'egi_satria_dharma_yudha_wicaksana.png',
                'status_user' => 'aktif'
            ]
        ];

        foreach ($data as $item) {
            User::create($item);
        }
    }
}
