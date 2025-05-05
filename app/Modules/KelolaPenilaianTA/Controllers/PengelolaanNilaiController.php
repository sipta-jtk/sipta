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
use App\Models\NilaiKategori;

class PengelolaanNilaiController extends Controller{
    /**
     * Menampilkan halaman kelola penilaian
     * 
     */
    public function kelolaNilai(): View
    {

        // TBD Seminar I mungkin bisa dianggap penilaian saja
        $kategoriPenilaian = FormPenilaian::whereIn('nama_fta', ['Seminar I', 'Seminar II', 'Seminar III', 'Sidang Akhir'])
        ->where(function ($query) {
            $query->where('jenis_form', 'penilaian')
                  ->orWhere('nama_fta', 'Seminar I'); // Biarkan "Seminar I" tanpa filter jenis_form
        })
        ->with('prodi', 'kategoriPenilaian')
        ->orderBy('id_prodi')
        ->orderBy('nama_fta')
        ->get();
    
        $prodiList = $kategoriPenilaian->pluck('prodi')->unique('nama_prodi')->values();

        return view('KelolaPenilaianTA.views.pengelolaan-nilai.kelola_penilaian_ta', [
            'kategoriPenilaian' => $kategoriPenilaian,
            'prodiList' => $prodiList,
        ]);
    }

    /**
     * Menampilkan detail nilai mahasiswa
     * 
     * @param string $namaFta
     */
    public function detailNilaiMahasiswa($namaFta, $idProdi): View
    {
        $nip = auth()->user()->username;
        $namaFtaSlug = Str::slug($namaFta, ' ');

        $detailInformasiFta = FormPenilaian::where('nama_fta', $namaFtaSlug)
            ->orderBy('nama_fta')
            ->where('id_prodi', $idProdi)
            ->with('prodi')
            ->first();
        
        $idFtaPenilaian = FormPenilaian::where('nama_fta', $namaFtaSlug)
            ->orderBy('nama_fta')
            ->where('id_prodi', $idProdi)
            ->where('jenis_form', 'penilaian')
            ->with('prodi')
            ->first()
            ->id_fta;

        $idFtaFeedback = FormPenilaian::where('nama_fta', $namaFtaSlug)
            ->orderBy('nama_fta')
            ->where('id_prodi', $idProdi)
            ->where('jenis_form', 'feedback')
            ->with('prodi')
            ->first()
            ->id_fta;

        $detailNilaiMahasiswa = Mahasiswa::where('id_prodi', $idProdi)
            ->where('mahasiswa.status_ta', 'mahasiswa_ta')
            ->whereNotNull('mahasiswa.id_kota')
            ->with([
                'nilaiKategori' => function ($query) use ($idFtaPenilaian) {
                    $query->whereHas('kategoriPenilaian', function ($q) use ($idFtaPenilaian) {
                        $q->where('id_fta', $idFtaPenilaian);
                    });
                },
                'nilaiKategori.dosen',
                'user',
            ])
            ->get();

        return view('KelolaPenilaianTA.views.pengelolaan-nilai.detail_nilai_mahasiswa', compact('detailNilaiMahasiswa', 'detailInformasiFta', 'namaFta', 'nip'));
    }

    /**
     * Mengubah status publish nilai
     * 
     * @param string $namaFta
     * @param int $idKota
     * @param string $action
     * @return \Illuminate\Http\RedirectResponse
     */
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
        
        $status = $action === 'publish' ? 'dipublikasikan' : 'draf';
        
        DetailFeedback::where('id_kota', $idKota)
            ->where('nip', $nip)
            ->whereIn('id_feedback', $aspekFeedback)
            ->update(['status_penilaian_dosen' => $status]);

        NilaiKategori::where('nip', $nip)
            ->whereHas('kategoriPenilaian.formulirPenilaian', function ($query) use ($namaFtaSlug, $idProdi) {
                $query->where('nama_fta', $namaFtaSlug)
                      ->where('id_prodi', $idProdi);
            })
            ->update(['status_penilaian_dosen' => $status]);
        
        $message = $action === 'publish' ? 'Nilai berhasil dipublikasikan.' : 'Nilai berhasil diunpublikasikan.';
        
        return back()->with('success', $message);
    }

    /**
     * Mengunci atau membuka kunci penilaian
     * 
     * @param string $namaFta
     * @param int $idProdi
     * @param string $action
     * @return \Illuminate\Http\RedirectResponse
     */
    public function toggleKunciPenilaian($idKategori, $action)
    {
        $kategoriPenilaian = kategoriPenilaian::find($idKategori);
    
        if ($kategoriPenilaian) {
            $kategoriPenilaian->kunci_penilaian = $action === 'kunci' ? 1 : 0;
            $kategoriPenilaian->save();
    
            $message = $action === 'kunci' ? 'Penilaian berhasil dikunci.' : 'Penilaian berhasil dibuka kuncinya.';
            return back()->with('success', $message);
        }
    
        return back()->with('error', 'Kategori penilaian tidak ditemukan.');
    }
    
}