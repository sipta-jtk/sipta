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
use Validator;
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


        foreach ($listDosen as $dosen) {
            $dosen->history = $this->getDosenHistory($dosen->nip);
        }

        // dd($listDosen);

        return view('PengajuanAlokasiPembimbing.views.PengajuanPembimbing.PrioritasDosenPembimbing', compact('listDosen'));
    }

    public function getDosenHistory($nip)
    {
        $history = DB::table('ketertarikan_bidang')
            ->join('bidang', 'ketertarikan_bidang.id_bidang', '=', 'bidang.id_bidang')
            ->where('ketertarikan_bidang.nip', $nip)
            ->select('bidang.bidang')
            ->orderBy('bidang.bidang', 'asc')
            ->get();
        
            
            // return response()->json([
            //     'bidangList' => $history
            // ]);
        return $history;
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
        $validator = Validator::make($request->all(), [
            'topik' => 'required|string|max:255',
            'bidang' => 'required|min:1',
            'bidang.*' => 'string',
            'prioritas_dosen' => 'required|array|min:1|max:5',
            'prioritas_dosen.*' => 'string', // Validasi NIP dosen
        ],
        [
            'topik.required' => 'Topik tugas akhir harus diisi',
            'bidang.required' => 'Bidang tugas akhir harus dipilih',
            'prioritas_dosen.required' => 'Prioritas dosen pembimbing harus diisi minimal 1 dan maksimal 5',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }


        // Ambil data yang dikirim dari frontend
        $topik = $request->input('topik');
        $bidang = $request->input('bidang');
        $prioritas = json_decode($request->input('prioritas'), true);
        
        
        $sessionUser = [
            'nama' => 'Welsya',
            'nim' => '221524032',
            'kelas' => 'D4A',
            'id_kota' => 2
        ];

        // Mencari ID berdasarkan nama bidang
        $bidangId = DB::table('bidang')->where('bidang', $bidang)->value('id_bidang');

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
            'id_bidang' => $bidangId
        ]);

        // Insert bidang tugas akhir yang dipilih (menyimpan id_bidang)
        DB::table('kota')
            ->where('id_kota', $sessionUser['id_kota'])
            ->update([
                'id_bidang' => $bidangId,
            ]);

        // Insert data ke tabel prioritas_pembimbing
        foreach ($prioritas as $dosen) {
            // Ambil NIP berdasarkan nama dosen
            $nipDosen = DB::table('dosen')
                ->join('user', 'dosen.nip', '=', 'user.username')
                ->where('user.nama', $dosen['name'])
                ->value('dosen.nip');
        
            // Pastikan NIP ditemukan sebelum insert
            if ($nipDosen) {
                DB::table('prioritas_pembimbing')->insert([
                    'id_pengajuan' => $pengajuanPembimbingId,
                    'nip' => $nipDosen,
                    'urutan_prioritas' => $dosen['priority'], 
                ]);
            } else {
                return back()->withErrors(['prioritas' => "NIP tidak ditemukan untuk dosen: " . $dosen['name']]);
            }
        }
        
        // Redirect setelah data disimpan
        session()->flash('success', 'Pengajuan dosen pembimbing sudah direkap. Silahkan menunggu status pengajuan diterima.');

        return redirect()->route('pengajuanalokasipembimbing.pengajuan-pembimbing.pratinjau-formulir.index');
    }
}