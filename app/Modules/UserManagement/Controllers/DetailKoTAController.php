<?php

namespace App\Modules\UserManagement\Controllers;

use Illuminate\Http\Request;
use App\Modules\Controller;
use App\Models\User;
use App\Models\Kota;
use App\Models\Mahasiswa;
use App\Models\Bidang;
use App\Models\PengajuanPembimbing;
use App\Models\Prodi;
// use App\Models\AlokasiPembimbing;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * class DetailKoTAController
 */
class DetailKoTAController extends Controller
{    
    /**
     * function index
     * 
     * Menampilkan detail informasi suatu KoTA
     *
     * @param  mixed $id
     * @return void
     */
    public function index($id = null)
    {
        // Initialisasi mahasiswa variable
        $mahasiswa = null;
        $maksimalAnggota = 3; // Default value jika tidak ada data prodi

        // Jika ID tidak diberikan, ambil ID KoTA dari user yang login
        if ($id === null)
        {
            $user = Auth::user();

            // Pastikan user adalah mahasiswa
            if (!$user || $user->role_user !== 'mahasiswa') {
                return redirect()->back()->with('error', 'Anda tidak memiliki akses.');
            }

            // Ambil data mahasiswa berdasarkan user yang login
            $mahasiswa = Mahasiswa::where('nim', $user->username)->first();

            // Jika mahasiswa tidak memiliki KoTA
            if (!$mahasiswa || !$mahasiswa->id_kota) {
                return redirect()->back()->with('error', 'Anda belum tergabung dalam Kelompok TA.');
            }

            // Set ID menjadi ID KoTA mahasiswa
            $id = $mahasiswa->id_kota;
        }
        else {
            // Coba mengambil anggota KoTA untuk mendapatkan id_prodi
            $kotaAnggota = Mahasiswa::where('id_kota', $id)->first();
            if ($kotaAnggota) {
                $prodi = Prodi::find($kotaAnggota->id_prodi);
                if ($prodi) {
                    $maksimalAnggota = $prodi->maksimal_anggota_kota;
                }
            }
        }

        // Ambil data KoTA berdasarkan ID
        $kota = Kota::find($id);

        if (!$kota)
        {
            return redirect()->back()->with('error',  'Kelompok TA tidak ditemukan.');
        }

        // Jika memiliki data mahasiswa (untuk akses via sidebar 'Detail KoTA Saya')
        if ($mahasiswa) {
             // Ambil nilai maksimal anggota dari prodi mahasiswa
            $prodi = Prodi::find($mahasiswa->id_prodi);
            if ($prodi) 
            {
                $maksimalAnggota = $prodi->maksimal_anggota_kota;
            }   
        }

        // Ambil data anggota kelompok TA
        $anggota = Mahasiswa::join('user', 'mahasiswa.nim', '=', 'user.username')
            ->where('mahasiswa.id_kota', $id)
            ->select('mahasiswa.*', 'user.nama')
            ->get();

        // Ambil data bidang jika ada
        $bidang = null;
        if ($kota->id_bidang)
        {
            $bidang = Bidang::find($kota->id_bidang);
        }

        // Judul TA
        $judulTA = $kota->judul_ta ?? 'Belum ditentukan';

        // Jika bidang TA belum ditentukan
        $bidangTA = $bidang->bidang ?? 'Belum ditentukan';

        // Default pembimbing jika tidak ditemukan
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
                    ->where('pengajuan_pembimbing.id_kota', $id)
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
        } catch (\Exception $e) 
        {
            // Abaikan error dan gunakan default pembimbing
        }

        return view('UserManagement.views.detail-kota', compact('kota', 'anggota', 'judulTA', 'bidangTA', 'pembimbing', 'maksimalAnggota'));
    }
}
