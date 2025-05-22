<?php

namespace App\Modules\PengajuanAlokasiPembimbing\Controllers;

use App\Models\Bidang;
use App\Models\Kota;
use App\Models\Dosen;
use App\Models\PengajuanPembimbing;
use App\Models\PreferensiKota;
use App\Models\User;
use App\Models\Mahasiswa;
use App\Models\Prodi;
use Illuminate\Http\Request;
use App\Modules\Controller;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DaftarPengajuanDosbingController extends Controller
{
    // private $USER_ID = '197312271999031003';

    public function view_daftarPengajuanDosbing()
    {

        // dd(auth()->user()->username);
        // dd(auth()->user()->dosen->nip);
        $kotaList = Kota::pluck('nama_kota')->toArray();
        $namaKota = Kota::pluck('nama_kota')->first();
        $bidangList = Bidang::pluck('bidang')->toArray();
        $judulList = Kota::pluck('judul_ta')->toArray();
        $tanggalPengajuanList = PengajuanPembimbing::pluck('created_at')->toArray();

        $allKotaData = Kota::orderBy('nama_kota')->get()->keyBy('id_kota');
        $allBidangData = Bidang::get()->keyBy('id_bidang');

        Carbon::setLocale('id');
        $formattedTanggalList = array_map(function($tanggal) {
            return Carbon::parse($tanggal)->translatedFormat('H:i d F Y');
        }, $tanggalPengajuanList);
        

        $mahasiswaList = User::where('role_user', 'mahasiswa')
            ->join('mahasiswa', 'mahasiswa.nim', '=', 'user.username')
            ->join('kota', 'kota.id_kota', '=', 'mahasiswa.id_kota')
            ->select(
                'user.username as nim',
                'user.nama',
                'kota.nama_kota',
                'mahasiswa.id_prodi',
                'mahasiswa.id_kota'
            )
            ->orderBy('kota.nama_kota')
            ->get();

        $kotaProdi = Mahasiswa::select('id_kota', 'id_prodi')
            ->groupBy('id_kota', 'id_prodi')
            ->get()
            ->pluck('id_prodi', 'id_kota');

        $dosenNip = auth()->user()->dosen->nip ?? null;
        $preferredKotaIdsByDosen = [];
        if ($dosenNip) {
            $preferredKotaIdsByDosen = PreferensiKota::where('nip', $dosenNip)
                                            ->pluck('status', 'id_kota')
                                            ->all(); // Menghasilkan array [id_kota1, id_kota2, ...]
        }

        $kelompokData = ['table' => [], 'prodiList' => []];
        // $mahasiswaGrouped = $mahasiswaList->groupBy('nama_kota');
        $mahasiswaGroupedByIdKota = $mahasiswaList->groupBy('id_kota');


        $loopIteration = 1;
        foreach ($mahasiswaGroupedByIdKota as $idKota => $anggotaGrup) {
            $kotaInfo = $allKotaData->get($idKota);
            if (!$kotaInfo) continue;
            $anggotaFormatted = $anggotaGrup->map(function ($mhs) {
                return [
                    'nama' => $mhs->nama,
                    'nim' => $mhs->nim,
                ];
            })->values()->toArray();
    
            $statusPeminatanDb = $preferredKotaIdsByDosen[$idKota] ?? null;
            $statusPeminatanUntukTampilan = 'none';

            if ($statusPeminatanDb === 1) {
                $statusPeminatanUntukTampilan = 'accepted';
            } elseif ($statusPeminatanDb === 0) {
                $statusPeminatanUntukTampilan = 'rejected';
            }


            // $index = array_search($namaKota, $kotaList);
            // if ($index === false) continue;
            
            // $idKota = $kelompokData['status_peminatan_aktual']->anggota->first()->id_kota ?? null;
            // $idProdi = $anggota->first()->id_prodi;

            // $dosenNip = auth()->user()->dosen->nip ?? null;
            // $statusPeminatan = 'none'; // Default status
            // if ($dosenNip) {
            //     $preferensi = PreferensiKota::where('nip', $dosenNip)->where('id_kota', $idKota)->first();
            //     if ($preferensi) {
            //         $statusPeminatan = 'accepted';
            //     }
            // }
            
            $kelompokData['table'][] = [
                'loop_no' => $loopIteration++,
                'id_kota_real' => $idKota,
                'anggota' => $anggotaFormatted,
                'status_peminatan_aktual' => $statusPeminatanUntukTampilan,
                'id_prodi' => $anggotaGrup->first()->id_prodi ?? null,
                'bidang' => $kotaInfo->bidang->bidang ?? '-', // pastikan relasi `bidang` didefinisikan di model Kota
                'judul' => $kotaInfo->judul_ta ?? '-',         // asumsinya judul_ta adalah kolom di tabel `kota`
            ];

        }

        $kelompokData['prodiList'] = Prodi::orderBy('nama_prodi', 'asc')->get(['id_prodi', 'nama_prodi']);

        // dd($prodiList->toArray());

        // dd('SEBELUM RETURN VIEW DI CONTROLLER');
        return view('PengajuanAlokasiPembimbing.views.DaftarPengajuanDosbing.topik', compact('kelompokData'));
    }

    public function handlePengajuan(Request $request, $id_kota, $action)
    {
        // 
        $dosenNip = null;
        if (Auth::check() && Auth::user()->dosen) {
            $dosenNip = Auth::user()->dosen->nip;
        } else {
            return response()->json(['status' => 'error', 'message' => 'User tidak terautentikasi atau bukan dosen.'], 403);
        }
        
        $kota = Kota::where('id_kota', $id_kota)->first();
        
        if (!$kota) {
            return response()->json(['message' => 'Kota/Kelompok tidak ditemukan.'], 404);
        }
        
        
        if ($action === 'accept') {
    DB::beginTransaction();
    try {
        $preferensi = PreferensiKota::where('nip', $dosenNip)
            ->where('id_kota', $kota->id_kota)
            ->first();

        if (!$preferensi) {
            // Belum ada preferensi, buat baru
            PreferensiKota::create([
                'nip' => $dosenNip,
                'id_kota' => $kota->id_kota,
                'status' => 1,
            ]);
            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'Peminatan berhasil diterima.',
                'new_status_peminatan' => 'accepted'
            ], 200);
        } elseif ($preferensi->status == 0) {
            // Jika sebelumnya rejected, ubah ke accepted
            $preferensi->status = 1;
            $preferensi->save();
            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'Peminatan berhasil diterima kembali.',
                'new_status_peminatan' => 'accepted'
            ], 200);
        } else {
            // Sudah accepted, tidak lakukan apa-apa
            DB::rollBack();
            return response()->json([
                'status' => 'exists',
                'message' => 'Anda sudah memilih minat ini sebelumnya.'
            ], 200);
        }
    } catch (\Exception $e) {
        DB::rollBack();
        \Log::error('Error toggling preferensi kota: ' . $e->getMessage(), [
            'nip' => $dosenNip,
            'id_kota' => $id_kota,
            'trace' => $e->getTraceAsString()
        ]);
         return response()->json([
            'status' => 'error',
            'message' => 'Terjadi kesalahan pada server saat memproses permintaan.',
        ], 500);
    }
    } elseif ($action === 'reject') {
        DB::beginTransaction();
        try {
            $preferensi = PreferensiKota::where('nip', $dosenNip)
            ->where('id_kota', $kota->id_kota)
            ->first();
            
            $newStatusPeminatanView = '';
            $message = '';
            
            if ($preferensi && $preferensi->status == 1) {
                // Dari accepted ke rejected
                $preferensi->status = 0;
                $preferensi->save();

                PreferensiKota::where('nip', $dosenNip)->where('id_kota', $kota->id_kota)->update(['status' => 0]);

                $newStatusPeminatanView = 'rejected';
                $message = 'Peminatan berhasil ditolak.';
                // return response()->json($preferensi);
            } elseif ($preferensi && $preferensi->status == 0) {
                // Dari rejected ke accepted
                $preferensi->status = 1;
                $preferensi->save();
                $newStatusPeminatanView = 'accepted';
                $message = 'Peminatan berhasil diterima kembali.';
            } else {
                DB::rollBack();
                return response()->json([
                    'status' => 'error',
                    'message' => 'Tidak ada preferensi yang bisa diubah statusnya.',
                ], 400);
            }

            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => $message,
                'new_status_peminatan' => $newStatusPeminatanView
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error toggling preferensi kota (reject): ' . $e->getMessage(), [
                'nip' => $dosenNip,
                'id_kota' => $id_kota,
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan pada server saat memproses permintaan.',
            ], 500);
        }
    } else {
        return response()->json(['status' => 'error', 'message' => 'Aksi tidak dikenali.'], 400);
    }
}
}