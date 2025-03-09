<?php

namespace App\Modules\UserManagement\Controllers;

use App\Modules\Controller;
use App\Models\User;

class FormCeraiKoTAController extends Controller
{
    public function index()
    {   
        return view('UserManagement.views.form-cerai-kota');
    }

    // public function submit()
    // {
    //     $request->validate([
    //         'fta_20' => 'required|file|mimes:pdf|max:2048',
    //     ]);
    
    //     $path = $request->file('fta_20')->store('fta_documents');

    //     return redirect()->route('dashboard')->with('success', 'Pengajuan cerai berhasil dikirim.');
    // }
}