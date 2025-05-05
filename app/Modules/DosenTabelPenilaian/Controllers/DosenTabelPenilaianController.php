<?php

namespace App\Modules\DosenTabelPenilaian\Controllers;

use App\Modules\Controller;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\NilaiKriteria;
use App\Models\Penjadwalan;
use App\Models\AlokasiDosen;
use App\Models\PengajuanPembimbing;
use App\Models\Kota;
use App\Models\Mahasiswa;
use Carbon\Carbon;
Carbon::setLocale('id');


use Illuminate\Support\Facades\Auth;

class DosenTabelPenilaianController extends Controller
{
    public function index()
    {
        $nip = Auth::user()->dosen->nip; // Asumsi NIP dosen disimpan di kolom `username` pada tabel `users`

        // Cari kota yang dibimbing atau diuji oleh dosen tersebut
        $kotaDibimbing = AlokasiDosen::where('nip', $nip)
            ->with(['pengajuanPembimbing.kota.penjadwalan', 
                'pengajuanPembimbing.kota.mahasiswa',
                ])
            ->get()
            ->pluck('pengajuanPembimbing.kota.penjadwalan')
            ->flatten()
            ->unique('id_penjadwalan'); // Pastikan tidak ada duplikasi penjadwalan

        // Format data penjadwalan
        $penjadwalan = $kotaDibimbing->map(function ($item) use ($nip) {
            $mahasiswa = $item->kota->mahasiswa ?? collect([]);
            $sudahDinilai = $mahasiswa->contains(function ($mahasiswa) use ($nip) {
                return $mahasiswa->nilaiKategori->where('nip', $nip)->isNotEmpty();
            });
            $statusPenilaian = $mahasiswa->flatMap(function ($mhs) use ($nip) {
                return $mhs->nilaiKategori->where('nip', $nip)->pluck('status_penilaian_dosen');
            })->unique()->first();
            return [
                'id_penjadwalan' => $item->id_penjadwalan,
                'sesi' => $item->sesi,
                'agenda' => match ($item->agenda) {
                    'seminar_3' => 'Seminar 3',
                    'sidang' => 'Sidang Akhir',
                },
                'tanggal' => Carbon::parse($item->tanggal)->translatedFormat('d F Y'),
                'judul' => $item->kota->judul_ta,
                'kota' => $item->kota->nama_kota, // Asumsi ada relasi ke tabel `kota`
                'id_kota' => $item->kota->id_kota,
                'status' => $sudahDinilai ? 'Sudah dinilai' : 'Belum dinilai',
                'status_penilaian' => $statusPenilaian,
                'namaFta' => match ($item->agenda) {
                    'seminar_3' => 'seminar-iii',
                    'sidang' => 'sidang-akhir',
                },
                'id_prodi' => $mahasiswa->first()->id_prodi,
            ];
        });

        // Kembalikan response JSON
        return view('DosenTabelPenilaian.views.view', compact('penjadwalan'));
    }
}