<?php

namespace App\Providers;

use Illuminate\Routing\UrlGenerator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
<<<<<<< HEAD
    // public function boot(): void
    // {
    //     View::addLocation(base_path('app/modules'));
    // }
    public function boot(): void
    {
        // Menambahkan path untuk views di dalam folder app/Modules/Notifikasi/views
        // View::addLocation(base_path('app/Modules'));
        View::addLocation(base_path('app/Modules/NotificationAndReminder/views'));
        View::addNamespace('NotificationAndReminder', base_path('app/Modules/NotificationAndReminder/views'));
=======
    public function boot(UrlGenerator $url): void
    {
        If(env('APP_ENV') !== 'local') { 
            $url->forceScheme('https'); 
        }
        View::addLocation(base_path('app/Modules'));
>>>>>>> c1f5fe8eb21c124f7e3c77f29d7824ec3fe80dae
    }
}
