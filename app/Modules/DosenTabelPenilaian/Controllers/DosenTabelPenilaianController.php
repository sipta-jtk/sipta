<?php

namespace App\Modules\DosenTabelPenilaian\Controllers;

use App\Modules\Controller;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class DosenTabelPenilaianController extends Controller
{
    public function index(): View
    {
        $seminars = DB::table('penjadwalan')
            ->join('kota', 'penjadwalan.id_kota', '=', 'kota.id_kota')
            ->select('penjadwalan.*', 'kota.judul_ta as kota_judul')
            ->get();
        
        return view('DosenTabelPenilaian.views.view', compact('seminars'));
    }
}