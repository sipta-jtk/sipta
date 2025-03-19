<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

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
            return $user->role_user === 'dosen';
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

        //Contoh Akses Multirole
        Gate::define('akses-form-pisah-kota', function ($user) {
            return Gate::allows('mahasiswa_ta') || Gate::allows('koordinator_ta');
        });

        //Contoh Pengecualian Akses
        Gate::define('blokir-pisah-kota', function ($user) {
            return Gate::denies('mahasiswa_ta');
        });
    }
}