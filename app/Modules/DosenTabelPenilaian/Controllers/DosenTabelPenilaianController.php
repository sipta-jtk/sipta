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
use App\Models\KategoriPenilaian;
use App\Models\FormPenilaian;
use App\Models\DetailFeedback;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
Carbon::setLocale('id');


use Illuminate\Support\Facades\Auth;

class DosenTabelPenilaianController extends Controller
{
    public function index($kegiatan)
    {
        $nip = Auth::user()->dosen->nip;

        //change kegiatan seminar-iii to Seminar III
        $kegiatanForm = match ($kegiatan) {
            'seminar-iii' => 'Seminar III',
            'sidang-akhir' => 'Sidang Akhir',
        };

        $id_kategori = FormPenilaian::where('nama_fta', $kegiatanForm)
            ->where('jenis_form', 'penilaian')
            ->with('kategoriPenilaian')
            ->get();

        $idKategoriSemua = [];
        foreach ($id_kategori as $item) {
            foreach ($item->kategoriPenilaian as $kategori) {
                $idKategoriSemua[] = $kategori->id_kategori;
            }
        }

        $id_feedback = FormPenilaian::where('nama_fta', $kegiatanForm)
            ->where('jenis_form', 'feedback')
            ->with('aspekFeedback.detailFeedback')
            ->get();
        
        $idKategoriFeedback = [];
        foreach ($id_feedback as $item) {
            foreach ($item->aspekFeedback as $feedback) {
                $idKategoriFeedback[] = $feedback->id_feedback;
            }
        }

        // Ambil hanya penjadwalan yang status-nya 'fix'
        $kotaDibimbing = AlokasiDosen::where('nip', $nip)
            ->with([
                'pengajuanPembimbing.kota.penjadwalan' => function ($query) use ($kegiatan) {
                    $query->where('status', 'fix')
                        ->where('agenda', $this->mappingKegiatan($kegiatan));
                },
                'pengajuanPembimbing.kota.mahasiswa',
            ])
            ->get()
            ->pluck('pengajuanPembimbing.kota.penjadwalan')
            ->flatten()
            ->unique('id_penjadwalan')
            ->filter(); // filter() untuk menghilangkan null jika tidak ada penjadwalan yang fix


        $penjadwalan = $kotaDibimbing->map(function ($item) use ($nip, $idKategoriSemua, $idKategoriFeedback) {
            // Log::info("item : " . json_encode($item, JSON_PRETTY_PRINT));
            $mahasiswa = $item->kota->mahasiswa ?? collect([]);
            // Log::info("mahasiswa : " . json_encode($mahasiswa, JSON_PRETTY_PRINT));

            $sudahDinilai = $mahasiswa->contains(function ($mahasiswa) use ($nip, $idKategoriSemua) {
                return $mahasiswa->nilaiKategori->whereIn('id_kategori', $idKategoriSemua)
                    ->where('nip', $nip)
                    ->isNotEmpty();
            });
            // Log::info("sudah dinilai : " . json_encode($sudahDinilai, JSON_PRETTY_PRINT));
            
            $statusPenilaian = $mahasiswa->flatMap(function ($mhs) use ($nip, $idKategoriSemua) {
                return $mhs->nilaiKategori->whereIn('id_kategori', $idKategoriSemua)
                    ->where('nip', $nip)
                    ->pluck('status_penilaian_dosen');
            })->unique()->first();
            // Log::info("status penilaian : " . json_encode($statusPenilaian, JSON_PRETTY_PRINT));

            $sudahFeedback = $mahasiswa->contains(function ($mhs) use ($nip, $idKategoriFeedback) {
                // Log::info("mhs : " . json_encode($mhs->kota->detailFeedback, JSON_PRETTY_PRINT));
                return $mhs->kota->detailFeedback->whereIn('id_feedback', $idKategoriFeedback)
                    ->where('nip', $nip)
                    ->isNotEmpty();
            });
            // Log::info("sudah feedback : " . json_encode($sudahFeedback, JSON_PRETTY_PRINT));
            
            $statusFeedback = $mahasiswa->flatMap(function ($mhs) use ($nip, $idKategoriFeedback) {
                return $mhs->kota->detailFeedback->whereIn('id_feedback', $idKategoriFeedback)
                    ->where('nip', $nip)
                    ->pluck('status_penilaian_dosen');
            })->unique()->first();
            // Log::info("status feedback : " . json_encode($statusFeedback, JSON_PRETTY_PRINT));

            return [
                'id_penjadwalan' => $item->id_penjadwalan,
                'sesi' => $item->sesi,
                'agenda' => match ($item->agenda) {
                    'seminar_3' => 'Seminar 3',
                    'sidang' => 'Sidang Akhir',
                },
                'tanggal' => Carbon::parse($item->tanggal)->translatedFormat('d F Y'),
                'judul' => $item->kota->judul_ta,
                'kota' => $item->kota->nama_kota,
                'id_kota' => $item->kota->id_kota,
                'sudah_penilaian' => $sudahDinilai ? 'Sudah dinilai' : 'Belum dinilai',
                'status_penilaian' => $statusPenilaian,
                'namaFta' => match ($item->agenda) {
                    'seminar_3' => 'seminar-iii',
                    'sidang' => 'sidang-akhir',
                },
                'id_prodi' => $mahasiswa->first()->id_prodi,
                'sudah_feedback' => $sudahFeedback ? 'Sudah diisi' : 'Belum diisi',
                'status_feedback' => $statusFeedback,
            ];
        });

        Log::info(json_encode($penjadwalan, JSON_PRETTY_PRINT));

        return view('DosenTabelPenilaian.views.view', compact('penjadwalan'));
    }

    private function mappingKegiatan($kegiatan) {
        return match ($kegiatan) {
            'seminar-iii' => 'seminar_3',
            'sidang-akhir' => 'sidang',
            default => null,
        };
    }
}