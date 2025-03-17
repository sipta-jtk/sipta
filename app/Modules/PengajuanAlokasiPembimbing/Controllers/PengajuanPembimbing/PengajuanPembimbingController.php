<?php

namespace App\Modules\PengajuanAlokasiPembimbing\Controllers\PengajuanPembimbing;

use App\Models\User;
use App\Models\Mahasiswa;
use App\Models\Kota;
use App\Models\PengajuanPembimbing;
use App\Models\PrioritasPembimbing;
use App\Models\Dosen;
use App\Modules\Controller;
use DB;
use Illuminate\View\View;

class PengajuanPembimbingController extends Controller
{
    public function view_dataKelompok(): View
    {
        // // data mahasiswa yang login
        // $id_kota_user = DB::table('mahasiswa')
        //     ->where('nim', auth()->user()->username) 
        //     ->value('id_kota'); 

        // // data mahasiswa dalam kota yang sama dengan yang login
        // $listMahasiswa = DB::table('mahasiswa')
        //     ->join('user', 'mahasiswa.nim', '=', 'user.username')
        //     ->select('user.nama', 'mahasiswa.nim', 'mahasiswa.kelas')
        //     ->where('mahasiswa.id_kota', $id_kota_user)
        //     ->orderBy('user.nama', 'asc')
        //     ->get();

        return view('PengajuanAlokasiPembimbing.views.PengajuanPembimbing.DataKelompok');
    }

    public function view_topikTugasAkhir(): View
    {
        return view('PengajuanAlokasiPembimbing.views.PengajuanPembimbing.TopikTugasAkhir');
    }

    public function view_prioritasDosenPembimbing(): View
    {
        $listDosen = DB::table('dosen')
            ->join('user', 'dosen.nip', '=', 'user.username')
            ->select('user.nama')
            ->orderBy('user.nama', 'asc')
            ->get();

        return view('PengajuanAlokasiPembimbing.views.PengajuanPembimbing.PrioritasDosenPembimbing', compact('listDosen'));
    }

    public function view_pratinjauFormulir(): View
    {
        return view('PengajuanAlokasiPembimbing.views.PengajuanPembimbing.PratinjauFormulir');
    }
}