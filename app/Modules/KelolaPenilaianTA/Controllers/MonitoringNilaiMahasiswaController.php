<?php

namespace App\Modules\KelolaPenilaianTA\Controllers;

use App\Modules\Controller;
use App\Models\Mahasiswa;
use App\Models\Kota;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Exports\RekapitulasiNilaiExport;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\FromCollection;

class MonitoringNilaiMahasiswaController extends Controller{

     /**
     * Menampilkan halaman monitoring nilai mahasiswa
     */
    public function monitoringMahasiswa(): View
    {
        // Username yang diketahui
        $username = '221524059';

        // Ambil role_user dari tabel user
        $user = DB::table('user')->where('username', $username)->first();

        // Pastikan user ditemukan dan memiliki role mahasiswa
        if (!$user || $user->role_user !== 'mahasiswa') {
            abort(403, 'Akses ditolak karena bukan mahasiswa');
        }

        // Ambil mahasiswa berdasarkan nim = username
        $mahasiswa = DB::table('mahasiswa')->where('nim', $username)->first();
        if (!$mahasiswa) {
            abort(404, 'Mahasiswa tidak ditemukan');
        }
        
        $idKota = $mahasiswa->id_kota;
        $idProdi = $mahasiswa->id_prodi; // Ambil id_prodi mahasiswa

        // Ambil hanya mahasiswa dengan id_kota yang ditemukan
        $mahasiswaList = Mahasiswa::with('user')
            ->where('id_kota', $idKota)
            ->get();

        // Ambil informasi Kota berdasarkan id_kota
        $kotaInfo = Kota::where('id_kota', $idKota)->first();

        // Ambil nama dosen pembimbing berdasarkan id_kota
        $dosenPembimbing = DB::table('kota')
            ->join('pengajuan_pembimbing', 'kota.id_kota', '=', 'pengajuan_pembimbing.id_kota')
            ->join('alokasi_pembimbing', 'pengajuan_pembimbing.id_pengajuan_pembimbing', '=', 'alokasi_pembimbing.id_pengajuan_pembimbing')
            ->join('dosen', 'alokasi_pembimbing.nip', '=', 'dosen.nip')
            ->join('user', 'dosen.nip', '=', 'user.username')
            ->where('kota.id_kota', $idKota)
            ->where('alokasi_pembimbing.status_alokasi', 'fix')
            ->select('dosen.nip', 'user.nama as nama_dosen') // Ambil nama (dari user) dan nip (dari dosen)
            ->get();

        // Ambil kode_fta yang memiliki id_prodi yang sama DAN jenis_form = 'penilaian'
        $kodeFtaList = DB::table('form_penilaian')
            ->where('id_prodi', $idProdi)
            ->where('jenis_form', 'penilaian') // Filter jenis_form
            ->pluck('kode_fta');

        // Ambil semua nama kategori dari kategori_penilaian yang memiliki kode_fta yang sesuai
        $kategoriList = DB::table('kategori_penilaian')
            ->whereIn('kode_fta', $kodeFtaList)
            ->select('nama_kategori')
            ->orderBy('nama_kategori', 'asc') // Urutkan dari A-Z
            ->get();


        // Kirimkan data ke tampilan Blade
        return view('KelolaPenilaianTA.views.monitoring-nilai-mahasiswa.monitoring_mahasiswa', compact(
            'mahasiswaList', 'kotaInfo', 'dosenPembimbing', 'kategoriList'
        ));
    }


    /**
     * Menampilkan halaman monitoring feedback
     * 
     */
    public function monitoringFeedback(): View
    {
        return view('KelolaPenilaianTA.views.monitoring-nilai-mahasiswa.monitoring_feedback');
    }

    /**
     * Menampilkan halaman monitoring rubrik
     */
    public function monitoringRubrik(): View
    {
        return view('KelolaPenilaianTA.views.monitoring-nilai-mahasiswa.monitoring_rubrik');
    }

}