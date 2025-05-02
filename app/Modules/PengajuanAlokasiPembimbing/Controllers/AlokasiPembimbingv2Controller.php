<?php

namespace App\Modules\PengajuanAlokasiPembimbing\Controllers;

use App\Modules\Controller;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\Kota;
use App\Models\User;
use App\Models\PengajuanPembimbing;
use App\Models\PrioritasPembimbing;
use App\Models\AlokasiDosen;
use App\Models\Dosen;
use App\Models\Bidang;
use App\Models\Mahasiswa;
use App\Models\KetertarikanBidang;
use App\Models\KuotaMembimbing;
use Illuminate\Support\Facades\DB;
use App\Models\PreferensiKota;

class AlokasiPembimbingv2Controller extends Controller
{
    public function index(): View
    {
        $data_pengajuan = PengajuanPembimbing::join('kota', 'pengajuan_pembimbing.id_kota', '=', 'kota.id_kota')
            ->join('bidang', 'kota.id_bidang', '=', 'bidang.id_bidang')
            ->select('pengajuan_pembimbing.*', 'kota.nama_kota', 'kota.id_bidang', 'kota.judul_ta', 'bidang.bidang')
            ->get();

        foreach ($data_pengajuan as $key => $value) {
            $data_pengajuan[$key]['mahasiswa'] = Mahasiswa::join('user', 'mahasiswa.nim', '=', 'user.username')
                ->where('id_kota', $value->id_kota)->get();

            $data_pengajuan[$key]['usulan_dosen'] = PrioritasPembimbing::join('dosen', 'prioritas_pembimbing.nip', '=', 'dosen.nip')
                ->where('id_pengajuan', $value->id_pengajuan_pembimbing)
                ->select('dosen.id_dosen', 'prioritas_pembimbing.urutan_prioritas')
                ->orderBy('urutan_prioritas')
                ->get();
                
            $data_pengajuan[$key]['preferensi_dosen'] = PreferensiKota::join('dosen', 'preferensi_kota.nip', '=', 'dosen.nip')
                ->where('preferensi_kota.id_kota', $value->id_kota)
                ->select('dosen.id_dosen', 'dosen.nip')->limit(2)->get();
        }

        $dosenList = Dosen::join('user', 'dosen.nip', '=', 'user.username')
            ->select('dosen.id_dosen', 'dosen.nip', 'user.nama')
            ->get();

        dd($data_pengajuan);

        return view('PengajuanAlokasiPembimbing.views.AlokasiDosenPembimbing.NewVersion', [
            'list_pengajuan' => $data_pengajuan,
            'dosenList' => $dosenList
        ]);
    }
    
    public function getDetailDosen(): JsonResponse
    {
        $dosen = DB::table('dosen')
            ->join('user', 'dosen.nip', '=', 'user.username')
            ->where('dosen.bersedia_membimbing', 'bersedia')
            ->select('user.nama', 'dosen.nip')
            ->orderBy('user.nama', 'asc')
            ->get();
        
        $kuota = DB::table('kuota_membimbing')
            ->select('nip', 'id_prodi', DB::raw('SUM(jumlah) as total'))
            ->groupBy('nip', 'id_prodi')
            ->get()
            ->groupBy('nip');
        
        $prodi = DB::table('prodi')->get()->keyBy('id_prodi');

        // Gabungkan data dosen dengan kuota berdasarkan prodi (D3/D4)
        $result = $dosen->map(function ($item) use ($kuota, $prodi) {
            $nip = $item->nip;
            $kuotaDosen = $kuota[$nip] ?? collect();
    
            $d3 = $kuotaDosen->firstWhere('id_prodi', 1)?->total ?? 0;
            $d4 = $kuotaDosen->firstWhere('id_prodi', 2)?->total ?? 0;
    
            return [
                'nama' => $item->nama,
                'nip' => $item->nip,
                'kuota' => [
                    'D3' => [
                        'terpakai' => $d3,
                        'maksimal_mhs' => $prodi[1]->maksimal_mahasiswa_bimbingan ?? 0,
                        'maksimal_kota' => $prodi[1]->maksimal_anggota_kota ?? 0
                    ],
                    'D4' => [
                        'terpakai' => $d4,
                        'maksimal_mhs' => $prodi[2]->maksimal_mahasiswa_bimbingan ?? 0,
                        'maksimal_kota' => $prodi[2]->maksimal_anggota_kota ?? 0
                    ],
                ]
            ];
        });
        
        // dd($result);
        return response()->json($result->map(function ($item) {
            return [
                'dosenName' => $item['nama'],
                'nip' => $item['nip'],
                'mhs' => [
                    'D3' => $item['kuota']['D3']['terpakai'],
                    'D4' => $item['kuota']['D4']['terpakai'],
                ],
                'kuota' => [
                    'D3' => $item['kuota']['D3']['maksimal_mhs'],
                    'D4' => $item['kuota']['D4']['maksimal_mhs'],
                ],
                'kelompok' => [
                    'D3' => $item['kuota']['D3']['maksimal_kota'],
                    'D4' => $item['kuota']['D4']['maksimal_kota'],
                ]
            ];
        }));
    }
}