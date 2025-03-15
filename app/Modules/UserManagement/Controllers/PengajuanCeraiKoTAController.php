<?php

namespace App\Modules\UserManagement\Controllers;

use App\Modules\Controller;
use App\Models\User;
use App\Models\PengajuanPisahKota;

class PengajuanCeraiKoTAController extends Controller
{
    public function index()
    {   
        // Ambil data dari model baru
        $pengajuan = PengajuanPisahKota::with('kota')->get();
        
        return view('UserManagement.views.pengajuan-cerai-kota', compact('pengajuan'));
    }

    public function show($id)
    {
        // Ambil satu data sesuai id
        $item = PengajuanPisahKota::find($id);

        if (!$item) {
            return abort(404, 'Data tidak ditemukan');
        }
    
        return view('UserManagement.views.form-cerai-kota', compact('item'));
    }
}
