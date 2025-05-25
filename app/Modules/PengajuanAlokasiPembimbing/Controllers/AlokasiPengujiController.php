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

class AlokasiPengujiController extends Controller
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

            $data_pengajuan[$key]['usulan_dosen'] = PreferensiKota::join('dosen', 'preferensi_kota.nip', '=', 'dosen.nip')
                ->where('preferensi_kota.id_kota', $value->id_kota)
                ->select('dosen.id_dosen')
                ->get();

            $data_pengajuan[$key]['alokasi'] = AlokasiDosen::where('id_pengajuan_pembimbing', $value->id_pengajuan_pembimbing)
                ->where('tipe_alokasi', 'penguji')
                ->orderBy('urutan_prioritas_terpilih')
                ->join('dosen', 'alokasi_dosen.nip', '=', 'dosen.nip')
                ->select('dosen.id_dosen', 'alokasi_dosen.*')
                ->get();

            $data_pengajuan[$key]['pembimbing'] = AlokasiDosen::where('id_pengajuan_pembimbing', $value->id_pengajuan_pembimbing)
                ->where('tipe_alokasi', 'pembimbing')
                ->orderBy('urutan_prioritas_terpilih')
                ->join('dosen', 'alokasi_dosen.nip', '=', 'dosen.nip')
                ->select('dosen.id_dosen', 'alokasi_dosen.*')
                ->get();

            if ($data_pengajuan[$key]['mahasiswa']->isNotEmpty()) {
                $firstMahasiswa = $data_pengajuan[$key]['mahasiswa']->first();
                $data_pengajuan[$key]['prodi'] = $firstMahasiswa->nama_prodi;
                $data_pengajuan[$key]['kode_prodi'] = substr($firstMahasiswa->nama_prodi, 0, 2); // D3 / D4
            } else {
                $data_pengajuan[$key]['prodi'] = null;
                $data_pengajuan[$key]['kode_prodi'] = null;
            }
        }

        $dosenList = Dosen::join('user', 'dosen.nip', '=', 'user.username')
            ->select('dosen.id_dosen', 'dosen.nip', 'user.nama')
            ->get();

        $prodiList = DB::table('prodi')->select('id_prodi', 'nama_prodi')->get();

        // dd($data_pengajuan);

        return view('PengajuanAlokasiPembimbing.views.AlokasiDosenPenguji.AlokasiDosenPenguji', [
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

    public function updateAlokasi(Request $request)
    {
        $id_pengajuan = $request->input('id_pengajuan_pembimbing');
        $nip = $request->input('nip');
        $urutan_prioritas = $request->input('urutan_prioritas_terpilih');
        $status_alokasi = $request->input('status_alokasi');
        $tipe_alokasi = $request->input('tipe_alokasi');

        $otherUrutan = null;
        if ($urutan_prioritas == 1) {
            $otherUrutan = 2;
        } elseif ($urutan_prioritas == 2) {
            $otherUrutan = 1;
        }

        // check pembimbingan
        $currentDosen = DB::table('alokasi_dosen')
            ->where('id_pengajuan_pembimbing', $id_pengajuan)
            ->where('tipe_alokasi', 'pembimbing')
            ->where('nip', $nip)
            ->first();
        if ($currentDosen) {
            return response()->json([
                'status' => 'error',
                'message' => 'Dosen sudah teralokasi sebagai pembimbing!'
            ]);
        }

        // - check pembimbingan

        $currentDosenCounterPart = DB::table('alokasi_dosen')
            ->where('id_pengajuan_pembimbing', $id_pengajuan)
            ->where('urutan_prioritas_terpilih', $otherUrutan)
            ->where('tipe_alokasi', $tipe_alokasi)
            ->first();

        if ($currentDosenCounterPart && $currentDosenCounterPart->nip == $nip) {
            DB::table('alokasi_dosen')
                ->where('id_pengajuan_pembimbing', $id_pengajuan)
                ->where('urutan_prioritas_terpilih', $otherUrutan)
                ->where('tipe_alokasi', $tipe_alokasi)
                ->delete();
        }

        $currentAllocation = DB::table('alokasi_dosen')
            ->where('id_pengajuan_pembimbing', $id_pengajuan)
            ->where('urutan_prioritas_terpilih', $urutan_prioritas)
            ->where('tipe_alokasi', $tipe_alokasi)
            ->count();

        if ($currentAllocation > 0) {
            DB::table('alokasi_dosen')
                ->where('id_pengajuan_pembimbing', $id_pengajuan)
                ->where('urutan_prioritas_terpilih', $urutan_prioritas)
                ->where('tipe_alokasi', $tipe_alokasi)
                ->delete();
        }

        DB::table('alokasi_dosen')->insert([
            'id_pengajuan_pembimbing' => $id_pengajuan,
            'nip' => $nip,
            'urutan_prioritas_terpilih' => $urutan_prioritas,
            'status_alokasi' => $status_alokasi,
            'tipe_alokasi' => $tipe_alokasi,
        ]);
        return response()->json([
            'status' => 'success',
            'message' => 'Alokasi berhasil diperbarui!'
        ]);
    }

    public function deleteAlokasi(Request $request)
    {
        $id_pengajuan = $request->input('id_pengajuan_pembimbing');
        $urutan_prioritas = $request->input('urutan_prioritas_terpilih');

        DB::table('alokasi_dosen')
            ->where('id_pengajuan_pembimbing', $id_pengajuan)
            ->where('urutan_prioritas_terpilih', $urutan_prioritas)
            ->delete();

        return redirect()->back()->with('success', 'Alokasi berhasil dihapus!');
    }

    public function fixAlokasi(Request $request)
    {
        $id_pengajuan = $request->input('id_pengajuan_pembimbing');
        $urutan_prioritas = $request->input('urutan_prioritas_terpilih');

        $statusAlokasi = DB::table('alokasi_dosen')
            ->where('id_pengajuan_pembimbing', $id_pengajuan)
            ->where('urutan_prioritas_terpilih', $urutan_prioritas)
            ->where('tipe_alokasi', 'penguji')
            ->value('status_alokasi');

        $newStatusAlokasi = ($statusAlokasi === 'fix') ? 'belum_fix' : 'fix';

        DB::table('alokasi_dosen')
            ->where('id_pengajuan_pembimbing', $id_pengajuan)
            ->where('urutan_prioritas_terpilih', $urutan_prioritas)
            ->where('tipe_alokasi', 'penguji')
            ->update([
                'status_alokasi' => $newStatusAlokasi,
                'tipe_alokasi' => 'penguji',
            ]);

        return response()->json([
            'status' => 'success',
            'new_status' => $newStatusAlokasi
        ]);
    }
}