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
        // // Username yang diketahui
        // $username = '221524049';

        // // Ambil role_user dari tabel user
        // $user = DB::table('user')->where('username', $username)->first();

        // // Pastikan user ditemukan dan memiliki role mahasiswa
        // if (!$user || $user->role_user !== 'mahasiswa') {
        //     abort(403, 'Akses ditolak karena bukan mahasiswa');
        // }

        // Ambil mahasiswa berdasarkan nim = username
        $mahasiswa = DB::table('mahasiswa')->where('nim', auth()->user()->username)
        ->first();

        
        $idKota = $mahasiswa->id_kota;
        $idProdi = $mahasiswa->id_prodi;

        // Ambil mahasiswa dengan id_kota yang sama
        $mahasiswaList = Mahasiswa::with('user')
            ->where('id_kota', $idKota)
            ->get();

        // Ambil informasi Kota berdasarkan id_kota
        $kotaInfo = Kota::where('id_kota', $idKota)->first();

        // Ambil nama dosen pembimbing dengan query yang lebih singkat
        $dosenPembimbing = DB::table('pengajuan_pembimbing')
            ->join('alokasi_pembimbing', 'pengajuan_pembimbing.id_pengajuan_pembimbing', '=', 'alokasi_pembimbing.id_pengajuan_pembimbing')
            ->join('dosen', 'alokasi_pembimbing.nip', '=', 'dosen.nip')
            ->join('user', 'dosen.nip', '=', 'user.username')
            ->where('pengajuan_pembimbing.id_kota', $idKota)
            ->where('alokasi_pembimbing.status_alokasi', 'fix')
            ->select('dosen.nip', 'user.nama as nama_dosen')
            ->get();

        /// 1. Ambil semua kode_fta yang ada dalam kategori_penilaian berdasarkan id_prodi mahasiswa
        $kodeFtaList = DB::table('form_penilaian')
        ->where('id_prodi', $idProdi)
        ->pluck('kode_fta');

        // 2. Dari hasil kode_fta yang diambil, cari yang memiliki jenis_form = 'penilaian' pada tabel form_penilaian
        $kodeFtaFiltered = DB::table('form_penilaian')
        ->whereIn('kode_fta', $kodeFtaList)
        ->where('jenis_form', 'penilaian')
        ->pluck('kode_fta');

        // 3. Ambil semua nama kategori yang memiliki kode_fta sesuai hasil filter sebelumnya
        $kategoriList = DB::table('kategori_penilaian')
        ->whereIn('kode_fta', $kodeFtaFiltered)
        ->select('kode_fta', 'nama_kategori')
        ->orderBy('nama_kategori', 'asc')
        ->get();


        // Kirimkan data ke tampilan Blade
        return view('KelolaPenilaianTA.views.monitoring-nilai-mahasiswa.monitoring_mahasiswa', compact(
            'mahasiswaList', 'kotaInfo', 'dosenPembimbing', 'kategoriList', 'idProdi'
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
    public function monitoringRubrik($kodeFta, $idProdi): View
    {
        // Ambil nama_kategori dari kategori_penilaian berdasarkan kode_fta
        $kategori = DB::table('kategori_penilaian')
            ->where('kode_fta', $kodeFta)
            ->select('nama_kategori')
            ->first();

        // Ambil rentang nilai hanya untuk A, AB, B, BC, C, dan CD
        $rentangNilai = DB::table('rentang_nilai')
            ->whereIn('id_nilai', ['A', 'AB', 'B', 'BC', 'C', 'CD'])
            ->select('id_nilai', 'batas_atas', 'batas_bawah')
            ->orderBy('batas_atas', 'desc')
            ->get();

        // Ambil data kriteria beserta rubriknya
        $namaKriteria = DB::table('kriteria_penilaian')
            ->where('kode_fta', $kodeFta)
            ->select('id_kriteria', 'nama_kriteria', 'bobot_kriteria')
            ->get()
            ->map(function ($kriteria) use ($rentangNilai) {
                // Ambil rubrik berdasarkan id_kriteria
                $kriteria->rubrik = DB::table('rubrik')
                    ->where('id_kriteria', $kriteria->id_kriteria)
                    ->select('id_rubrik', 'nama_rubrik')
                    ->get()
                    ->map(function ($rubrik) use ($rentangNilai) {
                        // Ambil detail rubrik berdasarkan id_rubrik dan id_nilai
                        $rubrik->detail = collect();
                        foreach ($rentangNilai as $nilai) {
                            $deskripsi = DB::table('detail_rubrik')
                                ->where('id_rubrik', $rubrik->id_rubrik)
                                ->where('id_nilai', $nilai->id_nilai)
                                ->select('detail_rubrik_penilaian')
                                ->first();
                            $rubrik->detail[$nilai->id_nilai] = $deskripsi->detail_rubrik_penilaian ?? '-';
                        }
                        return $rubrik;
                    });

                return $kriteria;
            });

        return view('KelolaPenilaianTA.views.monitoring-nilai-mahasiswa.monitoring_rubrik', compact('kategori', 'rentangNilai', 'namaKriteria'));
    }

}