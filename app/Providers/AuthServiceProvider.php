<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use App\Models\AlokasiDosen;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();


        /**********************************************************
        ! Restricted    
            * Role Access v.2
            * Base role.
            * Role dasar dari pengguna.
            * Perubahan base role dilakukan oleh tim User Management.
        ***********************************************************/

        /* v2 update
            Tambah gate user
        */
        //User
        Gate::define('user', function($user){
            return $user !== null;
        });

        //Admin
        Gate::define('admin', function ($user) {
            return $user->role_user === 'admin';
        });

        //Koordinator_TA
        Gate::define('koordinator_ta', function ($user) {
            return $user->role_user === 'dosen' && $user->dosen->role_dosen === 'koordinator_ta';
        });


        //Mahasiswa TA
        Gate::define('mahasiswa_ta', function ($user) {
            return $user->role_user === 'mahasiswa' && $user->mahasiswa->status_ta === 'mahasiswa_ta';
        });

        //Mahasiswa Non TA
        Gate::define('mahasiswa_non_ta', function ($user) {
            return $user->role_user === 'mahasiswa' && $user->mahasiswa->status_ta === 'mahasiswa_non_ta';
        });

        //Dosen
        Gate::define('dosen', function ($user) {
            return $user->role_user === 'dosen' && $user->status_user === 'aktif';
        });

        //Kaprodi
        Gate::define('kaprodi', function ($user) {
            $kaprodi = \App\Models\Kaprodi::where('nip', $user->username)->first();
            return $user->role_user === 'dosen' && $kaprodi !== null;
        });

        //Kajur
        Gate::define('kajur', function ($user) {
            return $user->role_user === 'kajur';
        });

        /***************************************************************
         * CUSTOM GATE
         * Silahkan definisikan gate untuk keperluan anda disini.
         * Supaya readable, silahkan beri comment sebagai pembatas dari tiap fitur
         * Format: [Topik n] - Fitur ...
         ******************************************************************/

        /**********************************
         * [Topik 7] - Fitur Kelola Jurusan
         ***********************************/

        //Contoh Akses Multirole
        Gate::define('akses-form-pisah-kota', function ($user) {
            return Gate::allows('mahasiswa_ta') || Gate::allows('koordinator_ta');
        });

        //Contoh Pengecualian Akses
        Gate::define('blokir-pisah-kota', function ($user) {
            return Gate::denies('mahasiswa_ta');
        });

        /**********************************
         * [Topik 2] - Fitur Pengajuan Seminar 3 & Sidang
         ***********************************/
        //All Mahasiswa 
        Gate::define('all_mahasiswa', function ($user) {
            return Gate::allows('mahasiswa_ta') || Gate::allows('mahasiswa_non_ta');
        });

        Gate::define('akses-dosen-kelola-pengajuan-jadwal', function ($user) {
            return Gate::allows('dosen') || Gate::allows('kooordinator_ta');
        });

        /**********************************
         * [Topik 2] - Fitur Pengajuan Seminar 3 & Sidang
         ***********************************/
        //All Mahasiswa 
        Gate::define('all_mahasiswa', function ($user) {
            return $user->role_user === 'mahasiswa' &&
                ($user->mahasiswa->status_ta === 'mahasiswa_ta' ||
                    $user->mahasiswa->status_ta === 'mahasiswa_non_ta');
        });
        /********************************************
         * [Topik 7] - Fitur Perekrutan Anggota KoTA
         *********************************************/

        // Akses Form Perekrutan Anggota KoTA
        Gate::define('akses-form-perekrutan-anggota-kota', function ($user) {
            return Gate::allows('mahasiswa_ta') && $user->mahasiswa->id_kota === null;
        });

        // Akses Halaman Detail KoTA pada sidebar Akun Pengguna
        Gate::define('mahasiswa_kota', function ($user) {
            $mahasiswa = \App\Models\Mahasiswa::where('nim', $user->username)->first();
            return $user->role_user === 'mahasiswa' && $mahasiswa->status_ta === 'mahasiswa_ta' && $mahasiswa->id_kota !== null;
        });

        /********************************************
         * [Topik 6] - Fitur Repository TA
         *********************************************/
        Gate::define('akses-sidebar-repo-dosen', function ($user) {
            return Gate::allows('dosen') || Gate::allows('admin');
        });
        Gate::define('akses-koordinator-admin', function ($user) {
            return Gate::allows('koordinator_ta') || Gate::allows('admin');
        });
        Gate::define('akses-sidebar-repo-mahasiswa', function ($user) {
            return Gate::allows('mahasiswa_ta');
        });
        Gate::define('akses-sidebar-repo', function ($user) {
            return Gate::allows('mahasiswa_ta') || Gate::allows('dosen') || Gate::allows('admin');
        });

        Gate::define('penguji', function ($user) {
            if ($user->role_user !== 'dosen') return false;

            $nip = $user->dosen->nip ?? null;
            if (!$nip) return false;

            return AlokasiDosen::where('nip', $nip)
                ->where('tipe_alokasi', 'penguji')
                ->exists();
        });

        Gate::define('pembimbing', function ($user) {
            if ($user->role_user !== 'dosen') return false;

            $nip = $user->dosen->nip ?? null;
            if (!$nip) return false;

            return AlokasiDosen::where('nip', $nip)
                ->where('tipe_alokasi', 'pembimbing')
                ->exists();
        });

        /**********************************
         * [Topik 4] - Fitur Kelola Penilaian
         ***********************************/
        Gate::define('akses-penilaian-koordinator-ta', function ($user) {
            return Gate::allows('koordinator_ta');
        });

        Gate::define('akses-pemberian-nilai', function ($user) {
            return Gate::allows('koordinator_ta') || Gate::allows('dosen');
        });

        Gate::define('dosen-pembimbing', function ($user) {
            return $user->role_user === 'dosen' &&
                $user->dosen->bersedia_membimbing === 'bersedia' &&
                $user->dosen->alokasiDosen->contains(function ($alokasi) {
                    return $alokasi->tipe_alokasi === 'pembimbing';
                });
        });

        Gate::define('akses-monitoring-dosen-pembimbing', function ($user) {
            return Gate::allows('dosen-pembimbing');
        });

        //Contoh Akses Multirole
        Gate::define('akses-penilaian-mahasiswa', function ($user) {
            return Gate::allows('mahasiswa_ta');
        });


        /***
         * [TOPIK 1] - Fitur Pengajuan dan Alokasi Pembimbing
         */

         //Akses Alokasi and another customize route that only allowed for dosen and koordinator_ta only 
        Gate::define('akses-alokasi', function ($user) {
            return Gate::allows('dosen') || Gate::allows('koordinator_ta');
        });

        /***
         * [TOPIK 5] - Fitur Cek Plagiarisme
         */

        Gate::define('akses-dokumen-mahasiswa-kota', function ($user, $dokumen) {
            return $user->role_user === 'mahasiswa'
                && $user->mahasiswa->status_ta === 'mahasiswa_ta'
                && $user->mahasiswa->id_kota !== null
                && $user->mahasiswa->id_kota === $dokumen->id_kota;
        });
    }
}
