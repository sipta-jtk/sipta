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

class AlokasiPembimbingController extends Controller
{
    public function index(): View
    {
        $data_pengajuan = PengajuanPembimbing::join('kota', 'pengajuan_pembimbing.id_kota', '=', 'kota.id_kota')
        ->join('bidang', 'kota.id_bidang', '=', 'bidang.id_bidang')
        ->get();

        foreach ($data_pengajuan as $key => $value) {
            $listMahasiswaOnKelompok = Mahasiswa::join('user', 'mahasiswa.nim', '=', 'user.username')->where('id_kota', $value->id_kota)->get();
            $listUsulanDosen = PrioritasPembimbing::join('dosen', 'prioritas_pembimbing.nip', '=', 'dosen.nip')->where('id_pengajuan', $value->id_pengajuan_pembimbing)->get();
            $data_pengajuan[$key]['mahasiswa'] = $listMahasiswaOnKelompok;
            $data_pengajuan[$key]['usulan_dosen'] =$listUsulanDosen;
        }


        $dosenList = Dosen::join('user', 'dosen.nip', '=', 'user.username')
        ->get();

        foreach ($dosenList as $key => $value) {
            $dosenBidang = KetertarikanBidang::join('bidang', 'ketertarikan_bidang.id_ketertarikan_bidang', '=', 'bidang.id_bidang')->where('nip', $value->nip)->get();
            $dosenList[$key]['ketertarikan_bidang'] = $dosenBidang;
        }

        $data=[
            "list_pengajuan" => $data_pengajuan,
            "dosenList" => $dosenList
        ];

        return view('PengajuanAlokasiPembimbing.views.AlokasiPembimbing.AlokasiPembimbing', $data);
}

    public function getDetailDosen($nip): JsonResponse
    {
        $dosen = User::with('dosen')
            ->whereHas('dosen', function ($query) use ($nip) {
                $query->where('id_dosen', $nip);
            })
            ->where('role_user', 'dosen')
            ->select('username', 'nama')
            ->first();

    if (!$dosen) {
        return response()->json(['error' => 'Dosen tidak ditemukan'], 404);
    }

        $pembimbing1_KoTA = AlokasiDosen::where('nip', $dosen->dosen->nip)
            ->where('urutan_prioritas_terpilih', '1')
            ->where('status_alokasi', 'fix')
            ->count();

        $pembimbing2_KoTA = AlokasiDosen::where('nip', $dosen->dosen->nip)
            ->where('urutan_prioritas_terpilih', '2')
            ->where('status_alokasi', 'fix')
            ->count();

        $jumlah_KoTA = $pembimbing1_KoTA + $pembimbing2_KoTA;


        $pembimbing1_Mhs = Mahasiswa::whereHas('kota.pengajuanPembimbing.alokasiDosen', function ($query) use ($dosen) {
            $query->where('nip',  $dosen->dosen->nip)
                  ->where('urutan_prioritas_terpilih', '1')
                  ->where('status_alokasi', 'fix');
        })->count();

        $pembimbing2_Mhs = Mahasiswa::whereHas('kota.pengajuanPembimbing.alokasiDosen', function ($query) use ($dosen) {
            $query->where('nip',  $dosen->dosen->nip)
                  ->where('urutan_prioritas_terpilih', '2')
                  ->where('status_alokasi', 'fix');
        })->count();

        $jumlahMahasiswa = $pembimbing1_Mhs + $pembimbing2_Mhs;

        $kuota = KuotaMembimbing::where('nip', $dosen->dosen->nip)->sum('jumlah');

        $kelebihan = $jumlahMahasiswa > $kuota ? ($jumlahMahasiswa - $kuota) . " (Overload)" : "Aman";

        return response()->json([
            "nama" => $dosen->nama,
            'nip' => $dosen->username,
            'id_kbk' => $dosen->dosen->id_kbk ?? null,
            "pembimbing1_KoTA" => $pembimbing1_KoTA,
            "pembimbing2_KoTA" => $pembimbing2_KoTA,
            "jumlah_KoTA" => $jumlah_KoTA,
            "pembimbing1_Mhs" => $pembimbing1_Mhs,
            "pembimbing2_Mhs" => $pembimbing2_Mhs,
            "jumlahMahasiswa" => $jumlahMahasiswa,
            "kuota" => $kuota,
            "kelebihan" => $kelebihan,
            'status_dosen' => $dosen->dosen->status_dosen ?? null,
            'role_dosen' => $dosen->dosen->role_dosen ?? null,
            'bersedia_membimbing' => $dosen->dosen->bersedia_membimbing ?? null

        ]);
    }
    public function submit(Request $request)
    {
        $data = json_decode($request->input('dataToSend'));

        foreach ($data as $value) {
            $nip_dosen_1 = Dosen::where('id_dosen', $value->pembimbing1 ?? null)->select('nip')->first();
            $nip_dosen_2 = Dosen::where('id_dosen', $value->pembimbing2 ?? null)->select('nip')->first();

            $nip_penguji_1 = Dosen::where('id_dosen', $value->penguji1 ?? null)->select('nip')->first();
            $nip_penguji_2 = Dosen::where('id_dosen', $value->penguji2 ?? null)->select('nip')->first();
            $nip_penguji_3 = Dosen::where('id_dosen', $value->penguji3 ?? null)->select('nip')->first();

            $catatan = $value->catatan ?? null;

            // **Alokasi Pembimbing**
            if ($nip_dosen_1) {
                AlokasiDosen::updateOrCreate(
                    ['id_pengajuan_pembimbing' => $value->id_pengajuan_pembimbing, 'nip' => $nip_dosen_1->nip],
                    [
                        'urutan_prioritas_terpilih' => 1,
                        'status_alokasi' => $value->status_pembimbing1 ?? 'belum_fix',
                        'catatan' => $catatan,
                        'tipe_alokasi' => 'pembimbing'
                    ]
                );
            }

            if ($nip_dosen_2) {
                AlokasiDosen::updateOrCreate(
                    ['id_pengajuan_pembimbing' => $value->id_pengajuan_pembimbing, 'nip' => $nip_dosen_2->nip],
                    [
                        'urutan_prioritas_terpilih' => 2,
                        'status_alokasi' => $value->status_pembimbing2 ?? 'belum_fix',
                        'catatan' => $catatan,
                        'tipe_alokasi' => 'pembimbing'
                    ]
                );
            }

            // **Alokasi Penguji**
            if ($nip_penguji_1) {
                AlokasiDosen::updateOrCreate(
                    ['id_pengajuan_pembimbing' => $value->id_pengajuan_pembimbing, 'nip' => $nip_penguji_1->nip],
                    [
                        'urutan_prioritas_terpilih' => 1,
                        'status_alokasi' => 'fix',
                        'catatan' => $catatan,
                        'tipe_alokasi' => 'penguji'
                    ]
                );
            }

            if ($nip_penguji_2) {
                AlokasiDosen::updateOrCreate(
                    ['id_pengajuan_pembimbing' => $value->id_pengajuan_pembimbing, 'nip' => $nip_penguji_2->nip],
                    [
                        'urutan_prioritas_terpilih' => 2,
                        'status_alokasi' => 'fix',
                        'catatan' => $catatan,
                        'tipe_alokasi' => 'penguji'
                    ]
                );
            }

            if ($nip_penguji_3) {
                AlokasiDosen::updateOrCreate(
                    ['id_pengajuan_pembimbing' => $value->id_pengajuan_pembimbing, 'nip' => $nip_penguji_3->nip],
                    [
                        'urutan_prioritas_terpilih' => 3,
                        'status_alokasi' => 'fix',
                        'catatan' => $catatan,
                        'tipe_alokasi' => 'penguji'
                    ]
                );
            }
        }

        return redirect()->back()->with('success', 'Data alokasi pembimbing dan penguji berhasil disimpan.');
    }

}
