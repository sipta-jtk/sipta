<?php

namespace App\Modules\PengajuanAlokasiPembimbing\Controllers;

use App\Models\User;
use App\Modules\Controller;
use Illuminate\View\View;

class PengajuanAlokasiPembimbingController extends Controller
{
    public function view_minatTopik(): View
    {
        return view('PengajuanAlokasiPembimbing.views.KesediaanBimbingan.Bidang');
    }

    public function view_daftarPengajuanDosbing(): View
    {
        return view('PengajuanAlokasiPembimbing.views.DaftarPengajuanDosbing.topik');
    }
}