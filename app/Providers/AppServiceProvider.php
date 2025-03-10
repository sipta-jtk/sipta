<?php

namespace App\Providers;

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
    }
}
