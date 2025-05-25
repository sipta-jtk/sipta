<?php

namespace App\Modules\UserManagement\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;

class ImpersonateController extends Controller
{
    public function impersonate($id)
    {
        $user = User::where('username', $id)->first();
        if (!$user) {
            return redirect()->back()->with('error', 'User tidak ditemukan');
        }        // Cek apakah user bisa melakukan dan menerima impersonate
        if (!auth()->user()->canImpersonate()) {
            return redirect()->back()->with('error', 'Anda tidak memiliki izin untuk melakukan impersonate');
        }

        if (!$user->canBeImpersonated()) {
            return redirect()->back()->with('error', 'User ini tidak dapat di-impersonate');
        }

        auth()->user()->impersonate($user);
        return redirect()->route('profile')->with('success', 'Berhasil login sebagai ' . $user->nama);
    }

    public function leave()
    {
        auth()->user()->leaveImpersonation();
        return redirect()->route('profile')->with('success', 'Kembali ke akun asli');
    }
}