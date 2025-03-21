<?php

namespace App\Modules\CekPlagiarisme\Controllers;

use App\Modules\Controller;
use Illuminate\View\View;
use App\Models\Dokumen;
use App\Models\Dosen;
use App\Models\ReviewDosenPembimbing;
use App\Models\ListJurnalPlagiarisme;
use App\Models\ListKalimatPlagiarisme;
use App\Models\AlokasiDosen;

class CekPlagiarismeDetailController extends Controller
{
    public function show($id)
    {
        $dokumen = Dokumen::with('user', 'ambangBatas')->find($id);
        
        // Mengambil semua review (catatan) yang terkait dengan dokumen
        $catatan = ReviewDosenPembimbing::with('dosen.user')->where('id_dokumen', $id)->get();

        // Mengambil kalimat plagiat yang berelasi dengan dokumen dan jurnal
        $sumberPlagiarisme = ListKalimatPlagiarisme::with('listJurnalPlagiarisme') // Menggunakan relasi yang benar
            ->where('id_dokumen', $id)
            ->get();

        // Mengambil data alokasi dosen yang statusnya 'fix' dan mengirimkan ke view
        $alokasiDosen = AlokasiDosen::all();

        // Mengirimkan data ke view
        return view('CekPlagiarisme.views.detail', compact('dokumen', 'catatan', 'sumberPlagiarisme', 'alokasiDosen')); // Tambahkan 'sumberPlagiarisme'
    }


    public function PenentuanAmbangBatas(): View
    {
        return view('CekPlagiarisme.views.PenentuanAmbangBatas');
    }
}