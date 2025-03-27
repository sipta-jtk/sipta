<?php

namespace App\Modules\KelolaPenilaianTA\Controllers;

use App\Modules\Controller;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Exports\RekapitulasiNilaiExport;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Support\Facades\Log;
use App\Models\Mahasiswa;
use App\Models\kategoriPenilaian;
use App\Models\FormPenilaian;
use App\Models\Kota;
use App\Models\DetailFeedback;

class PengelolaanNilaiController extends Controller{
    /**
     * Menampilkan halaman kelola penilaian
     * 
     */
    public function kelolaNilai(): View
    {
        $kategoriPenilaian = FormPenilaian::whereIn('nama_fta', ['Seminar I', 'Seminar II', 'Seminar III', 'Sidang Akhir'])
            ->orderBy('nama_fta')
            ->distinct()
            ->pluck('nama_fta');

        return view('KelolaPenilaianTA.views.pengelolaan-nilai.kelola_penilaian_ta', [
            'kategoriPenilaian' => $kategoriPenilaian,
        ]);
    }

    /**
     * Menampilkan detail nilai mahasiswa
     * 
     * @param string $namaFta
     */
    public function detailNilaiMahasiswa($namaFta): View
    {
        $namaFtaSlug = Str::slug($namaFta, ' ');
        
        $detailInformasiFta = FormPenilaian::where('nama_fta', $namaFtaSlug)
            ->select('kode_fta', 'nama_fta', 'id_prodi', 'id_fta')
            ->orderBy('nama_fta')
            ->distinct()
            ->get();
    
        $idFtaList = $detailInformasiFta->pluck('id_fta')->toArray();

        $detailNilaiMahasiswa = Mahasiswa::with([
            'nilaiKategori' => function ($query) use ($idFtaList) {
                $query->whereHas('kategoriPenilaian', function ($q) use ($idFtaList) {
                    $q->whereIn('id_fta', $idFtaList);
                });
            },
            'nilaiKategori.kategoriPenilaian',
            'nilaiKategori.dosen',
            'user',
            'kota.detailFeedback',
        ])->get();

        return view('KelolaPenilaianTA.views.pengelolaan-nilai.detail_nilai_mahasiswa', compact('detailNilaiMahasiswa', 'detailInformasiFta', 'namaFta'));
    }

    public function togglePublishNilai($namaFta, $idKota, $action)
    {
        $nip = auth()->user()->username;
        $namaFtaSlug = Str::slug($namaFta, ' ');
    
        $mahasiswa = Mahasiswa::where('id_kota', $idKota)->first();
    
        $idProdi = $mahasiswa->id_prodi;
    
        $formPenilaian = FormPenilaian::where([
                ['nama_fta', $namaFtaSlug],
                ['id_prodi', $idProdi],
                ['jenis_form', 'feedback']
            ])
            ->with('aspekFeedback')
            ->first();
    
        $aspekFeedback = $formPenilaian->aspekFeedback->pluck('id_feedback')->toArray();
        
        $status = $action === 'publish' ? 'dipublikasikan' : 'draft';
        
        DetailFeedback::where('id_kota', $idKota)
            ->where('nip', $nip)
            ->whereIn('id_feedback', $aspekFeedback)
            ->update(['status_penilaian_dosen' => $status]);
        
        $message = $action === 'publish' ? 'Nilai berhasil dipublikasikan.' : 'Nilai berhasil diunpublikasikan.';
        
        return back()->with('success', $message);
    }
    
}