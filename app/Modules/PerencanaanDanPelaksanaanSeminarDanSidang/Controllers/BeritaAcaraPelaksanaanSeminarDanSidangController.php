<?php

namespace App\Modules\PerencanaanDanPelaksanaanSeminarDanSidang\Controllers;

use App\Modules\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use App\Models\Kehadiran;

class BeritaAcaraPelaksanaanSeminarDanSidangController extends Controller
{
    public function index(): View
    {
        return view('PerencanaanDanPelaksanaanSeminar3DanSidang.views.view');
    }

    public function indexBeritaAcaraSeminar3(): View
    {
        // Ambil nim dari user yang sedang login
        $nim = auth()->user()->username; // Asumsi username adalah nim 

        // Simulasikan waktu sekarang
        // $sekarang = Carbon::parse('2025-03-17 00:00:00'); // Untuk simulasi
        // $sekarang->setTimezone('Asia/Jakarta'); // Set timezone ke WIB
        $sekarang = Carbon::now('Asia/Jakarta');

        // Data presensi sementara
        $beritaAcaraSeminar3 = Kehadiran::with(['penjadwalan', 'user'])
        ->whereHas('penjadwalan', function ($query) {
            $query->where('agenda', 'seminar_3'); // Ambil hanya data Seminar 3
        })
        ->whereHas('user', function ($query) use ($nim) {
            $query->where('username', $nim); // Filter berdasarkan nim mahasiswa yang sedang login
        })
        ->get();

        // Teruskan $sekarang dan $beritaAcaraSeminar3 ke view
        return view('PerencanaanDanPelaksanaanSeminarDanSidang.views.BeritaAcara.BeritaAcaraSeminar3', compact('beritaAcaraSeminar3', 'sekarang'));
    }


    public function indexBeritaAcaraSidangTA(): View
    {
        // Ambil nim dari user yang sedang login
        $nim = auth()->user()->username;
        
        // Simulasikan waktu sekarang
        // $sekarang = Carbon::parse('2025-03-17 00:00:00'); // Untuk simulasi
        // $sekarang->setTimezone('Asia/Jakarta'); // Set timezone ke WIB
        $sekarang = Carbon::now('Asia/Jakarta');

        // Ambil data kehadiran dari database
        $beritaAcaraSidangTA = Kehadiran::with(['penjadwalan', 'user'])
            ->whereHas('penjadwalan', function ($query) {
                $query->where('agenda', 'sidang'); // Ambil hanya data sidang TA
            })
            ->whereHas('user', function ($query) use ($nim) {
                $query->where('username', $nim); // Filter berdasarkan nim mahasiswa yang sedang login
            })
            ->get();

        // Teruskan $sekarang dan $beritaAcaraSidangTA ke view
        return view('PerencanaanDanPelaksanaanSeminarDanSidang.views.BeritaAcara.BeritaAcaraSidangTA', compact('beritaAcaraSidangTA', 'sekarang'));
    }

    public function rekapBeritaAcaraSeminar3(): View
    {
    // Ambil data kehadiran dari database untuk Seminar 3
    $beritaAcaraSeminar3 = Kehadiran::with(['penjadwalan', 'user'])
        ->whereHas('penjadwalan', function ($query) {
            $query->where('agenda', 'seminar_3'); // Ambil hanya data Seminar 3
        })
        ->get();

    // Teruskan $beritaAcaraSeminar3 ke view rekap
    return view('PerencanaanDanPelaksanaanSeminarDanSidang.views.RekapBeritaAcara.RekapBeritaAcaraSeminar3', compact('beritaAcaraSeminar3'));
    }
    
    public function rekapBeritaAcaraSidangTA(): View
    {
        // Data Berita Acara Sidang TA Semua MHS Sementara
        $beritaAcaraSidangTA = Kehadiran::with(['penjadwalan', 'user'])
        ->whereHas('penjadwalan', function ($query) {
            $query->where('agenda', 'sidang'); // Ambil hanya data sidang TA
        })
        ->get();

        // Teruskan $beritaAcaraSidangTA ke view rekap
        return view('PerencanaanDanPelaksanaanSeminarDanSidang.views.RekapBeritaAcara.RekapBeritaAcaraSidangTA', compact('beritaAcaraSidangTA'));
    }


    public function simpanKehadiran(Request $request)
    {
        // Validasi request
        $request->validate([
            'id_kehadiran' => 'required|exists:kehadiran,id_kehadiran',
            'status_hadir' => 'required|in:hadir,belum_absen,tidak_hadir',
        ]);

        // Update status kehadiran di database
        $kehadiran = Kehadiran::find($request->input('id_kehadiran'));
        $kehadiran->status_hadir = $request->input('status_hadir');
        $kehadiran->save();

        return redirect()->back()->with('success', 'Status kehadiran berhasil disimpan.');
    }

    public function simpanDokumentasi(Request $request)
    {
        // Validasi request
        $request->validate([
            'id_kehadiran' => 'required|exists:kehadiran,id_kehadiran',
            'dokumentasi' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120', // Max 5 MB
        ], [
            'dokumentasi.max' => 'Ukuran file tidak boleh lebih dari 5 MB.', // Pesan custom
        ]);

        // Ambil id_kehadiran dari request
        $idKehadiran = $request->input('id_kehadiran');

        // Simpan file baru ke storage
        $dokumentasiPath = $request->file('dokumentasi')->store('dokumentasi', 'public');

        // Update path file di database
        $kehadiran = Kehadiran::find($idKehadiran);
        $kehadiran->foto_sidang = $dokumentasiPath;
        $kehadiran->save();

        return redirect()->back()->with('success', 'Dokumentasi berhasil diupload.');
    }

    public function simpanBatasRevisi(Request $request)
    {
        // Validasi request
        $request->validate([
            'id_kehadiran' => 'required|exists:kehadiran,id_kehadiran',
            'batas_revisi' => 'required|date',
        ]);

        // Update batas revisi di database
        $kehadiran = Kehadiran::find($request->input('id_kehadiran'));
        $kehadiran->batas_revisi = $request->input('batas_revisi');
        $kehadiran->save();

        return redirect()->back()->with('success', 'Batas revisi berhasil disimpan.');
    }

    public function simpanStatusKelulusan(Request $request)
    {
        // Validasi request
        $request->validate([
            'id_kehadiran'      => 'required|exists:kehadiran,id_kehadiran',
            'status_kelulusan'  => 'required|in:lulus_tanpa_perbaikan_laporan,lulus_dengan_perbaikan_laporan,mengulang_sidang_tugas_akhir,tidak_lulus,pending',
        ]);

        // Update status kelulusan di database
        $kehadiran = Kehadiran::find($request->input('id_kehadiran'));
        $kehadiran->status_kelulusan = $request->input('status_kelulusan');
        $kehadiran->save();

        return redirect()->back()->with('success', 'Status kelulusan berhasil disimpan.');
    }
}
