<?php

namespace App\Modules\PengajuanAlokasiPembimbing\Controllers;

use App\Models\Bidang;
use App\Models\Kota;
use App\Models\Dosen;
use App\Models\PengajuanPembimbing;
use App\Models\PreferensiKota;
use App\Models\KuotaMembimbing;
use App\Models\User;
use Illuminate\Http\Request;
use App\Modules\Controller;
use Illuminate\Support\Facades\DB;

class DaftarPengajuanDosbingController extends Controller
{
    // private $USER_ID = '197312271999031003';

    public function view_daftarPengajuanDosbing()
    {
        // dd(auth()->user()->username);
        // dd(auth()->user()->dosen->nip);
        $kotaList = Kota::pluck('nama_kota')->toArray();
        $bidangList = Bidang::pluck('bidang')->toArray();
        $judulList = Kota::pluck('judul_ta')->toArray();
        $tanggalPengajuanList = PengajuanPembimbing::pluck('created_at')->toArray();
        

        $mahasiswaList = User::where('role_user', 'mahasiswa')
            ->join('mahasiswa', 'mahasiswa.nim', '=', 'user.username')
            ->join('kota', 'kota.id_kota', '=', 'mahasiswa.id_kota')
            ->select('user.username as nim', 'user.nama', 'kota.nama_kota')
            ->orderBy('kota.nama_kota')
            ->get();


        $kelompokData = [];
        $mahasiswaGrouped = $mahasiswaList->groupBy('nama_kota');

        foreach ($kotaList as $index => $namaKota) {
            $anggota = $mahasiswaGrouped[$namaKota] ?? collect();
            $anggotaFormatted = [];
            
            foreach ($anggota->take(3) as $mhs) {
                $anggotaFormatted[] = [
                    'nama' => $mhs->nama,
                    'nim' => $mhs->nim,
                ];
            }
            
            while (count($anggotaFormatted) < 3) {
                $anggotaFormatted[] = [
                    'nama' => 'Mahasiswa Default',
                    'nim' => 'NIM0000',
                ];
            }

            $kelompokData[] = [
                'id' => $index + 1,
                'kode' => $namaKota,
                'bidang' => $bidangList[$index % max(1, count($bidangList))] ?? 'Default Bidang',
                'judul' => $judulList[$index % max(1, count($judulList))] ?? 'Judul Default',
                'tanggal' => $tanggalPengajuanList[$index % max(1, count($tanggalPengajuanList))] ?? date('Y-m-d'),
                'anggota' => $anggotaFormatted,
            ];
        }

        return view('PengajuanAlokasiPembimbing.views.DaftarPengajuanDosbing.topik', compact('kelompokData'));
    }

    public function handlePengajuan(Request $request, $id, $action)
    {
        // return response()->json(auth()->user());
        $dosen = Dosen::where('nip', auth()->user()->dosen->nip)->first();
    
        if (!$dosen) {
            return response()->json(['message' => 'Dosen tidak ditemukan.'], 403);
        }
    
        $kota = Kota::where('id_kota', $id)->first();
    
        if (!$kota) {
            return response()->json(['message' => 'Kota tidak ditemukan.'], 404);
        }
    
        if ($action === 'accept') {
            $existingPreferensi = PreferensiKota::where([
                'nip' => $dosen->nip,
                'id_kota' => $kota->id_kota
            ])->exists();
    
            if ($existingPreferensi) {
                return response()->json([
                    'status' => 'exists',
                    'message' => 'Anda sudah memilih kota ini sebelumnya. Silakan pilih kota lain.'
                ], 200);
            }

            $kuotaMembimbing = KuotaMembimbing::where('nip', $dosen->nip)->first();
            if (!$kuotaMembimbing) {
                return response()->json(['message' => 'Kuota membimbing tidak ditemukan.'], 404);
            }
    
            $jumlahPengajuanDiterima = PreferensiKota::where('nip', $dosen->nip)->count();
    
            if ($jumlahPengajuanDiterima >= $kuotaMembimbing->jumlah) {
                return response()->json([
                    'message' => 'Kuota membimbing sudah penuh. Anda tidak dapat menerima pengajuan baru.'
                ], 422);
            }
            
    
            DB::beginTransaction();
            try {
                PreferensiKota::updateOrCreate(
                    ['nip' => $dosen->nip, 'id_kota' => $kota->id_kota],
                    ['nip' => $dosen->nip, 'id_kota' => $kota->id_kota]
                );
    
                DB::commit();
                return response()->json(['status' => 'success', 'message' => 'Pengajuan diterima dan data telah disimpan.'], 200);
            } catch (\Exception $e) {
                DB::rollBack();
                return response()->json([
                    'message' => 'Terjadi kesalahan saat menyimpan data.',
                    'error' => $e->getMessage()
                ], 500);
            }
        } elseif ($action === 'reject') {
            DB::beginTransaction();
            try {
                PreferensiKota::where([
                    'nip' => $dosen->nip,
                    'id_kota' => $kota->id_kota,
                ])->delete();
    
                DB::commit();
                return response()->json(['message' => 'Pengajuan ditolak dan data telah dihapus.'], 200);
            } catch (\Exception $e) {
                DB::rollBack();
                return response()->json([
                    'message' => 'Terjadi kesalahan saat menghapus data.',
                    'error' => $e->getMessage()
                ], 500);
            }
        }
    
        return response()->json(['message' => 'Aksi tidak valid.'], 400);
    }
}