<?php

// namespace App\Http\Middleware;

// use Closure;
// use Illuminate\Http\Request;
// use Symfony\Component\HttpFoundation\Response;
// use App\Models\User;

// class RoleWithoutAuthMiddleware
// {
//     public function handle($request, Closure $next, $role)
//     {
//         $user = User::where('role_user', '')->first(); 

//         if ($user && $user->hasRole($role)) {
//             return $next($request);
//         }

//         return response()->view('UserManagement.views.error403');

//     }
// }