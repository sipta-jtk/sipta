<?php

namespace App\Modules\PengajuanAlokasiPembimbing\Controllers\PengajuanPembimbing;

use App\Models\User;
use App\Models\Mahasiswa;
use App\Models\Kota;
use App\Models\PengajuanPembimbing;
use App\Models\PrioritasPembimbing;
use App\Models\Dosen;
use App\Modules\Controller;
use Illuminate\Http\Request;
use DB;
use Illuminate\View\View;

class PengajuanPembimbingController extends Controller
{
    public function view_dataKelompok(): View
    {
        // // data mahasiswa yang login
        // $id_kota_user = DB::table('mahasiswa')
        //     ->where('nim', auth()->user()->username) 
        //     ->value('id_kota'); 

        // // data mahasiswa dalam kota yang sama dengan yang login
        // $listMahasiswa = DB::table('mahasiswa')
        //     ->join('user', 'mahasiswa.nim', '=', 'user.username')
        //     ->select('user.nama', 'mahasiswa.nim', 'mahasiswa.kelas')
        //     ->where('mahasiswa.id_kota', $id_kota_user)
        //     ->orderBy('user.nama', 'asc')
        //     ->get();

        $sessionUser = [
            'nama' => 'Welsya',
            'nim' => '221524032',
            'kelas' => 'D4A',
            'id_kota' => 2
        ];

        $dataAnggota = DB::table('mahasiswa')
            ->join('user', 'mahasiswa.nim', '=', 'user.username')
            ->join('kota', 'mahasiswa.id_kota', '=', 'kota.id_kota')
            ->select('user.nama', 'mahasiswa.nim')
            ->where('mahasiswa.id_kota', $sessionUser['id_kota'])
            ->where('mahasiswa.nim', '!=', $sessionUser['nim'])
            ->orderBy('user.nama', 'asc')
            ->get();

        return view('PengajuanAlokasiPembimbing.views.PengajuanPembimbing.DataKelompok', compact('sessionUser','dataAnggota'));
    }

    public function view_topikTugasAkhir(): View
    {
        $namaBidang = DB::table('bidang')
            ->select('bidang')
            ->orderBy('bidang', 'asc')
            ->get();
        return view('PengajuanAlokasiPembimbing.views.PengajuanPembimbing.TopikTugasAkhir', compact('namaBidang'));
    }

    public function view_prioritasDosenPembimbing(): View
    {
        $listDosen = DB::table('dosen')
            ->join('user', 'dosen.nip', '=', 'user.username')
            ->where('dosen.bersedia_membimbing', 'bersedia')
            ->select('user.nama', 'dosen.nip')
            ->orderBy('user.nama', 'asc')
            ->get();

        return view('PengajuanAlokasiPembimbing.views.PengajuanPembimbing.PrioritasDosenPembimbing', compact('listDosen'));
    }

    public function getDosenHistory($nip)
    {
        $history = DB::table('ketertarikan_bidang')
            ->join('bidang', 'ketertarikan_bidang.id_bidang', '=', 'bidang.id_bidang')
            ->where('ketertarikan_bidang.nip', $nip)
            ->select('bidang.bidang')
            ->orderBy('asc')
            ->get();
        
            return response()->json([
                'tahun' => 2025,
                'bidangList' => $history
            ]);
    }

    public function view_pratinjauFormulir(): View
    {
        // Ambil data mahasiswa yang sedang login
        $sessionUser = [
            'nama' => 'Welsya',
            'nim' => '221524032',
            'kelas' => 'D4A',
            'id_kota' => 2
        ];

        // Ambil data anggota kelompok berdasarkan kota yang sama
        $dataAnggota = DB::table('mahasiswa')
            ->join('user', 'mahasiswa.nim', '=', 'user.username')
            ->join('kota', 'mahasiswa.id_kota', '=', 'kota.id_kota')
            ->select('user.nama', 'mahasiswa.nim')
            ->where('mahasiswa.id_kota', $sessionUser['id_kota'])
            ->where('mahasiswa.nim', '!=', $sessionUser['nim'])
            ->orderBy('user.nama', 'asc')
            ->get();
        
        return view('PengajuanAlokasiPembimbing.views.PengajuanPembimbing.PratinjauFormulir', compact('sessionUser', 'dataAnggota'));
    }

    public function finalisasiData(Request $request)
    {
        // // Validasi data dari form
        // $validatedData = $request->validate([
        //     'topik' => 'required|string|max:255',
        //     'bidang' => 'required|array|min:1',
        //     'bidang.*' => 'string',
        //     'prioritas_dosen' => 'required|array|min:1',
        //     'prioritas_dosen.*' => 'string', // Validasi NIP dosen
        // ]);

        // Ambil data yang dikirim dari frontend
        $topik = $request->input('topik');
        $bidang = $request->input('bidang');
        $prioritas = $request->input('prioritas');
        
        $sessionUser = [
            'nama' => 'Welsya',
            'nim' => '221524032',
            'kelas' => 'D4A',
            'id_kota' => 2
        ];

        // Ambil ID Kota dari data Mahasiswa yang login
        // $id_kota = DB::table('mahasiswa')
        //     ->where('nim', $sessionUser['id_kota']) // Mengambil NIM dari session user
        //     ->value('id_kota');

        // Insert data ke tabel pengajuan_pembimbing
        $pengajuanPembimbingId = DB::table('pengajuan_pembimbing')->insertGetId([
            'id_kota' => $sessionUser['id_kota'],
            'status_pengajuan' => 'pending',  // Set statusnya menjadi pending
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Insert data ke tabel kota (judul_ta) dan bidang (id_bidang)
        DB::table('kota')->where('id_kota', $sessionUser['id_kota'])->update([
            'judul_ta' => $topik,
            'id_bidang' => $bidang[0]// Menyimpan bidang pertama yang dipilih
        ]);

        // Insert bidang tugas akhir yang dipilih (menyimpan id_bidang)
        foreach ($bidang as $bidangId) {
            DB::table('kota')
                ->where('id_kota', $sessionUser['id_kota'])
                ->update([
                    'id_bidang' => $bidangId,
                ]);
        }

        // Insert data ke tabel prioritas_pembimbing
        foreach ($prioritas as $urutan => $nip) {
            DB::table('prioritas_pembimbing')->insert([
                'id_pengajuan' => $pengajuanPembimbingId,
                'nip' => $nip,
                'urutan_prioritas' => $urutan + 1,  // Menyimpan urutan prioritas
            ]);
        }

        // Redirect setelah data disimpan
        session()->flash('success', 'Bidang baru berhasil ditambahkan');

        return redirect()->route('pratinjau-formulir.index');
    }
}