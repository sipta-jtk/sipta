<?php

namespace App\Modules\PengajuanAlokasiPembimbing\Controllers;

use App\Modules\Controller;
use Illuminate\View\View;

class KesediaanBimbinganController extends Controller
{
    public function view_minatTopik(): View
    {
        return view('PengajuanAlokasiPembimbing.views.KesediaanBimbingan.Bidang');
    }
    public function view_kuotaMahasiswa(): View
    {
        return view('PengajuanAlokasiPembimbing.views.KesediaanBimbingan.JumlahMahasiswa');
    }
    public function view_jadwal(): View
    {
        return view('PengajuanAlokasiPembimbing.views.KesediaanBimbingan.Jadwal');
    }
}