<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;

class ComponentServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // ================ pengajuan alokasi pembimbing
        Blade::component(
            'pengajuan-alokasi-pembimbing.kesediaan-membimbing.card-banner', 
            \App\Modules\PengajuanAlokasiPembimbing\Components\KesediaanMembimbing\CardBanner::class
        );
        // ================ //pengajuan alokasi pembimbing
    }
}
