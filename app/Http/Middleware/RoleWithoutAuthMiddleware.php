<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;

class RoleWithoutAuthMiddleware
{
    public function handle($request, Closure $next, $role)
    {
        // Ambil user yang ingin dicek (tanpa login)
        $user = User::where('username', 'dosen005')->first(); // Ganti sesuai admin

        // Jika user ada dan memiliki role yang sesuai, lanjutkan request
        if ($user && $user->hasRole($role)) {
            return $next($request);
        }

        // Jika tidak punya akses, tampilkan error 403
        return response()->view('UserManagement.views.error403');

    }
}