<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class KoordinatorTAMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->user()->role_user === 'dosen' && auth()->user()->dosen->role_dosen === 'koordinator_ta') {
            return redirect('/')->with('error', 'Akses hanya untuk Koordinator TA!');
        }

        return $next($request);
    }
}
