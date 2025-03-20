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
use App\Models\AlokasiPembimbing;
use App\Models\Dosen;
use App\Models\Bidang;
use App\Models\Mahasiswa;
use App\Models\KetertarikanBidang;
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

        // dd($data);

        return view('PengajuanAlokasiPembimbing.views.AlokasiPembimbing.AlokasiPembimbing', $data);
}

    public function getDetailDosen($nama): JsonResponse
    {
        return response()->json($this->generateDummyDosenDetail($nama));
    }

    private function generateDummyDosenDetail($nama): array
    {
        $pembimbing1_KoTA = rand(5, 15);
        $pembimbing2_KoTA = rand(3, 10);
        $jumlah_KoTA = $pembimbing1_KoTA + $pembimbing2_KoTA;

        $pembimbing1_Mhs = rand(10, 30);
        $pembimbing2_Mhs = rand(5, 20);
        $jumlahMahasiswa = $pembimbing1_Mhs + $pembimbing2_Mhs;

        $kuota = rand(20, 40);
        $kelebihan = max(0, $jumlahMahasiswa - $kuota);

        return [
            "nama" => "Dosen " . $nama,
            "pembimbing1_KoTA" => $pembimbing1_KoTA,
            "pembimbing2_KoTA" => $pembimbing2_KoTA,
            "jumlah_KoTA" => $jumlah_KoTA,
            "pembimbing1_Mhs" => $pembimbing1_Mhs,
            "pembimbing2_Mhs" => $pembimbing2_Mhs,
            "jumlahMahasiswa" => $jumlahMahasiswa,
            "kuota" => $kuota,
            "kelebihan" => $kelebihan
        ];
    }
}
