<?php

namespace App\Modules\PerencanaanDanPelaksanaanSeminar3DanSidang\Controllers;

use App\Models\Pembatalan;
use App\Models\Penjadwalan;
use App\Modules\Controller;
use Http;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use App\Services\Notifikasi;
use Illuminate\Http\Request;
use Carbon\Carbon;
Carbon::setLocale('id');

class PembatalanJadwalSeminarSidangController extends Controller
{
    public function index(): View
    {
        return view('PerencanaanDanPelaksanaanSeminar3DanSidang.views.view');
    }

    public function indexPersetujuanPembatalanJadwalSeminar(): View
    {
        $jadwal = Penjadwalan::select()
            ->join('pembatalan', 'penjadwalan.id_penjadwalan', '=', 'pembatalan.id_penjadwalan')
            ->where(function ($query) {
                $query->where('pembatalan.status_pembatalan', '!=', '1')
                    ->where('pembatalan.status_pembatalan', '!=', '0');
            })
            ->join('kota', 'penjadwalan.id_kota', '=', 'kota.id_kota')
            ->join('user', 'pembatalan.nip', '=', 'user.username')
            ->where('agenda', '=', 'seminar_3')->get()->map(function ($item) {
                $item->tanggal = Carbon::parse($item->tanggal)->translatedFormat('d F Y');
                return $item;
            });
        // dd($jadwal);
        return view('PerencanaanDanPelaksanaanSeminar3DanSidang.views.pembatalan.persetujuanpembatalanjadwalseminar', compact('jadwal'));
    }

    public function persetujuanPembatalanSeminar($pembatalan_id, $status)
    {
        $pembatalan = Pembatalan::find($pembatalan_id);
        $penjadwalan = Penjadwalan::find($pembatalan->id_penjadwalan);
        $response = Http::timeout(10)->withoutVerifying()->get('https://polban-space.cloudias79.com/penjadwalan-ruangan/api/v1/schedules');
        $id_penjadwalan_api = null;
        if ($response->successful()) {
            $data = $response->json();
            foreach ($data as $item) {
            if (
                isset($item['id_ruangan'], $item['id_kota'], $item['start'], $item['end']) &&
                $item['id_ruangan'] == $penjadwalan->id_ruangan &&
                $item['id_kota'] == $penjadwalan->id_kota
            ) {
                $itemStart = Carbon::parse($item['start'])->format('Y-m-d H:i:s');
                $itemEnd = Carbon::parse($item['end'])->format('Y-m-d H:i:s');
                $jadwalStart = Carbon::parse($penjadwalan->start)->format('Y-m-d H:i:s');
                $jadwalEnd = Carbon::parse($penjadwalan->end)->format('Y-m-d H:i:s');
                if ($itemStart == $jadwalStart && $itemEnd == $jadwalEnd) {
                $id_penjadwalan_api = $item['id_penjadwalan'];
                break;
                }
            }
            }
        }
        if (!$id_penjadwalan_api) {
            return redirect()->route('view.persetujuan.pembatalan.seminar')->with('error', 'Jadwal tidak ditemukan di API ruangan.');
        }
        if ($pembatalan->status_pembatalan == '1') {
            return redirect()->route('view.persetujuan.pembatalan.seminar')->with('error', 'Pembatalan jadwal sudah disetujui.');
        } elseif ($pembatalan->status_pembatalan == '0') {
            return redirect()->route('view.persetujuan.pembatalan.seminar')->with('error', 'Pembatalan jadwal sudah ditolak.');
        }
        $pembatalan->status_pembatalan = $status;
        $pembatalan->save();
        if ($status == '1') {
            $message = 'Pembatalan jadwal disetujui.';
            $token = auth()->user()->createToken(auth()->user()->username . '_token')->plainTextToken;
            $data = [
            'type' => 'delete',
            'id' => $id_penjadwalan_api,
            ];
            $response = Http::withHeaders([
            'X-Requested-With' => 'XMLHttpRequest',
            'content-type' => 'application/json',
            'Accept' => 'application/json',
            ])->withToken($token)->post('https://polban-space.cloudias79.com/penjadwalan-ruangan/api/v1/schedule/action', $data);
        } else {
            $message = 'Pembatalan jadwal ditolak';
            $pembatalan->delete();
        }

        return redirect()->route('view.persetujuan.pembatalan.seminar')->with('success', $message);
    }

    public function indexPersetujuanPembatalanJadwalSidang(): View
    {
        $jadwal = Penjadwalan::select()
            ->join('pembatalan', 'penjadwalan.id_penjadwalan', '=', 'pembatalan.id_penjadwalan')
            ->where(function ($query) {
                $query->where('pembatalan.status_pembatalan', '!=', '1')
                    ->where('pembatalan.status_pembatalan', '!=', '0');
            })
            ->join('kota', 'penjadwalan.id_kota', '=', 'kota.id_kota')
            ->join('user', 'pembatalan.nip', '=', 'user.username')
            ->where('agenda', '=', 'sidang')
            ->get()->map(function ($item) {
                $item->tanggal = Carbon::parse($item->tanggal)->translatedFormat('d F Y');
                return $item;
            });
        // dd($jadwal);
        return view('PerencanaanDanPelaksanaanSeminar3DanSidang.views.pembatalan.persetujuanpembatalanjadwalsidang', compact('jadwal'));
    }

    public function persetujuanPembatalanSidang($pembatalan_id, $status)
    {
        $pembatalan = Pembatalan::find($pembatalan_id);
        $penjadwalan = Penjadwalan::find($pembatalan->id_penjadwalan);
        $response = Http::timeout(10)->withoutVerifying()->get('https://polban-space.cloudias79.com/penjadwalan-ruangan/api/v1/schedules');
        $id_penjadwalan_api = null;
        if ($response->successful()) {
            $data = $response->json();
            foreach ($data as $item) {
                if (
                    isset($item['id_ruangan'], $item['id_kota'], $item['start'], $item['end']) &&
                    $item['id_ruangan'] == $penjadwalan->id_ruangan &&
                    $item['id_kota'] == $penjadwalan->id_kota
                ) {
                    // Normalize both start and end to Y-m-d H:i:s for comparison
                    $itemStart = Carbon::parse($item['start'])->format('Y-m-d H:i:s');
                    $itemEnd = Carbon::parse($item['end'])->format('Y-m-d H:i:s');
                    $jadwalStart = Carbon::parse($penjadwalan->start)->format('Y-m-d H:i:s');
                    $jadwalEnd = Carbon::parse($penjadwalan->end)->format('Y-m-d H:i:s');
                    if ($itemStart == $jadwalStart && $itemEnd == $jadwalEnd) {
                        $id_penjadwalan_api = $item['id_penjadwalan'];
                        break;
                    }
                }
            }
        }
        if (!$id_penjadwalan_api) {
            return redirect()->route('view.persetujuan.pembatalan.sidang')->with('error', 'Jadwal tidak ditemukan di API ruangan.');
        }
        if ($pembatalan->status_pembatalan == '1') {
            return redirect()->route('view.persetujuan.pembatalan.sidang')->with('error', 'Pembatalan jadwal sudah disetujui.');
        } elseif ($pembatalan->status_pembatalan == '0') {
            return redirect()->route('view.persetujuan.pembatalan.sidang')->with('error', 'Pembatalan jadwal sudah ditolak.');
        }
        $pembatalan->status_pembatalan = $status;
        $pembatalan->save();
        $message = $status == '1' ? 'Pembatalan jadwal disetujui.' : 'Pembatalan jadwal ditolak';
        if ($status == '1') {
            $token = auth()->user()->createToken(auth()->user()->username . '_token')->plainTextToken;
            $data = [
                'type' => 'delete',
                'id' => $id_penjadwalan_api,
            ];
            $response = Http::withHeaders([
                'X-Requested-With' => 'XMLHttpRequest',
                'content-type' => 'application/json',
                'Accept' => 'application/json',
                ])->withToken($token)->post('https://polban-space.cloudias79.com/penjadwalan-ruangan/api/v1/schedule/action', $data);
        }
        if ($status == '0') {
            $pembatalan->delete();
        }
        return redirect()->route('view.persetujuan.pembatalan.sidang')->with('success', $message);
    }

    public function indexJadwalSeminar(): View
    {
        $user = Auth::user()->username;
        $jadwal_penguji = Penjadwalan::select('penjadwalan.id_penjadwalan as penjadwalan_id', 'penjadwalan.*', 'kota.*', 'pengajuan_pembimbing.*', 'alokasi_dosen.*', 'pembatalan.*')
            ->join('kota', 'penjadwalan.id_kota', '=', 'kota.id_kota')
            ->join('pengajuan_pembimbing', 'kota.id_kota', '=', 'pengajuan_pembimbing.id_kota')
            ->join('alokasi_dosen', 'pengajuan_pembimbing.id_pengajuan_pembimbing', '=', 'alokasi_dosen.id_pengajuan_pembimbing')
            ->leftJoin('pembatalan', 'penjadwalan.id_penjadwalan', '=', 'pembatalan.id_penjadwalan')
            ->where('agenda', '=', 'seminar_3')
            ->where('alokasi_dosen.status_alokasi', 'fix')
            ->where('alokasi_dosen.tipe_alokasi', 'penguji')
            ->where('alokasi_dosen.nip', '=', $user)->get()->map(function ($item) {
                $item->tanggal = Carbon::parse($item->tanggal)->translatedFormat('d F Y');
                return $item;
            });
        // dd($jadwal_penguji);

        $jadwal_pembimbing = Penjadwalan::select('penjadwalan.id_penjadwalan as penjadwalan_id', 'penjadwalan.*', 'kota.*', 'pengajuan_pembimbing.*', 'alokasi_dosen.*', 'pembatalan.*')
            ->join('kota', 'penjadwalan.id_kota', '=', 'kota.id_kota')
            ->join('pengajuan_pembimbing', 'kota.id_kota', '=', 'pengajuan_pembimbing.id_kota')
            ->join('alokasi_dosen', 'pengajuan_pembimbing.id_pengajuan_pembimbing', '=', 'alokasi_dosen.id_pengajuan_pembimbing')
            ->leftJoin('pembatalan', 'penjadwalan.id_penjadwalan', '=', 'pembatalan.id_penjadwalan')
            ->where('agenda', '=', 'seminar_3')
            ->where('alokasi_dosen.status_alokasi', 'fix')
            ->where('alokasi_dosen.tipe_alokasi', 'pembimbing')
            ->where('alokasi_dosen.nip', $user)->get()->map(function ($item) {
                $item->tanggal = Carbon::parse($item->tanggal)->translatedFormat('d F Y');
                return $item;
            });
        // dd($jadwal_pembimbing);
        return view('PerencanaanDanPelaksanaanSeminar3DanSidang.views.jadwal.bataljadwalseminar', compact('jadwal_penguji', 'jadwal_pembimbing'));
    }

    public function pembatalanJadwalSeminar(Request $request)
    {
        // dd($request);
        $nip = Auth::user()->dosen->nip;
        $pembatalan = Pembatalan::where('id_penjadwalan', $request->id)->first();
        if ($pembatalan) {
            if ($pembatalan->status_pembatalan == '1') {
                return redirect()->route('jadwal.seminar')->with('error', 'Pengajuan pembatalan jadwal seminar sudah disetujui.');
            } else {
                return redirect()->route('jadwal.seminar')->with('error', 'Pengajuan pembatalan jadwal seminar sudah diajukan.');
            }
        }
        $pembatalan = Pembatalan::create([
            'id_penjadwalan' => $request->id,
            'alasan_pembatalan' => $request->alasan,
            'nip' => $nip
        ]);
        // Notifikasi Pembatalan Penjadwalan Seminar oleh mahasiswa
        // Not So Sure About This
         Notifikasi::kirim(
            '[Pemberitahuan] Pembatalan Penjadwalan Seminar',
            $nip, // Kirim ke dosen pembimbing (nip)
            [
                'Alasan' => $request->alasan
            ]
        );
        return redirect()->route('jadwal.seminar')->with('success', 'Pengajuan pembatalan jadwal seminar berhasil dikirimkan.');
    }

    public function indexJadwalSidang(): View
    {
        $user = Auth::user()->username;
        $jadwal_penguji = Penjadwalan::select('penjadwalan.id_penjadwalan as penjadwalan_id', 'penjadwalan.*', 'kota.*', 'pengajuan_pembimbing.*', 'alokasi_dosen.*', 'pembatalan.*')
            ->join('kota', 'penjadwalan.id_kota', '=', 'kota.id_kota')
            ->join('pengajuan_pembimbing', 'kota.id_kota', '=', 'pengajuan_pembimbing.id_kota')
            ->join('alokasi_dosen', 'pengajuan_pembimbing.id_pengajuan_pembimbing', '=', 'alokasi_dosen.id_pengajuan_pembimbing')
            ->leftJoin('pembatalan', 'penjadwalan.id_penjadwalan', '=', 'pembatalan.id_penjadwalan')
            ->where('agenda', '=', 'sidang')
            ->where('alokasi_dosen.status_alokasi', 'fix')
            ->where('alokasi_dosen.tipe_alokasi', 'penguji')
            ->where('alokasi_dosen.nip', '=', $user)->get()->map(function ($item) {
                $item->tanggal = Carbon::parse($item->tanggal)->translatedFormat('d F Y');
                return $item;
            });
        // dd($jadwal_penguji);

        $jadwal_pembimbing = Penjadwalan::select('penjadwalan.id_penjadwalan as penjadwalan_id', 'penjadwalan.*', 'kota.*', 'pengajuan_pembimbing.*', 'alokasi_dosen.*', 'pembatalan.*')
            ->join('kota', 'penjadwalan.id_kota', '=', 'kota.id_kota')
            ->join('pengajuan_pembimbing', 'kota.id_kota', '=', 'pengajuan_pembimbing.id_kota')
            ->join('alokasi_dosen', 'pengajuan_pembimbing.id_pengajuan_pembimbing', '=', 'alokasi_dosen.id_pengajuan_pembimbing')
            ->leftJoin('pembatalan', 'penjadwalan.id_penjadwalan', '=', 'pembatalan.id_penjadwalan')
            ->where('agenda', '=', 'sidang')
            ->where('alokasi_dosen.status_alokasi', 'fix')
            ->where('alokasi_dosen.tipe_alokasi', 'pembimbing')
            ->where('alokasi_dosen.nip', $user)->get()->map(function ($item) {
                $item->tanggal = Carbon::parse($item->tanggal)->translatedFormat('d F Y');
                return $item;
            });
        return view('PerencanaanDanPelaksanaanSeminar3DanSidang.views.jadwal.bataljadwalsidang', compact('jadwal_penguji', 'jadwal_pembimbing'));
    }

    public function pembatalanJadwalSidang(Request $request)
    {
        // dd($request);
        $nip = Auth::user()->dosen->nip;
        $pembatalan = Pembatalan::where('id_penjadwalan', $request->id)->first();
        if ($pembatalan) {
            if ($pembatalan->status_pembatalan == '1') {
                return redirect()->route('jadwal.sidang')->with('error', 'Pengajuan pembatalan jadwal sidang sudah disetujui.');
            } else {
                return redirect()->route('jadwal.sidang')->with('error', 'Pengajuan pembatalan jadwal sidang sudah diajukan.');
            }
        }
        $pembatalan = Pembatalan::create([
            'id_penjadwalan' => $request->id,
            'alasan_pembatalan' => $request->alasan,
            'nip' => $nip
        ]);

        // Not So Sure About This
        //Notifikasi Pembatalan Penjadawalan Sidang oleh mahasiswa
        Notifikasi::kirim(
            '[Pemberitahuan] Pembatalan Jadwal Sidang', // Judul template notifikasi
            $nip, // Ganti dengan username admin, atau log system
            [
               'Alasan' => $request->alasan
            ]
        );

        return redirect()->route('jadwal.seminar')->with('success', 'Pengajuan pembatalan jadwal sidang berhasil dikirimkan.');
    }

}