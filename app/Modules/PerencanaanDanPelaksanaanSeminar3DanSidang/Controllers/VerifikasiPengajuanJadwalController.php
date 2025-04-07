<?php

namespace App\Modules\PerencanaanDanPelaksanaanSeminar3DanSidang\Controllers;

use App\Modules\Controller;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;


class VerifikasiPengajuanJadwalController extends Controller
{
    public function getListAsKoordinatorTA(Request $request, String $tipe): View
    {
        $nip = auth()->user()->username;
        
        $role = DB::table('dosen')->where('nip', $nip)->value('role_dosen');

        $agenda = ($tipe == 'seminar-3') ? 'seminar_3' : 'sidang';
        
        $dataPengajuan = DB::table('pengajuan_jadwal_kota')
        ->join('penjadwalan', 'pengajuan_jadwal_kota.id_penjadwalan', '=', 'penjadwalan.id_penjadwalan')
        ->join('kota', 'pengajuan_jadwal_kota.id_kota', '=', 'kota.id_kota')
        ->select(
            'pengajuan_jadwal_kota.id_penjadwalan',
            'kota.id_kota',
            'kota.judul_ta',
            'penjadwalan.agenda',
            'penjadwalan.tanggal',
            'penjadwalan.id_ruangan',
            'penjadwalan.sesi',
            'pengajuan_jadwal_kota.status_koordinator_ta'
        )
        ->where('penjadwalan.agenda', $agenda)
        ->where('pengajuan_jadwal_kota.status_dosen_penguji_1', 1)
        ->where('pengajuan_jadwal_kota.status_dosen_penguji_2', 1)
        ->whereNull('pengajuan_jadwal_kota.status_koordinator_ta')
        ->get();
        

        return view('PerencanaanDanPelaksanaanSeminar3DanSidang.views.kelolaPengajuanJadwal.ListPengajuanJadwalKoordinator', compact('dataPengajuan', 'tipe'));
    }

    public function getListAsDosenPembimbing(Request $request, String $tipe): View
    {
        $nip = auth()->user()->username;
        
        $role = DB::table('alokasi_dosen')->where('nip', $nip)->value('tipe_alokasi');

        $agenda = ($tipe == 'seminar-3') ? 'seminar_3' : 'sidang';  

        $dataPengajuan = DB::table('penjadwalan')
        ->where('penjadwalan.agenda', $agenda)
        ->join('pengajuan_jadwal_kota', 'pengajuan_jadwal_kota.id_penjadwalan', '=', 'penjadwalan.id_penjadwalan')
        ->join('kota', 'kota.id_kota', '=', 'pengajuan_jadwal_kota.id_kota')
        ->join('pengajuan_pembimbing', 'pengajuan_pembimbing.id_kota', '=', 'kota.id_kota')
        ->join('alokasi_dosen', 'pengajuan_pembimbing.id_pengajuan_pembimbing', '=', 'alokasi_dosen.id_pengajuan_pembimbing')
        ->where('alokasi_dosen.status_alokasi', 'fix')
        ->where('alokasi_dosen.tipe_alokasi', 'pembimbing')
        ->where('alokasi_dosen.nip', $nip)
        ->where(function($query) {
            $query->whereRaw("
                (alokasi_dosen.urutan_prioritas_terpilih = 1 AND pengajuan_jadwal_kota.status_dosen_pembimbing_1 IS NULL) 
                OR 
                (alokasi_dosen.urutan_prioritas_terpilih = 2 AND pengajuan_jadwal_kota.status_dosen_pembimbing_2 IS NULL)
            ");
        })
        ->select(
            'pengajuan_jadwal_kota.id_penjadwalan',
            'kota.id_kota',
            'kota.judul_ta',
            'penjadwalan.agenda',
            'penjadwalan.tanggal',
            'penjadwalan.id_ruangan',
            'penjadwalan.sesi',
            'pengajuan_jadwal_kota.status_dosen_pembimbing_1',
            'pengajuan_jadwal_kota.status_dosen_pembimbing_2',
            'alokasi_dosen.urutan_prioritas_terpilih'
        )
        ->get();
    
        return view('PerencanaanDanPelaksanaanSeminar3DanSidang.views.kelolaPengajuanJadwal.ListPengajuanJadwalPembimbing', compact('dataPengajuan', 'tipe'));
    }
    
    public function getListAsDosenPenguji(Request $request, String $tipe): View
    {
        $nip = auth()->user()->username;
        
        $role = DB::table('alokasi_dosen')->where('nip', $nip)->value('tipe_alokasi');

        $agenda = ($tipe == 'seminar-3') ? 'seminar_3' : 'sidang';

        $dataPengajuan = DB::table('penjadwalan')
        ->join('pengajuan_jadwal_kota', 'pengajuan_jadwal_kota.id_penjadwalan', '=', 'penjadwalan.id_penjadwalan')
        ->leftJoin('kota', 'kota.id_kota', '=', 'pengajuan_jadwal_kota.id_kota')
        ->leftJoin('pengajuan_pembimbing', 'pengajuan_pembimbing.id_kota', '=', 'kota.id_kota')
        ->leftJoin('alokasi_dosen', 'pengajuan_pembimbing.id_pengajuan_pembimbing', '=', 'alokasi_dosen.id_pengajuan_pembimbing')
        ->where('alokasi_dosen.status_alokasi', 'fix')
        ->where('alokasi_dosen.tipe_alokasi', 'penguji')
        ->where('alokasi_dosen.nip', $nip)
        ->where('pengajuan_jadwal_kota.status_dosen_pembimbing_1', 1)
        ->where('pengajuan_jadwal_kota.status_dosen_pembimbing_2', 1)
        ->where(function($query) {
            $query->whereRaw("
            (alokasi_dosen.urutan_prioritas_terpilih = 1 AND pengajuan_jadwal_kota.status_dosen_penguji_1 IS NULL) 
            OR 
            (alokasi_dosen.urutan_prioritas_terpilih = 2 AND pengajuan_jadwal_kota.status_dosen_penguji_2 IS NULL)
            ");
        })
        ->select(
            'pengajuan_jadwal_kota.id_penjadwalan',
            'kota.id_kota',
            'kota.judul_ta',
            'penjadwalan.agenda',
            'penjadwalan.tanggal',
            'penjadwalan.id_ruangan',
            'penjadwalan.sesi',
            'pengajuan_jadwal_kota.status_dosen_penguji_1',
            'pengajuan_jadwal_kota.status_dosen_penguji_2',
            'alokasi_dosen.urutan_prioritas_terpilih'
        )
        ->where('penjadwalan.agenda', $agenda)
        ->get();
    
        return view('PerencanaanDanPelaksanaanSeminar3DanSidang.views.kelolaPengajuanJadwal.ListPengajuanJadwalPenguji', compact('dataPengajuan', 'tipe'));
    }


    public function verifikasiAsKoordinatorTA(Request $request, string $tipe, int $idPenjadwalan)
    {
        // Ambil status verifikasi dari request
        $status = $request->input('status_verifikasi') === 'Ditolak' ? false : true;
        
        if ($status == true) {
            // Ambil informasi ruangan dari tabel penjadwalan
            $roomInformation = DB::table('penjadwalan')
                ->where('id_penjadwalan', $idPenjadwalan)
                ->select('id_ruangan', 'tanggal', 'agenda', 'sesi','tanggal','start', 'end','id_kota')
                ->first();
        
            if ($roomInformation) {       
                // Mengambil token dari auth
                $token = auth()->user()->createToken(auth()->user()->username . '_token')->plainTextToken;

                $data = [
                    'type' => 'add',
                    'agenda' => $roomInformation->agenda,
                    'start' => $roomInformation->start,
                    'end' => $roomInformation->end  ,
                    'id_ruangan' => $roomInformation->id_ruangan,
                    'id_kota' => $roomInformation->id_kota,
                    'nip' => auth()->user()->username,
                ];
            
                $response = Http::withHeaders([
                    'X-Requested-With' => 'XMLHttpRequest',
                    'content-type' => 'application/json',
                    'Accept' => 'application/json',
                ])
                ->withToken($token)
                ->post('http://host.docker.internal:8005/penjadwalan-ruangan/api/v1/schedule/action', $data);

                if( $response->successful()) {
                    // Jika berhasil, lakukan update status verifikasi
                    DB::table('pengajuan_jadwal_kota')
                        ->where('id_penjadwalan', $idPenjadwalan)
                        ->update(['status_koordinator_ta' => $status]);
                } else {
                    // Jika gagal, kirimkan pop up error response json dari API
                    $responseData = $response->json();
                    $errorMessage = $responseData['message'] ?? 'Terjadi kesalahan saat memproses permintaan.';
                    return redirect()->route('kelola.jadwal.list', ['tipe' => $tipe])
                        ->with('error', "Gagal memverifikasi: " . $errorMessage);
                }
            }
        } else {
            // Jika status ditolak, update status verifikasi tanpa menghubungi API
            DB::table('pengajuan_jadwal_kota')
                ->where('id_penjadwalan', $idPenjadwalan)
                ->update(['status_koordinator_ta' => $status]); 
        }
        
        // Redirect kembali ke halaman dengan pesan
        return redirect()->route('kelola.jadwal.list', ['tipe' => $tipe])
                        ->with('success', "Status verifikasi: " . ($status ? 'Disetujui' : 'Ditolak'));
    }

    public function verifikasiAsPembimbing(Request $request, string $tipe, int $idPenjadwalan)
    {
        // Ambil status verifikasi dari request
        $status = $request->input('status_verifikasi') === 'Ditolak' ? false : true;

        $nip = auth()->user()->username;
        $prioritas = DB::table('penjadwalan')
        ->join('pengajuan_jadwal_kota', 'pengajuan_jadwal_kota.id_penjadwalan', '=', 'penjadwalan.id_penjadwalan')
        ->join('kota', 'kota.id_kota', '=', 'pengajuan_jadwal_kota.id_kota')
        ->join('pengajuan_pembimbing', 'pengajuan_pembimbing.id_kota', '=', 'kota.id_kota')
        ->join('alokasi_dosen', 'pengajuan_pembimbing.id_pengajuan_pembimbing', '=', 'alokasi_dosen.id_pengajuan_pembimbing')
        ->where('alokasi_dosen.nip', $nip)
        ->where('penjadwalan.id_penjadwalan', $idPenjadwalan) // Menambahkan filter id_penjadwalan dengan benar
        ->where('alokasi_dosen.status_alokasi', 'fix')
        ->value('alokasi_dosen.urutan_prioritas_terpilih');
    

        if ($prioritas == 1) {
            DB::table('pengajuan_jadwal_kota')
                ->where('id_penjadwalan', $idPenjadwalan)
                ->update(['status_dosen_pembimbing_1' => $status]);
        } elseif ($prioritas == 2) {
            DB::table('pengajuan_jadwal_kota')
                ->where('id_penjadwalan', $idPenjadwalan)
                ->update(['status_dosen_pembimbing_2' => $status]);
        }
        

        // Redirect kembali ke halaman dengan pesan
        return redirect()->route('kelola-pembimbing.jadwal.list', ['tipe' => $tipe])
                        ->with('success', "Status verifikasi: " . ($status ? 'Disetujui' : 'Ditolak'));
    }

    public function verifikasiAsPenguji(Request $request, string $tipe, int $idPenjadwalan)
    {
        // Ambil status verifikasi dari request
        $status = $request->input('status_verifikasi') === 'Ditolak' ? false : true;

        $nip = auth()->user()->username;
        $prioritas = DB::table('penjadwalan')
        ->join('pengajuan_jadwal_kota', 'pengajuan_jadwal_kota.id_penjadwalan', '=', 'penjadwalan.id_penjadwalan')
        ->join('kota', 'kota.id_kota', '=', 'pengajuan_jadwal_kota.id_kota')
        ->join('pengajuan_pembimbing', 'pengajuan_pembimbing.id_kota', '=', 'kota.id_kota')
        ->join('alokasi_dosen', 'pengajuan_pembimbing.id_pengajuan_pembimbing', '=', 'alokasi_dosen.id_pengajuan_pembimbing')
        ->where('alokasi_dosen.nip', $nip)
        ->where('penjadwalan.id_penjadwalan', $idPenjadwalan) // Menambahkan filter id_penjadwalan dengan benar
        ->where('alokasi_dosen.status_alokasi', 'fix')
        ->value('alokasi_dosen.urutan_prioritas_terpilih');

        if ($prioritas == 1) {
            DB::table('pengajuan_jadwal_kota')
                ->where('id_penjadwalan', $idPenjadwalan)
                ->update(['status_dosen_pengajuan_1' => $status]);
        } elseif ($prioritas == 2) {
            DB::table('pengajuan_jadwal_kota')
                ->where('id_penjadwalan', $idPenjadwalan)
                ->update(['status_dosen_pengajuan_2' => $status]);
        }
        

        // Redirect kembali ke halaman dengan pesan
        return redirect()->route('kelola-penguji.jadwal.list', ['tipe' => $tipe])
                        ->with('success', "Status verifikasi: " . ($status ? 'Disetujui' : 'Ditolak'));
    }

}

