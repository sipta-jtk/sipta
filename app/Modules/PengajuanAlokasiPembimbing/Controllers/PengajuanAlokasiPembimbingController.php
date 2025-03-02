<?php

namespace App\Modules\PengajuanAlokasiPembimbing\Controllers;

use App\Modules\Controller;
use Illuminate\View\View;

class PengajuanAlokasiPembimbingController extends Controller
{
    // public function index(): View
    // {
    //     return view('PengajuanAlokasiPembimbing.views.view');
    // }

    public function view_kesediaanMembimbing(): View
    {
        return view('PengajuanAlokasiPembimbing.views.KesediaanBimbingan.topik');
    }

    public function view_daftarPengajuanDosbing(): View
    {
        return view('PengajuanAlokasiPembimbing.views.DaftarPengajuanDosbing.topik');
    }
}