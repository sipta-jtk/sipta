<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use App\Models\LoginUser;

class LoginServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        // Handle Login Event
        $this->app['events']->listen(Login::class, function ($event) {
            // Find last login entry for the user
            $lastLogin = LoginUser::where('username', $event->user->username)
                    ->latest('waktu_aktivitas')
                    ->first();

            if ($lastLogin && $lastLogin->status === 'offline') {
                // If user has logged out properly, update the existing entry
                $lastLogin->update([
                    'ip_address' => request()->ip(),
                    'waktu_aktivitas' => now(),
                    'status' => 'online',
                    'waktu_logout' => null // Clear previous logout time
                ]);
            } else {
                // If no previous entry or user didn't logout properly, create new entry
                LoginUser::create([
                    'username' => $event->user->username,
                    'ip_address' => request()->ip(),
                    'waktu_aktivitas' => now(),
                    'status' => 'online'
                ]);
            }
        });

        // Handle Logout Event
        $this->app['events']->listen(Logout::class, function ($event) {
            // Update status log terakhir menjadi offline
            LoginUser::where('username', $event->user->username)
                    ->where('status', 'online')
                    ->update([
                        'status' => 'offline',
                        'waktu_logout' => now()
                    ]);
        });
    }
}
