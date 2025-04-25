<?php

namespace App\Modules\PerencanaanDanPelaksanaanSeminar3DanSidang\Controllers;

use Carbon\Carbon;
use App\Modules\Controller;
use App\Models\Kota;
use App\Models\Mahasiswa;
use App\Models\Penjadwalan;
use App\Models\PengajuanJadwalKota;
use App\Models\VerifikasiBerkasPengajuan;
use App\Models\KotaArtefak;
use App\Models\Artefak;

use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class VerifikasiBerkasPengajuanMahasiswaController extends Controller
{
    public function create()
    {
        $user = Auth::user();
    $idKota = $user->mahasiswa->id_kota;

    // Ambil pengajuan berdasarkan id_kota
    $pengajuan = VerifikasiBerkasPengajuan::where('id_kota', $idKota)->first();

    // Daftar artefak yang harus di-upload
    $namaArtefak = ['FTA 10', 'FTA 10a', 'Proposal Tugas Akhir', 'Presentasi'];
    $artefaks = Artefak::whereIn('nama_artefak', $namaArtefak)->get();

    $data = [];
    $adaBelumUpload = false; // Flag untuk cek apakah ada artefak yang belum di-upload

    foreach ($artefaks as $artefak) {
        // Cek apakah artefak sudah di-upload
        $isUploaded = KotaArtefak::where('id_artefak', $artefak->id_artefak)
            ->where('id_kota', $idKota)
            ->whereNotNull('file_pengumpulan')
            ->exists();

        $data[] = [
            'nama_artefak' => $artefak->nama_artefak,
            'status' => $isUploaded ? 'Sudah di-upload' : 'Belum di-upload',
        ];

        if (!$isUploaded) {
            $adaBelumUpload = true; // Set flag jika ada artefak yang belum di-upload
        }
    }

    // Tambahkan artefak yang tidak ditemukan di database dengan status "Belum di-upload"
    foreach ($namaArtefak as $nama) {
        $exists = collect($data)->contains('nama_artefak', $nama);
        if (!$exists) {
            $data[] = [
                'nama_artefak' => $nama,
                'status' => 'Belum di-upload',
            ];
            $adaBelumUpload = true; // Set flag jika ada artefak yang belum di-upload
        }
    }

    // Cek apakah pengajuan tidak bisa dilakukan
    $tidakBisaAjukan = $pengajuan && in_array($pengajuan->status_konfirmasi, ['disetujui', 'pending']) || $adaBelumUpload;

    return view('PerencanaanDanPelaksanaanSeminar3DanSidang.views.verifikasi.VerifikasiBerkasPengajuanMahasiswa', compact('pengajuan', 'data', 'tidakBisaAjukan', 'adaBelumUpload'));
    }

    public function store(Request $request)
    {
        VerifikasiBerkasPengajuan::create([
            'tanggal_pengajuan' => Carbon::now(),
            'jenis_pengajuan' => 'seminar_3',
            'id_kota' => Auth::user()->mahasiswa->id_kota,
            'status_konfirmasi' => 'pending',
        ]);

        return redirect()->route('verifikasi3.create')->with('success', 'Pengajuan berhasil diajukan.');
    }

    public function create_sidang()
    {
        $user = Auth::user();
        $idKota = $user->mahasiswa->id_kota;

        // Ambil pengajuan berdasarkan id_kota
        $pengajuan = VerifikasiBerkasPengajuan::where('id_kota', $idKota)
            ->where('jenis_pengajuan', 'sidang_akhir')
            ->first();

        // Daftar artefak yang harus di-upload
        $namaArtefak = ['FTA 14', 'FTA 14a', 'Laporan Tugas Akhir', 'Presentasi'];
        $artefaks = Artefak::whereIn('nama_artefak', $namaArtefak)->get();

        $data = [];
        $adaBelumUpload = false; // Flag untuk cek apakah ada artefak yang belum di-upload

        foreach ($artefaks as $artefak) {
            // Cek apakah artefak sudah di-upload
            $isUploaded = KotaArtefak::where('id_artefak', $artefak->id_artefak)
                ->where('id_kota', $idKota)
                ->whereNotNull('file_pengumpulan')
                ->exists();

            $data[] = [
                'nama_artefak' => $artefak->nama_artefak,
                'status' => $isUploaded ? 'Sudah di-upload' : 'Belum di-upload',
            ];

            if (!$isUploaded) {
                $adaBelumUpload = true; // Set flag jika ada artefak yang belum di-upload
            }
        }

    // Tambahkan artefak yang tidak ditemukan di database dengan status "Belum di-upload"
        foreach ($namaArtefak as $nama) {
            $exists = collect($data)->contains('nama_artefak', $nama);
            if (!$exists) {
                $data[] = [
                    'nama_artefak' => $nama,
                    'status' => 'Belum di-upload',
                ];
                $adaBelumUpload = true; // Set flag jika ada artefak yang belum di-upload
            }
        }

    // Cek apakah pengajuan tidak bisa dilakukan
        $tidakBisaAjukan = $pengajuan && in_array($pengajuan->status_konfirmasi, ['disetujui', 'pending']) || $adaBelumUpload;

        return view('PerencanaanDanPelaksanaanSeminar3DanSidang.views.verifikasi.VerifikasiBerkasPengajuanMahasiswaSidang', compact('pengajuan', 'data', 'tidakBisaAjukan', 'adaBelumUpload'));
    }

    public function store_sidang(Request $request)
    {
        VerifikasiBerkasPengajuan::create([
            'nip' => '196610181995121001',
            'tanggal_pengajuan' => Carbon::now(),
            'status_konfirmasi' => 'pending',
            'jenis_pengajuan' => 'sidang_akhir',
            'id_kota' => Auth::user()->mahasiswa->id_kota,
        ]);

        return redirect()->route('verifikasi-sidang.create')->with('success', 'Pengajuan berhasil diajukan.');
    }

    public function create_sidang()
    {
        $user = Auth::user();
        $idKota = $user->mahasiswa->id_kota;

        // Ambil pengajuan berdasarkan id_kota
        $pengajuan = VerifikasiBerkasPengajuan::where('id_kota', $idKota)
            ->where('jenis_pengajuan', 'sidang_akhir')
            ->first();

        // Daftar artefak yang harus di-upload
        $namaArtefak = ['FTA 14', 'FTA 14a', 'Laporan Tugas Akhir', 'Presentasi'];
        $artefaks = Artefak::whereIn('nama_artefak', $namaArtefak)->get();

        $data = [];
        $adaBelumUpload = false; // Flag untuk cek apakah ada artefak yang belum di-upload

        foreach ($artefaks as $artefak) {
            // Cek apakah artefak sudah di-upload
            $isUploaded = KotaArtefak::where('id_artefak', $artefak->id_artefak)
                ->where('id_kota', $idKota)
                ->whereNotNull('file_pengumpulan')
                ->exists();

            $data[] = [
                'nama_artefak' => $artefak->nama_artefak,
                'status' => $isUploaded ? 'Sudah di-upload' : 'Belum di-upload',
            ];

            if (!$isUploaded) {
                $adaBelumUpload = true; // Set flag jika ada artefak yang belum di-upload
            }
        }

    // Tambahkan artefak yang tidak ditemukan di database dengan status "Belum di-upload"
        foreach ($namaArtefak as $nama) {
            $exists = collect($data)->contains('nama_artefak', $nama);
            if (!$exists) {
                $data[] = [
                    'nama_artefak' => $nama,
                    'status' => 'Belum di-upload',
                ];
                $adaBelumUpload = true; // Set flag jika ada artefak yang belum di-upload
            }
        }

    // Cek apakah pengajuan tidak bisa dilakukan
        $tidakBisaAjukan = $pengajuan && in_array($pengajuan->status_konfirmasi, ['disetujui', 'pending']) || $adaBelumUpload;

        return view('PerencanaanDanPelaksanaanSeminar3DanSidang.views.verifikasi.VerifikasiBerkasPengajuanMahasiswaSidang', compact('pengajuan', 'data', 'tidakBisaAjukan', 'adaBelumUpload'));
    }

    public function store_sidang(Request $request)
    {
        VerifikasiBerkasPengajuan::create([
            'tanggal_pengajuan' => Carbon::now(),
            'status_konfirmasi' => 'pending',
            'jenis_pengajuan' => 'sidang_akhir',
            'id_kota' => Auth::user()->mahasiswa->id_kota,
        ]);

        return redirect()->route('verifikasi-sidang.create')->with('success', 'Pengajuan berhasil diajukan.');
    } //nambah
}