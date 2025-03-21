<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

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
            * Role Access v.1
            * Base role.
            * Role dasar dari pengguna.
            * Perubahan base role dilakukan oleh tim User Management.
        ***********************************************************/

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
            return $user->role_user === 'dosen' && $user->dosen->status_dosen === 'aktif';
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
    }
}