<?php

namespace App\Modules\UserManagement\Controllers;

// use Illuminate\Http\Request;
use App\Modules\Controller;
use App\Models\User;

class KonfirmasiKoTAController extends Controller
{
    public function index()
    {
        return view('UserManagement.views.konfirmasi-kota');
    }
}
