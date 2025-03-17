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
        // ID kota statis = 2 (karena fitur login belum selesai)
        $idKota = 2;

        // Ambil hanya mahasiswa dengan id_kota = 2
        $mahasiswaList = Mahasiswa::with('user')
            ->where('id_kota', $idKota)
            ->get();

        // Ambil informasi KoTA berdasarkan id_kota = 2
        $kotaInfo = Kota::where('id_kota', $idKota)->first();

        // Ambil nama dosen pembimbing berdasarkan id_kota = 2
        $dosenPembimbing = DB::table('kota')
            ->join('pengajuan_pembimbing', 'kota.id_kota', '=', 'pengajuan_pembimbing.id_kota')
            ->join('alokasi_pembimbing', 'pengajuan_pembimbing.id_pengajuan_pembimbing', '=', 'alokasi_pembimbing.id_pengajuan_pembimbing')
            ->join('dosen', 'alokasi_pembimbing.nip', '=', 'dosen.nip')
            ->join('user', 'dosen.nip', '=', 'user.username')
            ->where('kota.id_kota', $idKota)
            ->where('alokasi_pembimbing.status_alokasi', 'fix')
            ->select('dosen.nip', 'user.nama as nama_dosen') // Tambahkan 'nip'
            ->get();


        // Kirimkan data ke tampilan Blade
        return view('KelolaPenilaianTA.views.monitoring-nilai-mahasiswa.monitoring_mahasiswa', compact('mahasiswaList', 'kotaInfo', 'dosenPembimbing'));
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