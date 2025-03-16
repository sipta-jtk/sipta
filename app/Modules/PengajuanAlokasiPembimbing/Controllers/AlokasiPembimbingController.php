<?php

namespace App\Modules\PengajuanAlokasiPembimbing\Controllers;

use App\Modules\Controller;
use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Models\Kota;
use App\Models\User;
use App\Models\PengajuanPembimbing;
use App\Models\PrioritasPembimbing;
use App\Models\AlokasiPembimbing;
use App\Models\Dosen;
use App\Models\Bidang;
use Illuminate\Support\Facades\DB;

class AlokasiPembimbingController extends Controller
{
    public function index(): View
    {
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
    }
}
