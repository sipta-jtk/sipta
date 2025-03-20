<?php

namespace App\Modules\CekPlagiarisme\Controllers;

use App\Modules\Controller;
use Illuminate\View\View;
use App\Models\Dokumen;
use App\Models\Dosen;
use App\Models\ReviewDosenPembimbing;
use App\Models\ListJurnalPlagiarisme;
use App\Models\ListKalimatPlagiarisme;

class CekPlagiarismeDetailController extends Controller
{
    public function show($id)
    {
        // Mengambil dokumen berdasarkan ID
        $dokumen = Dokumen::find($id);
        
        // Mengambil semua review (catatan) yang terkait dengan dokumen
        $catatan = ReviewDosenPembimbing::with('dosen')->where('id_dokumen', $id)->get();
        
        // Mengambil kalimat plagiat yang berelasi dengan dokumen dan jurnal
        $sumberPlagiarisme = ListKalimatPlagiarisme::with('listJurnalPlagiarisme') // Menggunakan relasi yang benar
            ->where('id_dokumen', $id)
            ->get();
    
        // Mengirimkan data ke view
        return view('CekPlagiarisme.views.detail', compact('dokumen', 'catatan', 'sumberPlagiarisme')); // Tambahkan 'sumberPlagiarisme'
    }
    

    public function PenentuanAmbangBatas(): View
    {
        return view('CekPlagiarisme.views.PenentuanAmbangBatas');
    }
}