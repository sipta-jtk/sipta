<?php
namespace App\Modules\UserManagement\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController as FortifyAuthenticatedSessionController;

class AuthenticatedSessionController extends FortifyAuthenticatedSessionController
{
    protected function authenticated(Request $request, $user)
    {
        if ($user->hasRole('admin')) 
        {
            return redirect()->route('dashboard.admin');
        } elseif ($user->hasRole('dosen')) 
        {
            return redirect()->route('dashboard.dosen');
        } elseif ($user->hasRole('mahasiswa')) 
        {
            return redirect()->route('dashboard.mahasiswa');
        } elseif ($user->hasRole('koordinator_ta')) 
        {
            return redirect()->route('dashboard.koordinator_ta');
        } elseif ($user->hasRole('kajur')) 
        {
            return redirect()->route('dashboard.kajur');
        } 

        return redirect('/');
    }
}
