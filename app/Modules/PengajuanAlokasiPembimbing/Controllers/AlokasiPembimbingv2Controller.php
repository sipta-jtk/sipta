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
                ->join('prodi', 'mahasiswa.id_prodi', '=', 'prodi.id_prodi')
                ->where('id_kota', $value->id_kota)->get();

            $data_pengajuan[$key]['usulan_dosen'] = PrioritasPembimbing::join('dosen', 'prioritas_pembimbing.nip', '=', 'dosen.nip')
                ->where('id_pengajuan', $value->id_pengajuan_pembimbing)
                ->select('dosen.id_dosen', 'prioritas_pembimbing.urutan_prioritas')
                ->orderBy('urutan_prioritas')
                ->get();

            if ($data_pengajuan[$key]['mahasiswa']->isNotEmpty()) {
                $firstMahasiswa = $data_pengajuan[$key]['mahasiswa']->first();
                $data_pengajuan[$key]['prodi'] = $firstMahasiswa->nama_prodi;
            } else {
                $data_pengajuan[$key]['prodi'] = null;
            }
        }

        $dosenList = Dosen::join('user', 'dosen.nip', '=', 'user.username')
            ->select('dosen.id_dosen', 'dosen.nip', 'user.nama')
            ->get();

        $prodiList = DB::table('prodi')->select('id_prodi', 'nama_prodi')->get();


        return view('PengajuanAlokasiPembimbing.views.AlokasiDosenPembimbing.NewVersion', [
            'list_pengajuan' => $data_pengajuan,
            'dosenList' => $dosenList,
            'list_prodi' => $prodiList
        ]);
    }

    public function getDetailDosen(): JsonResponse
    {
        $allProdi = DB::table('prodi')->pluck('nama_prodi', 'id_prodi');

        $dosen = DB::table('dosen')
            ->join('user', 'dosen.nip', '=', 'user.username')
            ->where('dosen.bersedia_membimbing', 'bersedia')
            ->select('user.nama', 'dosen.nip', 'dosen.id_dosen')
            ->orderBy('user.nama', 'asc')
            ->get();

        $kuota = DB::table('kuota_membimbing')
            ->select('nip', 'id_prodi', 'jumlah')
            ->get()
            ->groupBy('nip');

        $terpakai = DB::table('alokasi_dosen')
            ->join('mahasiswa', 'alokasi_dosen.nip', '=', 'mahasiswa.nim')
            ->select('alokasi_dosen.nip', 'mahasiswa.id_prodi', DB::raw('COUNT(*) as total'))
            ->groupBy('alokasi_dosen.nip', 'mahasiswa.id_prodi')
            ->get()
            ->groupBy('nip');

        $result = $dosen->map(function ($item) use ($kuota, $terpakai, $allProdi) {
            $nip = $item->nip;

            $kuotaDosen = $kuota[$nip] ?? collect();
            $terpakaiDosen = $terpakai[$nip] ?? collect();

            $mhs = [];
            $kuotas = [];
            $kelompok = [];

            foreach ($allProdi as $id => $namaProdi) {
                $kode = substr($namaProdi, 0, 2); // Ambil D3, D4, S1, dst
                $mhs[$kode] = $terpakaiDosen->firstWhere('id_prodi', $id)?->total ?? 0;
                $kuotas[$kode] = $kuotaDosen->firstWhere('id_prodi', $id)?->jumlah ?? 0;
                $kelompok[$kode] = 0;
            }

            return [
                'dosenName' => $item->nama,
                'nip' => $item->nip,
                'id' => $item->id_dosen,
                'mhs' => $mhs,
                'kuota' => $kuotas,
                'kelompok' => $kelompok
            ];
        });
        return response()->json($result);
    }
}