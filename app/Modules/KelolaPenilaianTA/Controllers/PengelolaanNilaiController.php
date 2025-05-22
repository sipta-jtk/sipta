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
use App\Services\Notifikasi;

class PengelolaanNilaiController extends Controller{
    /**
     * Menampilkan halaman kelola penilaian
     * 
     */
    public function kelolaNilai(): View
    {
        $kategoriPenilaian = FormPenilaian::whereIn('nama_fta', ['Seminar I', 'Seminar II', 'Seminar III', 'Sidang Akhir'])
        ->where(function ($query) {
            $query->where('jenis_form', 'penilaian')
                  ->orWhere('nama_fta', 'Seminar I'); // Biarkan "Seminar I" tanpa filter jenis_form
        })
        ->where(function ($query) {
            $query->whereNot('jenis_ta', 'penelitian')
                  ->orWhereNull('jenis_ta'); // Sertakan nilai NULL
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
            ->get();
        
        $idFtaPenilaian = $detailInformasiFta->where('jenis_form', 'penilaian')
            ->pluck('id_fta');

        $idFtaFeedback = $detailInformasiFta->where('jenis_form', 'feedback')
            ->pluck('id_fta');

        $detailNilaiMahasiswa = Mahasiswa::where('id_prodi', $idProdi)
            ->where('mahasiswa.status_ta', 'mahasiswa_ta')
            ->whereHas('kota', function ($query) {
                $query->where('status_kota', 'aktif');
            })
            ->with([
                'nilaiKategori' => function ($query) use ($idFtaPenilaian) {
                    $query->whereHas('kategoriPenilaian', function ($q) use ($idFtaPenilaian) {
                        $q->whereIn('id_fta', $idFtaPenilaian);
                    });
                },
                'nilaiKategori.dosen',
                'user',
                'kota.detailFeedback' => function ($query) use ($idFtaFeedback) {
                    $query->whereHas('aspekFeedback', function ($q) use ($idFtaFeedback) {
                        $q->whereIn('id_fta', $idFtaFeedback);
                    });
                },
                'kota.detailFeedback'
            ])
            ->get();
        
        return view('KelolaPenilaianTA.views.pengelolaan-nilai.detail_nilai_mahasiswa', [
            'detailNilaiMahasiswa' => $detailNilaiMahasiswa,
            'detailInformasiFta' => $detailInformasiFta->first(),
            'namaFta' => $namaFta,
            'nip' => $nip,
        ]);
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
    
        $mahasiswa = Mahasiswa::where('id_kota', $idKota)
            ->with('kota')
            ->get();
        
        $nimList = $mahasiswa->pluck('nim')->toArray();
        $jenisTa = $mahasiswa->first()->kota->jenis_ta;
        $idProdi = $mahasiswa->first()->id_prodi;
    
        $formPenilaian = FormPenilaian::where([
                ['nama_fta', $namaFtaSlug],
                ['id_prodi', $idProdi],
                ['jenis_form', 'feedback']
            ])
            ->with('aspekFeedback')
            ->first();
    
        $aspekFeedback = $formPenilaian->aspekFeedback->pluck('id_feedback')->toArray();
        
        $status = $action === 'publish' ? 'dipublikasikan' : 'draf';

        $detailFeedback = DetailFeedback::where('id_kota', $idKota)
            ->where('nip', $nip)
            ->whereIn('id_feedback', $aspekFeedback);

        $nilaiKategori = NilaiKategori::where('nip', $nip)
            ->whereIn('nim', $nimList)
            ->whereHas('kategoriPenilaian.formulirPenilaian', function ($query) use ($namaFtaSlug, $idProdi) {
            $query->where('nama_fta', $namaFtaSlug)
                    ->where('jenis_form', 'penilaian')
                    ->where('id_prodi', $idProdi);
        });

        if ($detailFeedback->get()->isEmpty() && $nilaiKategori->get()->isEmpty()) {
            return back()->with('error', 'Feedback dan Nilai untuk Kelompok ini belum lengkap.');
        } elseif ($detailFeedback->get()->isEmpty()) {
            return back()->with('error', 'Feedback untuk Kelompok ini belum lengkap.');
        } else if ($nilaiKategori->get()->isEmpty()) {
            return back()->with('error', 'Nilai untuk Kelompok ini belum lengkap.');
        }

        // Kirim notifikasi
        foreach ($mahasiswa as $mhs) {
            Notifikasi::kirim(
                'Nilai sudah di publikasikan', // template notifikasi
                $mhs->user->username, // id user/mahasiswa tujuan
                [
                    'nama_dosen' => auth()->user()->nama, // dosen pengirim
                ]
            );
        }

        $detailFeedback->update(['status_penilaian_dosen' => $status]);    
        $nilaiKategori->update(['status_penilaian_dosen' => $status]);
        
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
        $kategoriPenilaian = kategoriPenilaian::where('id_kategori', $idKategori)
            ->with('formulirPenilaian')
            ->get()
            ->first();
        
        $namaFta = $kategoriPenilaian->formulirPenilaian->nama_fta;
        $idProdi = $kategoriPenilaian->formulirPenilaian->id_prodi;

        $formPenilaian = FormPenilaian::where([
            ['nama_fta', $namaFta],
            ['id_prodi', $idProdi],
            ['jenis_form', 'penilaian']
        ])
        ->with('kategoriPenilaian')
        ->get();

        if ($formPenilaian) {
            $formPenilaian->each(function ($penilaian) use ($action) {
                $penilaian->kategoriPenilaian->each(function ($kategori) use ($action) {
                    $kategori->kunci_penilaian = $action === 'kunci' ? 1 : 0;
                    $kategori->save();
                });
            });

            return back()->with('success', 'Penilaian berhasil ' . ($action === 'kunci' ? 'dikunci.' : 'dibuka kunci.'));
        }

        return back()->with('error', 'Gagal mengubah status kunci penilaian.');
    }
}