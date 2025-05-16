<?php

namespace App\Modules\UserManagement\Controllers;

use App\Modules\Controller;
use App\Models\User;
use App\Models\Kota;
use App\Models\Mahasiswa;
use App\Models\Dosen;
use App\Models\Kbk;
use App\Models\Prodi;
use App\Models\Bidang;
use App\Models\PengajuanPembimbing;
use App\Models\Penjadwalan;
use App\Models\PengajuanJadwalKota;
use App\Models\VerifikasiBerkasPengajuan;
use App\Models\KotaArtefak;
use App\Models\Artefak;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{

    public function index()
    {
        $user = auth()->user();
        $role = $user->role_user;

        /* MAHASISWA */
        if ($role === 'mahasiswa') {
            $mahasiswa = Mahasiswa::where('nim', $user->username)->first();
            $prodi = Prodi::find($mahasiswa->id_prodi);
            $kota = $mahasiswa->kota ?? null;
            $anggotaKelompok = $mahasiswa->id_kota 
                ? Mahasiswa::where('id_kota', $mahasiswa->id_kota)->where('nim', '!=', $mahasiswa->nim)->get()
                : collect([]);

            $pembimbing = [
                (object)['urutan_prioritas_terpilih' => 1, 'nama' => 'Pembimbing 1 belum ditentukan', 'nip' => '-'],
                (object)['urutan_prioritas_terpilih' => 2, 'nama' => 'Pembimbing 2 Belum ditentukan', 'nip' => '-']
            ];

            try {
                if (Schema::hasTable('alokasi_dosen')) {
                    $dosenPembimbing = DB::table('pengajuan_pembimbing')
                        ->join('alokasi_dosen', 'pengajuan_pembimbing.id_pengajuan_pembimbing', '=', 'alokasi_dosen.id_pengajuan_pembimbing')
                        ->join('dosen', 'alokasi_dosen.nip', '=', 'dosen.nip')
                        ->join('user', 'dosen.nip', '=', 'user.username')
                        ->where('pengajuan_pembimbing.id_kota', $kota->id_kota ?? null)
                        ->where('alokasi_dosen.tipe_alokasi', 'pembimbing')
                        ->where('alokasi_dosen.status_alokasi', 'fix')
                        ->orderBy('alokasi_dosen.urutan_prioritas_terpilih', 'asc')
                        ->select('alokasi_dosen.urutan_prioritas_terpilih', 'user.nama', 'dosen.nip')
                        ->get();

                    if ($dosenPembimbing->isNotEmpty()) {
                        $pembimbing = $dosenPembimbing;
                    }
                }
            } catch (\Exception $e) {}

            // Berkas Seminar 3
            $idKota = $mahasiswa->id_kota;
            $pengajuanSeminar = VerifikasiBerkasPengajuan::where('id_kota', $idKota)->first();
            if($pengajuanSeminar) {
                $pengajuanSeminar->formatted_tanggal_pengajuan = Carbon::parse($pengajuanSeminar->tanggal_pengajuan)->translatedFormat('d F Y H:i');
            }

            $namaArtefakSeminar = ['FTA 10', 'FTA 10a', 'Proposal Tugas Akhir', 'Presentasi'];
            $artefakSeminar = $this->cekArtefak($namaArtefakSeminar, $idKota);

            // Berkas Sidang
            $pengajuanSidang = VerifikasiBerkasPengajuan::where('id_kota', $idKota)
                ->where('jenis_pengajuan', 'sidang_akhir')
                ->first();
            if($pengajuanSidang) {
                $pengajuanSidang->formatted_tanggal_pengajuan = Carbon::parse($pengajuanSidang->tanggal_pengajuan)->translatedFormat('d F Y H:i');
            }

            $namaArtefakSidang = ['FTA 14', 'FTA 14a', 'Laporan Tugas Akhir', 'Presentasi'];
            $artefakSidang = $this->cekArtefak($namaArtefakSidang, $idKota);

            return view('welcome', compact(
                'user', 'mahasiswa', 'prodi',
                'kota', 'anggotaKelompok', 'pembimbing',
                'pengajuanSeminar', 'artefakSeminar',
                'pengajuanSidang', 'artefakSidang'
            ));
        }
        
        /* DOSEN */
        elseif ($role === 'dosen'){
            $dosen = Dosen::with('ketertarikanBidang.bidang')->where('nip', $user->username)->first();
            $idKota = DB::table('alokasi_dosen')
                ->join('pengajuan_pembimbing', 'alokasi_dosen.id_pengajuan_pembimbing', '=', 'pengajuan_pembimbing.id_pengajuan_pembimbing')
                ->where('alokasi_dosen.nip', $user->username)
                ->where('alokasi_dosen.tipe_alokasi', 'pembimbing')
                ->where('alokasi_dosen.status_alokasi', 'fix')
                ->pluck('pengajuan_pembimbing.id_kota')
                ->unique()
                ->values();
            
            $kota = Kota::whereIn('id_kota', $idKota)->get();
            
            return view('welcome', compact('user', 'dosen', 'kota'));
        }

        /* ADMIN */
        elseif ($role === 'admin') {
            $dosenCount = Dosen::count();
            $mahasiswaCount = Mahasiswa::count();
            
            return view('welcome', compact('user', 'dosenCount', 'mahasiswaCount'));
        }

    }

    private function cekArtefak(array $namaArtefak, $idKota)
    {
        $artefaks = Artefak::whereIn('nama_artefak', $namaArtefak)->get();
        $data = [];

        foreach ($namaArtefak as $nama) {
            $artefak = $artefaks->firstWhere('nama_artefak', $nama);
            $isUploaded = $artefak
                ? KotaArtefak::where('id_artefak', $artefak->id_artefak)
                    ->where('id_kota', $idKota)
                    ->whereNotNull('file_pengumpulan')
                    ->exists()
                : false;

            $data[] = [
                'nama_artefak' => $nama,
                'status' => $isUploaded ? 'Sudah diunggah' : 'Belum diunggah',
            ];
        }

        return $data;
    }


    /* =============================== Mahasiswa =============================== */
    /*
        Fungsi untuk menampilkan data profil mahasiswa
    */
    public function showProfileMahasiswa()
    {
        $user = auth()->user();
        $mahasiswa = Mahasiswa::where('nim', $user->username)->first();
        $prodi = Prodi::where('id_prodi', $mahasiswa->id_prodi)->first();

        return view('welcome', compact('user', 'mahasiswa', 'prodi'));
    }

    /*
        Fungsi untuk menampilkan data kota mahasiswa
    */
    public function showKoTAMahasiswa(){
        $user = auth()->user();
        $mahasiswa = Mahasiswa::where('nim', $user->username)->first();

        if($mahasiswa->status_ta == "mahasiswa_ta"){
            $kota = $mahasiswa->kota ?? null;

            if($mahasiswa->id_kota){
                $anggotaKelompok = Mahasiswa::where('id_kota', $mahasiswa->id_kota)
                    ->where('nim', '!=', $mahasiswa->nim)
                    ->get();
            }
        }

        $pembimbing = [
            (object)['urutan_prioritas_terpilih' => 1, 'nama' => 'Belum ditentukan', 'nip' => '-'],
            (object)['urutan_prioritas_terpilih' => 2, 'nama' => 'Belum ditentukan', 'nip' => '-']
        ];

        // Coba ambil data dosen pembimbing dengan tabel alokasi_dosen
        try 
        {
            if (Schema::hasTable('alokasi_dosen')) 
            {
                $dosenPembimbing = DB::table('pengajuan_pembimbing')
                    ->join('alokasi_dosen', 'pengajuan_pembimbing.id_pengajuan_pembimbing', '=', 'alokasi_dosen.id_pengajuan_pembimbing')
                    ->join('dosen', 'alokasi_dosen.nip', '=', 'dosen.nip')
                    ->join('user', 'dosen.nip', '=', 'user.username')
                    ->where('pengajuan_pembimbing.id_kota', $kota->id_kota)
                    ->where('alokasi_dosen.tipe_alokasi', 'pembimbing')
                    ->where('alokasi_dosen.status_alokasi', 'fix')
                    ->orderBy('alokasi_dosen.urutan_prioritas_terpilih', 'asc')
                    ->select('alokasi_dosen.urutan_prioritas_terpilih', 'user.nama', 'dosen.nip')
                    ->get();

                if ($dosenPembimbing->isNotEmpty()) 
                {
                    $pembimbing = $dosenPembimbing;
                }
            }
        } catch (\Exception $e){}

    }

    /*
        Fungsi untuk menampilkan berkas pengajuan seminar 3
    */
    public function showPengajuanSeminar3()
    {
        $user = auth()->user();
        $idKota = $user->mahasiswa->id_kota;
        
        // Ambil pengajuan berdasarkan id_kota
        $pengajuan = VerifikasiBerkasPengajuan::where('id_kota', $idKota)->first();
        
        if($pengajuan) {
            $pengajuan->formatted_tanggal_pengajuan = Carbon::parse($pengajuan->tanggal_pengajuan)->translatedFormat('d F Y H:i');
        }
    
        // Daftar artefak yang harus di-upload
        $namaArtefak = ['FTA 10', 'FTA 10a', 'Proposal Tugas Akhir', 'Presentasi'];
        $artefaks = Artefak::whereIn('nama_artefak', $namaArtefak)->get();
        $data = [];
    
    
        foreach ($artefaks as $artefak) {
            // Cek apakah artefak sudah di-upload
            $isUploaded = KotaArtefak::where('id_artefak', $artefak->id_artefak)
                ->where('id_kota', $idKota)
                ->whereNotNull('file_pengumpulan')
                ->exists();
        
            $data[] = [
                'nama_artefak' => $artefak->nama_artefak,
                'status' => $isUploaded ? 'Sudah diunggah' : 'Belum diunggah',
            ];
        
        }
    
        // Tambahkan artefak yang tidak ditemukan di database dengan status "Belum diunggah"
        foreach ($namaArtefak as $nama) {
            $exists = collect($data)->contains('nama_artefak', $nama);
            if (!$exists) {
                $data[] = [
                    'nama_artefak' => $nama,
                    'status' => 'Belum diunggah',
                ];
            
            }
        }
    }

    /*
        Fungsi untuk menampilkan berkas sidang
    */
    public function showPengajuanSidang()
    {
        $user = auth()->user();
        $idKota = $user->mahasiswa->id_kota;

        // Ambil pengajuan berdasarkan id_kota
        $pengajuan = VerifikasiBerkasPengajuan::where('id_kota', $idKota)
            ->where('jenis_pengajuan', 'sidang_akhir')
            ->first();

        if($pengajuan) {
            $pengajuan->formatted_tanggal_pengajuan = Carbon::parse($pengajuan->tanggal_pengajuan)->translatedFormat('H:i d F Y');
        }

        // Daftar artefak yang harus di-upload
        $namaArtefak = ['FTA 14', 'FTA 14a', 'Laporan Tugas Akhir', 'Presentasi'];
        $artefaks = Artefak::whereIn('nama_artefak', $namaArtefak)->get();
        $data = [];

        foreach ($artefaks as $artefak) {
            // Cek apakah artefak sudah di-upload
            $isUploaded = KotaArtefak::where('id_artefak', $artefak->id_artefak)
                ->where('id_kota', $idKota)
                ->whereNotNull('file_pengumpulan')
                ->exists();

            $data[] = [
                'nama_artefak' => $artefak->nama_artefak,
                'status' => $isUploaded ? 'Sudah diunggah' : 'Belum diunggah',
            ];
        }

        // Tambahkan artefak yang tidak ditemukan di database dengan status "Belum di-upload"
        foreach ($namaArtefak as $nama) {
            $exists = collect($data)->contains('nama_artefak', $nama);
            if (!$exists) {
                $data[] = [
                    'nama_artefak' => $nama,
                    'status' => 'Belum diunggah',
                ];
            }
        }
    }
    
    /* =============================== Dosen =============================== */
    /*
        Fungi menampilkan profil dosen
    */
    public function showProfileDosen()
    {
        $user = auth()->user();
        $dosen = Dosen::with('ketertarikanBidang.bidang')->where('nip', $user->username)->first();

    }

    public function showKoTABimbingan()
    {
        $user = auth()->user();
        $nip = $user->username;

        // Ambil daftar id_kota yang dibimbing oleh dosen ini
        $idKota = DB::table('alokasi_dosen')
            ->join('pengajuan_pembimbing', 'alokasi_dosen.id_pengajuan_pembimbing', '=', 'pengajuan_pembimbing.id_pengajuan_pembimbing')
            ->where('alokasi_dosen.nip', $nip)
            ->where('alokasi_dosen.tipe_alokasi', 'pembimbing')
            ->where('alokasi_dosen.status_alokasi', 'fix')
            ->pluck('pengajuan_pembimbing.id_kota')
            ->unique()
            ->values();

        // Contoh: tampilkan untuk debug
        $kota = Kota::where('id_kota', $idKota)->get();

        return view('profile.kota_bimbingan', compact('idKota'));
    }



    public function showProfileAdmin()
    {
        $user = auth()->user();
        $dosenCount = Dosen::count();
        $mahasiswaCount = Mahasiswa::Count();

    }
    
}