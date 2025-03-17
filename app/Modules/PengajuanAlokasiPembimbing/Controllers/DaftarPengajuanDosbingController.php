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
            ->select('username as nim', 'nama')
            ->get();

        $kelompokData = [];
        $totalMahasiswa = count($mahasiswaList);
        $mahasiswaIndex = 0;

        for ($i = 0; $i < 9; $i++) {
            $anggota = [];

            for ($j = 0; $j < 3; $j++) {
                if ($mahasiswaIndex < $totalMahasiswa) {
                    $anggota[] = [
                        'nama' => $mahasiswaList[$mahasiswaIndex]->nama,
                        'nim' => $mahasiswaList[$mahasiswaIndex]->nim,
                    ];
                    $mahasiswaIndex++;
                } else {
                    $anggota[] = [
                        'nama' => 'Mahasiswa Default',
                        'nim' => 'NIM0000',
                    ];
                }
            }

            $kelompokData[] = [
                'id' => $i + 1,
                'kode' => $kotaList[$i % max(1, count($kotaList))] ?? 'Default Kota',
                'bidang' => $bidangList[$i % max(1, count($bidangList))] ?? 'Default Bidang',
                'judul' => $judulList[$i % max(1, count($judulList))] ?? 'Judul Default',
                'tanggal' => $tanggalPengajuanList[$i % max(1, count($tanggalPengajuanList))] ?? date('Y-m-d'),
                'anggota' => $anggota,
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