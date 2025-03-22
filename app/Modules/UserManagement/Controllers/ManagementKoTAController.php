<?php

namespace App\Modules\UserManagement\Controllers;

use Illuminate\Http\Request;
use App\Modules\Controller;
use App\Models\Kota;

class ManagementKoTAController extends Controller 
{
    public function index()
    {
        // Ambil semua data kelompok TA dari database
        $kelompokList = Kota::orderBy('tahun_kota', 'desc')
                           ->orderBy('nama_kota', 'asc')
                           ->get();

        return view('UserManagement.views.management-kota', compact('kelompokList'));
    }
}