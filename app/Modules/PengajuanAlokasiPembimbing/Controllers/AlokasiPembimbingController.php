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
use App\Services\NotifikasiService;
use App\Models\TemplateNotifikasi;
use App\Models\Notifikasi;

class AlokasiPembimbingController extends Controller
{protected $notifikasiService;

    public function __construct(NotifikasiService $notifikasiService)
    {
        $this->notifikasiService = $notifikasiService;
    }
    public function index(): View
    {
        $data_pengajuan = PengajuanPembimbing::join('kota', 'pengajuan_pembimbing.id_kota', '=', 'kota.id_kota')
            ->join('bidang', 'kota.id_bidang', '=', 'bidang.id_bidang')
            ->select('pengajuan_pembimbing.*', 'kota.nama_kota', 'kota.id_bidang', 'kota.judul_ta', 'bidang.bidang')
            ->get();

        foreach ($data_pengajuan as $key => $value) {
            $listMahasiswaOnKelompok = Mahasiswa::join('user', 'mahasiswa.nim', '=', 'user.username')
                ->where('id_kota', $value->id_kota)
                ->get();

            $listUsulanDosen = PrioritasPembimbing::join('dosen', 'prioritas_pembimbing.nip', '=', 'dosen.nip')
                ->where('id_pengajuan', $value->id_pengajuan_pembimbing)
                ->get();

                $preferensiDosen = PreferensiKota::join('dosen', 'preferensi_kota.nip', '=', 'dosen.nip')
                ->where('preferensi_kota.id_kota', $value->id_kota)
                ->select('dosen.id_dosen', 'dosen.nip')
                ->limit(2)
                ->get();

            $data_pengajuan[$key]['mahasiswa'] = $listMahasiswaOnKelompok;
            $data_pengajuan[$key]['usulan_dosen'] = $listUsulanDosen;
            $data_pengajuan[$key]['preferensi_dosen'] = $preferensiDosen; // inject ke view
        }

        $dosenList = Dosen::join('user', 'dosen.nip', '=', 'user.username')
            ->leftJoin('kbk', 'dosen.id_kbk', '=', 'kbk.id_kbk')
            ->select('dosen.id_dosen', 'dosen.nip', 'user.nama', 'kbk.kbk')
            ->get();

        return view('PengajuanAlokasiPembimbing.views.AlokasiPembimbing.AlokasiPembimbing', [
            'list_pengajuan' => $data_pengajuan,
            'dosenList' => $dosenList
        ]);
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
            $id_kota = PengajuanPembimbing::where('id_pengajuan_pembimbing', $value->id_pengajuan_pembimbing)
                ->pluck('id_kota')
                ->first();

            // Ambil ID dosen (bukan NIP langsung)
            $pembimbing1 = $value->pembimbing1 ?? null;
            $pembimbing2 = $value->pembimbing2 ?? null;

            $penguji1 = $value->penguji1 ?? null;
            $penguji2 = $value->penguji2 ?? null;
            $penguji3 = $value->penguji3 ?? null;

            // Validasi: Minimal 1 pembimbing dan 1 penguji
            if (empty($pembimbing1) && empty($pembimbing2)) {
                return back()->with('error', 'Minimal 1 pembimbing harus diisi sebelum finalisasi.');
            }

            if (empty($penguji1) && empty($penguji2) && empty($penguji3)) {
                return back()->with('error', 'Minimal 1 penguji harus diisi sebelum finalisasi.');
            }

            // Validasi duplikasi
            if (!empty($pembimbing1) && !empty($pembimbing2) && $pembimbing1 == $pembimbing2) {
                return back()->with('error', 'Pembimbing 1 dan Pembimbing 2 tidak boleh sama dalam satu KoTA.');
            }

            $pengujiSet = array_filter([$penguji1, $penguji2, $penguji3]);
            if (count($pengujiSet) !== count(array_unique($pengujiSet))) {
                return back()->with('error', 'Penguji tidak boleh sama dalam satu KoTA.');
            }

            $nip_dosen_1 = Dosen::where('id_dosen', $pembimbing1)->value('nip');
            $nip_dosen_2 = Dosen::where('id_dosen', $pembimbing2)->value('nip');

            $nip_penguji_1 = Dosen::where('id_dosen', $penguji1)->value('nip');
            $nip_penguji_2 = Dosen::where('id_dosen', $penguji2)->value('nip');
            $nip_penguji_3 = Dosen::where('id_dosen', $penguji3)->value('nip');

            $catatan = $value->catatan ?? null;

            // === SIMPAN PEMBIMBING ===
            if ($nip_dosen_1) {
                AlokasiDosen::updateOrCreate(
                    ['id_pengajuan_pembimbing' => $value->id_pengajuan_pembimbing, 'nip' => $nip_dosen_1],
                    [
                        'urutan_prioritas_terpilih' => 1,
                        'status_alokasi' => $value->status_pembimbing1 ?? 'belum_fix',
                        'catatan' => $catatan,
                        'tipe_alokasi' => 'pembimbing'
                    ]
                );
            }

            // Panggil function kirim notifikasi
            $this->kirimNotifikasiAlokasi($nip_dosen_1, auth()->user()->username);

            if ($nip_dosen_2) {
                AlokasiDosen::updateOrCreate(
                    ['id_pengajuan_pembimbing' => $value->id_pengajuan_pembimbing, 'nip' => $nip_dosen_2],
                    [
                        'urutan_prioritas_terpilih' => 2,
                        'status_alokasi' => $value->status_pembimbing2 ?? 'belum_fix',
                        'catatan' => $catatan,
                        'tipe_alokasi' => 'pembimbing'
                    ]
                );
            }

            // === SIMPAN PENGUJI ===
            foreach ([[1, $nip_penguji_1], [2, $nip_penguji_2], [3, $nip_penguji_3]] as [$urutan, $nip]) {
                if ($nip) {
                    AlokasiDosen::updateOrCreate(
                        ['id_pengajuan_pembimbing' => $value->id_pengajuan_pembimbing, 'nip' => $nip],
                        [
                            'urutan_prioritas_terpilih' => $urutan,
                            'status_alokasi' => 'fix',
                            'catatan' => $catatan,
                            'tipe_alokasi' => 'penguji'
                        ]
                    );
                }
            }

            // === UPDATE STATUS PENGAJUAN ===
            $statusFix1 = $value->status_pembimbing1 ?? null;
            $statusFix2 = $value->status_pembimbing2 ?? null;

            $status_pengajuan = ($statusFix1 === 'fix' || $statusFix2 === 'fix') ? 'diterima' : 'diproses';

            PengajuanPembimbing::where('id_pengajuan_pembimbing', $value->id_pengajuan_pembimbing)
                ->update(['status_pengajuan' => $status_pengajuan]);
        }

        return redirect()->back()->with('success', 'Data alokasi pembimbing dan penguji berhasil disimpan.');
    }

}
