<?php

namespace App\Modules\UserManagement\Controllers;

use Illuminate\Http\Request;
use App\Modules\Controller;
use App\Models\User;

class PengajuanKoTAController extends Controller
{
    public function index()
    {
        return view('UserManagement.views.pengajuan-kota');
    }

    public function submit(Request $request)
    {
        $request->validate([
            'anggota2' => 'required|different:anggota1|different:anggota3',
            'anggota3' => 'required|different:anggota1|different:anggota2',
        ]);

        // $request->session()->put('anggota1', $request->anggota1);
        // $request->session()->put('anggota2', $request->anggota2);
        // $request->session()->put('anggota3', $request->anggota3);
        session([
            'anggota1' => $request->input('anggota1'),
            'anggota2' => $request->input('anggota2'),
            'anggota3' => $request->input('anggota3'),
        ]);

        return redirect()->route('konfirmasi-kota');
    }
}
