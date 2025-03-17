<?php

namespace App\Modules\PengajuanAlokasiPembimbing\Controllers;

use App\Modules\Controller;
use Illuminate\View\View;
<<<<<<< HEAD
=======
use Illuminate\Http\JsonResponse;
>>>>>>> 58f96f1821308e854679e72c61ec57c4ccb8db0b
use Illuminate\Http\Request;
use App\Models\Kota;
use App\Models\User;
use App\Models\PengajuanPembimbing;
use App\Models\PrioritasPembimbing;
use App\Models\AlokasiPembimbing;
use App\Models\Dosen;
use App\Models\Bidang;
<<<<<<< HEAD
=======
use App\Models\Mahasiswa;
use App\Models\KetertarikanBidang;
>>>>>>> 58f96f1821308e854679e72c61ec57c4ccb8db0b
use Illuminate\Support\Facades\DB;

class AlokasiPembimbingController extends Controller
{
    public function index(): View
    {
<<<<<<< HEAD
        // Ambil daftar kota
        $kotaList = Kota::pluck('nama_kota', 'id_kota')->toArray();

        // Ambil daftar mahasiswa berdasarkan kota (dikelompokkan berdasarkan id_kota)
        $mahasiswaList = User::join('mahasiswa', 'user.username', '=', 'mahasiswa.nim')
            ->select('mahasiswa.id_kota', 'user.nama', 'user.username as nim')
            ->get()
            ->groupBy('id_kota')
            ->toArray();

        $data = [];

        foreach ($kotaList as $idKota => $namaKota) {
            // Ambil daftar mahasiswa per kota
            $anggota = array_map(function ($mahasiswa) {
                return [
                    'nama' => $mahasiswa['nama'],
                    'nim' => $mahasiswa['nim']
                ];
            }, $mahasiswaList[$idKota] ?? []);

            $jumlahMahasiswa = count($anggota);

            // Ambil pengajuan pembimbing berdasarkan id_kota
            $pengajuanPembimbing = PengajuanPembimbing::where('id_kota', $idKota)->first();

            // Ambil bidang berdasarkan id_bidang dari kota
            $idBidang = Kota::where('id_kota', $idKota)->value('id_bidang');
            $bidang = Bidang::where('id_bidang', $idBidang)->value('bidang') ?? 'Bidang Tidak Ditemukan';

            // Ambil judul dari pengajuan atau kota
            $judul = $pengajuanPembimbing?->judul ?? Kota::where('id_kota', $idKota)->value('judul_ta') ?? 'Belum Ada Judul';

            // Ambil prioritas pembimbing dengan join ke tabel `user` untuk mengambil nama dosen
            $usulanDosen = [];

            if ($pengajuanPembimbing) {
                $usulanDosen = DB::table('prioritas_pembimbing')
                    ->join('dosen', 'prioritas_pembimbing.nip', '=', 'dosen.nip')
                    ->join('user', 'dosen.nip', '=', 'user.username')
                    ->where('prioritas_pembimbing.id_pengajuan', $pengajuanPembimbing->id_pengajuan_pembimbing)
                    ->orderBy('prioritas_pembimbing.urutan_prioritas', 'asc')
                    ->get(['dosen.id_dosen', 'user.nama'])
                    ->toArray();
            }

            // Pastikan ada 5 elemen dalam array
            $usulanDosen = array_pad($usulanDosen, 5, (object) ['id_dosen' => '-', 'nama' => '-']);

            // Ambil alokasi pembimbing berdasarkan pengajuan (jika ada)
            $alokasiPembimbing = AlokasiPembimbing::where('id_pengajuan_pembimbing', $pengajuanPembimbing->id_pengajuan_pembimbing ?? null)
                ->pluck('nip')
                ->toArray();

            // Konversi NIP ke nama dosen dengan join ke tabel user
            $pembimbing = DB::table('user')
                ->whereIn('username', $alokasiPembimbing) // username menyimpan NIP
                ->pluck('nama')
                ->toArray();

            $data[] = [
                'kota' => $namaKota,
                'anggota' => $anggota,
                'jumlahMahasiswa' => $jumlahMahasiswa,
                'bidang' => $bidang, // Bidang dari Kota
                'judul' => $judul, // Judul dari Pengajuan atau Kota
                'usulanDosen' => $usulanDosen,
                'pembimbing1' => $pembimbing[0] ?? '-',
                'pembimbing2' => $pembimbing[1] ?? '-',
            ];
        }

        return view('PengajuanAlokasiPembimbing.views.AlokasiPembimbing.AlokasiPembimbing', compact('data'));
    }

    public function simpanDraft(Request $request)
    {
        // Simpan draft sementara dengan session flash
        session()->flash('success', 'Data tersimpan sebagai draft');
        return back();
=======
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
>>>>>>> 58f96f1821308e854679e72c61ec57c4ccb8db0b
    }
}
