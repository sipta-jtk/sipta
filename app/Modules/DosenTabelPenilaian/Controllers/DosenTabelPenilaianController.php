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


        $penjadwalan = $kotaDibimbing->map(function ($item) use ($nip, $idKategoriSemua, $idKategoriFeedback, $id_kategori) {
            $mahasiswa = $item->kota->mahasiswa ?? collect([]);

            $sudahDinilai = $mahasiswa->contains(function ($mahasiswa) use ($nip, $idKategoriSemua) {
                return $mahasiswa->nilaiKategori->whereIn('id_kategori', $idKategoriSemua)
                    ->where('nip', $nip)
                    ->isNotEmpty();
            });

            $statusPenilaian = $mahasiswa->flatMap(function ($mhs) use ($nip, $idKategoriSemua) {
                return $mhs->nilaiKategori->whereIn('id_kategori', $idKategoriSemua)
                    ->where('nip', $nip)
                    ->pluck('status_penilaian_dosen');
            })->unique()->first();

            $sudahFeedback = $mahasiswa->contains(function ($mhs) use ($nip, $idKategoriFeedback) {
                return $mhs->kota->detailFeedback->whereIn('id_feedback', $idKategoriFeedback)
                    ->where('nip', $nip)
                    ->isNotEmpty();
            });
          
            $statusFeedback = $mahasiswa->flatMap(function ($mhs) use ($nip, $idKategoriFeedback) {
                return $mhs->kota->detailFeedback->whereIn('id_feedback', $idKategoriFeedback)
                    ->where('nip', $nip)
                    ->pluck('status_penilaian_dosen');
            })->unique()->first();


            $id_prodi_mhs = $mahasiswa->first()->id_prodi;
            $jenis_ta = $mahasiswa->first()->kota->jenis_ta;

            $filteredKategori = $id_kategori->filter(function ($kategori) use ($id_prodi_mhs, $jenis_ta) {
                return $kategori->id_prodi == $id_prodi_mhs && $kategori->jenis_ta == $jenis_ta;
            });
            
            Log::info('Filtered Kategori: ' . json_encode($filteredKategori, JSON_PRETTY_PRINT));
            $isTerkunci = false;
            foreach ($filteredKategori as $filter) {
                if ($filter->kategoriPenilaian->first()->kunci_penilaian) {
                    $isTerkunci = true;
                }
            }
            return [
                'id_penjadwalan' => $item->id_penjadwalan,
                'sesi' => $item->sesi,
                'agenda' => match ($item->agenda) {
                    'seminar_3' => 'Seminar 3',
                    'sidang' => 'Sidang Akhir',
                },
                'kunci_penilaian' => $isTerkunci,
                'sudah_dibuka' => Carbon::now()->greaterThanOrEqualTo(Carbon::parse($item->start)),
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