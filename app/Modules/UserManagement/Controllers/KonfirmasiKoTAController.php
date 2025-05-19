<?php

namespace App\Modules\UserManagement\Controllers;

// use Illuminate\Http\Request;
use App\Modules\Controller;
use App\Models\User;

/**
 * class KonfirmasiKoTAController
 */
class KonfirmasiKoTAController extends Controller
{    
    /**
     * index
     * 
     * Menampilkan halaman konfirmasi KoTA
     *
     * @return void
     */
    public function index()
    {
        return view('UserManagement.views.konfirmasi-kota');
    }
}
