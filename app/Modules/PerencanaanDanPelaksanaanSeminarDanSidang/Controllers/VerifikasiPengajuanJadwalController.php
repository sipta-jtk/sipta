<?php

namespace App\Modules\PerencanaanDanPelaksanaanSeminarDanSidang\Controllers;

use App\Modules\Controller;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
        

        return view('PerencanaanDanPelaksanaanSeminarDanSidang.views.ListPengajuanJadwalKoordinator', compact('dataPengajuan', 'tipe'));
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
    
        return view('PerencanaanDanPelaksanaanSeminarDanSidang.views.ListPengajuanJadwalPembimbing', compact('dataPengajuan', 'tipe'));
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
    
        return view('PerencanaanDanPelaksanaanSeminarDanSidang.views.ListPengajuanJadwalPenguji', compact('dataPengajuan', 'tipe'));
    }


    public function verifikasiAsKoordinatorTA(Request $request, string $tipe, int $idPenjadwalan)
    {
        // Ambil status verifikasi dari request
        $status = $request->input('status_verifikasi') === 'Ditolak' ? false : true;

        // fungsi to api farhan
        DB::table('pengajuan_jadwal_kota')
        ->where('id_penjadwalan', $idPenjadwalan)
        ->update(['status_koordinator_ta' => $status]);



        if ($status == true) {
            // Ambil informasi ruangan dari tabel penjadwalan
            $roomInformation = DB::table('penjadwalan')
                ->where('id_penjadwalan', $idPenjadwalan)
                ->select('id_ruangan', 'tanggal', 'agenda', 'sesi','tanggal','start', 'end')
                ->first();
        
            if ($roomInformation) {       
                // Format tanggal dan waktu
                $startDateTime = $roomInformation->tanggal . " " . $rooomInformation->start;
                $endDateTime = $roomInformation->tanggal . " " . $roomInformation->end;
        
                // Data yang akan dikirim ke API
                $data = [
                    "type"  => "add",
                    "agenda" => $roomInformation->agenda,
                    "start" => $startDateTime,
                    "end"   => $endDateTime,
                    "id_ruangan" => $roomInformation->id_ruangan
                ];
        
                // Konversi data ke format JSON
                $jsonData = json_encode($data);
        
                // Inisialisasi cURL
                $ch = curl_init('https://your-api.com/api/v1/schedule/action?token=YOUR_SIPTA_TOKEN');
        
                // Set opsi cURL
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, [
                    'Content-Type: application/json'
                ]);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
        
                // Eksekusi cURL dan dapatkan respons
                $response = curl_exec($ch);
                $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        
                // Tutup koneksi cURL
                curl_close($ch);
        
                // Log response dari API
                if ($httpCode == 200) {
                    Log::info('API Response: ' . $response);
                } else {
                    Log::error('API Request Failed. Response: ' . $response);
                }
            } else {
                Log::error('Penjadwalan tidak ditemukan untuk id_penjadwalan: ' . $idPenjadwalan);
            }
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

