<?php

namespace App\Modules\PerencanaanDanPelaksanaanSeminar3DanSidang\Controllers;

use App\Models\Pembatalan;
use App\Models\Penjadwalan;
use App\Modules\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Http\Request;

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
        ->join('kota', 'penjadwalan.id_kota', '=', 'kota.id_kota')
        ->join('user', 'pembatalan.nip', '=', 'user.username')
        ->where('agenda', '=', 'seminar_3')->get();
        // dd($jadwal);
        return view('PerencanaanDanPelaksanaanSeminar3DanSidang.views.pembatalan.persetujuanpembatalanjadwalseminar', compact('jadwal'));
    }

    public function persetujuanPembatalanSeminar($pembatalan_id, $status)
    {
        $pembatalan = Pembatalan::find( $pembatalan_id, 'id_pembatalan');
        if($pembatalan->status_pembatalan == '1'){
            return redirect()->route('view.persetujuan.pembatalan.seminar')->with('error', 'Pembatalan jadwal sudah disetujui.');
        } elseif($pembatalan->status_pembatalan == '0'){
            return redirect()->route('view.persetujuan.pembatalan.seminar')->with('error', 'Pembatalan jadwal sudah ditolak.');
        }
        $pembatalan->status_pembatalan = $status;
        $pembatalan->save();
        $message = $status == '1' ? 'Pembatalan jadwal disetujui.' : 'Pembatalan jadwal ditolak';

        return redirect()->route('view.persetujuan.pembatalan.seminar')->with('success', $message);
    }

    public function indexPersetujuanPembatalanJadwalSidang(): View
    {
        $jadwal = Penjadwalan::select()
            ->join('pembatalan', 'penjadwalan.id_penjadwalan', '=', 'pembatalan.id_penjadwalan')
            ->join('kota', 'penjadwalan.id_kota', '=', 'kota.id_kota')
            ->join('user', 'pembatalan.nip', '=', 'user.username')
            ->where('agenda', '=', 'sidang')
            ->get();
        // dd($jadwal);
        return view('PerencanaanDanPelaksanaanSeminar3DanSidang.views.pembatalan.persetujuanpembatalanjadwalsidang', compact('jadwal'));
    }

    public function persetujuanPembatalanSidang($pembatalan_id, $status)
    {
        $pembatalan = Pembatalan::find( $pembatalan_id, 'id_pembatalan');
        if($pembatalan->status_pembatalan == '1'){
            return redirect()->route('view.persetujuan.pembatalan.sidang')->with('error', 'Pembatalan jadwal sudah disetujui.');
        } elseif($pembatalan->status_pembatalan == '0'){
            return redirect()->route('view.persetujuan.pembatalan.sidang')->with('error', 'Pembatalan jadwal sudah ditolak.');
        }
        $pembatalan->status_pembatalan = $status;
        $pembatalan->save();
        $message = $status == '1' ? 'Pembatalan jadwal disetujui.' : 'Pembatalan jadwal ditolak';

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
        ->where('alokasi_dosen.nip', '=',$user)->get();
        // dd($jadwal_penguji);
        
        $jadwal_pembimbing = Penjadwalan::select('penjadwalan.id_penjadwalan as penjadwalan_id', 'penjadwalan.*', 'kota.*', 'pengajuan_pembimbing.*', 'alokasi_dosen.*', 'pembatalan.*')
        ->join('kota', 'penjadwalan.id_kota', '=', 'kota.id_kota')
        ->join('pengajuan_pembimbing', 'kota.id_kota', '=', 'pengajuan_pembimbing.id_kota')
        ->join('alokasi_dosen', 'pengajuan_pembimbing.id_pengajuan_pembimbing', '=', 'alokasi_dosen.id_pengajuan_pembimbing')
        ->leftJoin('pembatalan', 'penjadwalan.id_penjadwalan', '=', 'pembatalan.id_penjadwalan')
        ->where('agenda', '=', 'seminar_3')
        ->where('alokasi_dosen.status_alokasi', 'fix')
        ->where('alokasi_dosen.tipe_alokasi', 'pembimbing')
        ->where('alokasi_dosen.nip', $user)->get();
        // dd($jadwal_pembimbing);
        return view('PerencanaanDanPelaksanaanSeminar3DanSidang.views.jadwal.bataljadwalseminar', compact('jadwal_penguji', 'jadwal_pembimbing'));
    }

    public function pembatalanJadwalSeminar(Request $request)
    {
        // dd($request);
        $nip = Auth::user()->dosen->nip;
        $pembatalan = Pembatalan::create([
            'id_penjadwalan' => $request->id,
            'alasan_pembatalan' => $request->alasan,
            'nip' => $nip
        ]);


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
        ->where('alokasi_dosen.nip', '=',$user)->get();
        // dd($jadwal_penguji);
        
        $jadwal_pembimbing = Penjadwalan::select('penjadwalan.id_penjadwalan as penjadwalan_id', 'penjadwalan.*', 'kota.*', 'pengajuan_pembimbing.*', 'alokasi_dosen.*', 'pembatalan.*')
        ->join('kota', 'penjadwalan.id_kota', '=', 'kota.id_kota')
        ->join('pengajuan_pembimbing', 'kota.id_kota', '=', 'pengajuan_pembimbing.id_kota')
        ->join('alokasi_dosen', 'pengajuan_pembimbing.id_pengajuan_pembimbing', '=', 'alokasi_dosen.id_pengajuan_pembimbing')
        ->leftJoin('pembatalan', 'penjadwalan.id_penjadwalan', '=', 'pembatalan.id_penjadwalan')
        ->where('agenda', '=', 'sidang')
        ->where('alokasi_dosen.status_alokasi', 'fix')
        ->where('alokasi_dosen.tipe_alokasi', 'pembimbing')
        ->where('alokasi_dosen.nip', $user)->get();
        return view('PerencanaanDanPelaksanaanSeminar3DanSidang.views.jadwal.bataljadwalsidang', compact('jadwal_penguji', 'jadwal_pembimbing'));
    }

    public function pembatalanJadwalSidang(Request $request)
    {
        // dd($request);
        $nip = Auth::user()->dosen->nip;
        $pembatalan = Pembatalan::create([
            'id_penjadwalan' => $request->id,
            'alasan_pembatalan' => $request->alasan,
            'nip' => $nip
        ]);


        return redirect()->route('jadwal.seminar')->with('success', 'Pengajuan pembatalan jadwal sidang berhasil dikirimkan.');
    }

}