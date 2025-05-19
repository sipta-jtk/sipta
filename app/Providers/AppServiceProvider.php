<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\UrlGenerator;
use Illuminate\Support\Facades\View;
use App\Services\NotifikasiService;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
        $this->app->singleton('notifikasi', function ($app) {
            return new NotifikasiService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(UrlGenerator $url): void
    {
        If(env('APP_ENV') !== 'local') { 
            $url->forceScheme('https'); 
        }
        View::addLocation(base_path('app/Modules'));
        View::addLocation(base_path('app/Modules/NotificationAndReminder/views'));
        View::addNamespace('NotificationAndReminder', base_path('app/Modules/NotificationAndReminder/views'));
    }
}