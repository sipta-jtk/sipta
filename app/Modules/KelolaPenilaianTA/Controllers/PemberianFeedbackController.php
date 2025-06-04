<?php

namespace App\Modules\KelolaPenilaianTA\Controllers;

use App\Modules\Controller;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

use App\Models\AlokasiDosen;
use App\Models\Mahasiswa;
use App\Models\Kota;
use App\Models\KriteriaPenilaian;
use App\Models\KategoriPenilaian;
use App\Models\AspekFeedback;
use App\Models\DetailFeedback;
use App\Models\FormPenilaian;
use App\Models\Penjadwalan;
use App\Models\Dokumen;
use App\Models\SubkategoriDokumen;
use App\Models\KotaUser;
use App\Models\User;

use App\Exports\RekapitulasiNilaiExport;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\FromCollection;

use App\Notifications\TestEmailNotification;

class PemberianFeedbackController extends Controller
{
    /**
     * Cek aksesibilitas pemberian feedback berdasarkan idKota
     * 
     * @param int $idKota
     */
    public function cekAksebilitasFeedback($idKota)
    {
        $nip = Auth::user()->dosen->nip;
    
        // Ambil semua id_kota yang boleh diakses dosen ini
        $kotaDiuji = AlokasiDosen::where('nip', $nip)
            ->where('status_alokasi', 'fix')
            ->with([
                'pengajuanPembimbing.kota'
            ])
            ->get()
            ->pluck('pengajuanPembimbing.kota')
            ->flatten()
            ->unique('id_kota');
    
        $bolehAkses = $kotaDiuji->contains(function ($kota) use ($idKota) {
            return $kota && $kota->id_kota == $idKota;
        });
    
        if (!$bolehAkses) {
            abort(403, 'Anda tidak memiliki akses untuk memberikan masukan kota ini');
        }
    }

    /**
     * Cek apakah feedback sudah terkunci untuk FTA tertentu
     * 
     * @param string $namaFta
     * @param int $idKota
     * @param string $nip
     */
    private function cekAksesFeedbackTerkunci($namaFta, $idKota, $nip)
    {
        $username = auth()->user()->nip;

        $namaFta = match (strtolower($namaFta)) {
            'seminar_3', 'seminar 3', 'seminar-iii' => 'Seminar III',
            'sidang', 'sidang-akhir' => 'Sidang Akhir',
            default => ucfirst(str_replace('_', ' ', $namaFta ?? 'Seminar')),
        };

        // belum tau kalo ini seminar atau sidang
        $kota = Kota::where('id_kota', $idKota)
            ->with('mahasiswa')
            ->with(['detailFeedback' => function ($query) use ($nip, $idKota) {
            $query->where('nip', $nip)
                  ->where('id_kota', $idKota)
                  ->where('status_penilaian_dosen', 'dipublikasikan');
            }])
            ->with(['detailFeedback.aspekFeedback.formPenilaian' => function ($query) use ($namaFta) {
                $query->where('nama_fta', $namaFta)
                      ->where('jenis_form', 'feedback');
            }])
            ->first();
        
        $idProdi = $kota->mahasiswa->first()->id_prodi;
        
        $jenis_ta = $kota->jenis_ta;

        // Cari form penilaian feedback berdasarkan nama, prodi, dan jenis_form = feedback
        $formPenilaian = FormPenilaian::where([
            ['nama_fta', $namaFta],
            ['id_prodi', $idProdi],
            ['jenis_form', 'penilaian'],
            ['jenis_ta', $jenis_ta]
        ])->with('kategoriPenilaian')
        ->first();

        // Cek apakah feedback sudah ada
        $feedback = $kota->detailFeedback->isEmpty();
        
        // ini untuk cek apakah dikunci atau tidak
        if ($formPenilaian->kategoriPenilaian->first()->kunci_penilaian && $feedback) {
            abort(403, "Form penilaian untuk $namaFta dikunci.");
        }
    }

    /**
     * Cek apakah jadwal penilaian sudah dimulai
     * 
     * @param int $idKota
     * @param string $namaFta
     */
    private function cekAksesJadwalDimulai($idKota, $namaFta)
    {
        Log::info("Cek akses jadwal dimulai untuk kota ID: $idKota, nama FTA: $namaFta");
        // Mapping namaFta ke format agenda di database
        $agenda = match (strtolower($namaFta)) {
            'seminar-iii' => 'seminar_3',
            'sidang-akhir' => 'sidang',
            default => $namaFta,
        };
    
        $jadwalQuery = Kota::where('id_kota', $idKota)
            ->with(['penjadwalan' => function ($q) use ($agenda) {
                if ($agenda) {
                    $q->where(function ($query) use ($agenda) {
                        $query->where('agenda', $agenda);
                    });
                }
            }])
            ->first();
    
        $penjadwalan = $jadwalQuery->penjadwalan->first();
        $start = optional($penjadwalan)->start;
        $agenda = optional($penjadwalan)->agenda ?? $agenda;
    
        // Mapping agenda ke label user-friendly
        $jenisSeminar = match (strtolower($agenda)) {
            'seminar_3', 'seminar 3', 'seminar-3' => 'Seminar III',
            'sidang' => 'Sidang Akhir',
            default => ucfirst(str_replace('_', ' ', $agenda ?? 'Seminar')),
        };

        if (!$start) {
            abort(403, "Jadwal penilaian $jenisSeminar belum ditentukan.");
        }
    
        if (Carbon::now()->lt(Carbon::parse($start))) {
            abort(403, "Penilaian $jenisSeminar belum dapat dilakukan karena jadwal belum dimulai.");
        }
    }

    /**
     * Tampilkan halaman pengisian masukan seminar
     * 
     * @param string $namaFta
     * @param int $idKota
     * @return View
     */
    public function pengisianMasukanSeminar($namaFta, $idKota): View
    {
        $this->cekAksebilitasFeedback($idKota);
        $this->cekAksesJadwalDimulai($idKota, $namaFta);
        $this->cekAksesFeedbackTerkunci(
            $namaFta,
            $idKota, 
            auth()->user()->username
        );

        // Mengubah nama FTA menjadi slug
        $namaFtaSlug = Str::slug($namaFta, ' ');

        // Konversi nama FTA ke format yang sesuai dengan database
        $namaAgenda = $this->konversiNamaAgenda($namaFta);

        // Ambil data umum penilaian berdasarkan kota
        $keteranganUmumPenilaian = Kota::with('penjadwalan', 'mahasiswa.user')
            ->find($idKota);

        $idProdi = $keteranganUmumPenilaian->mahasiswa->first()->id_prodi;

        // Ambil jadwal seminar yang sudah fix berdasarkan kota dan agenda
        $jadwal = Penjadwalan::where([
            ['id_kota', $idKota],
            ['agenda', $namaAgenda],
            ['status', 'fix']
            ])
            ->select('tanggal', 'start', 'end', 'agenda')
            ->first();

        // Ambil data form penilaian seminar berdasarkan nama FTA, prodi, dan jenis form
        $seminar = FormPenilaian::with('kategoriPenilaian')
            ->where([
            ['nama_fta', $namaFtaSlug],
            ['id_prodi', $idProdi],
            ['jenis_form', 'feedback']
            ])
            ->first();

        // Ambil data mahasiswa yang terkait dengan kota
        $mahasiswa = Mahasiswa::with(['user', 'kota.penjadwalan'])
            ->where('id_kota', $idKota)
            ->get();

        // Ambil aspek feedback yang terkait dengan form penilaian
        $aspekFeedback = AspekFeedback::whereHas('formPenilaian', function ($query) use ($namaFtaSlug, $idProdi) {
            $query->where([
                ['nama_fta', $namaFtaSlug],
                ['id_prodi', $idProdi],
                ['jenis_form', 'feedback']
            ]);
            })
            ->get();

        // Ambil username dosen yang sedang login
        $nip = auth()->user()->username;

        // Ambil detail feedback yang sudah diberikan oleh dosen untuk aspek feedback tertentu
        $detailFeedback = DetailFeedback::whereIn('id_feedback', $aspekFeedback->pluck('id_feedback'))
            ->where([
            ['id_kota', $idKota],
            ['nip', $nip]
            ])
            ->get()
            ->keyBy('id_feedback');

        $view = $detailFeedback->every(function ($item) {
                return $item->status_penilaian_dosen === 'dipublikasikan';
            }) ? false : true;

        // Cek apakah semua feedback dosen untuk FTA ini sudah dipublikasikan
        $isPublished = true; // Asumsikan sudah dipublikasikan di awal
        if ($detailFeedback->isNotEmpty()) {
            foreach ($detailFeedback as $feedbackItem) {
                if ($feedbackItem->status_penilaian_dosen !== 'dipublikasikan') {
                    $isPublished = false;
                    break;
                }
            }
        } else {
            // Jika belum ada feedback, berarti belum dipublikasikan
            $isPublished = false;
        }

        // Ambil dokumen terbaru berdasarkan kota dan kategori
        $dokumen = $this->getLatestDokumenByKota($idKota, $namaFta);

        // Siapkan data untuk dikirim ke view
        $data = [
            'kode_fta' => $seminar->kode_fta ?? null,
            'namaFta' => $namaFtaSlug,
            'namaKota' => $mahasiswa->first()->kota->nama_kota ?? null,
            'id_kota' => $idKota,
            'aspekFeedback' => $aspekFeedback,
            'detailFeedback' => $detailFeedback
        ];

        // Render view dengan data yang sudah disiapkan
        return view('KelolaPenilaianTA.views.pemberian-nilai-dan-feedback.formulir_masukan', compact(
            'seminar', 
            'mahasiswa', 
            'aspekFeedback', 
            'data', 
            'keteranganUmumPenilaian', 
            'jadwal', 
            'detailFeedback', 
            'dokumen',
            'isPublished'
        ));
    }

    /**
     * Konversi nama FTA ke format yang sesuai dengan database
     * 
     * @param string $namaFta
     * @return string|null
     */
    private function konversiNamaAgenda($namaFta)
    {
        // Mengubah nama FTA menjadi huruf kecil
        $namaFtaLower = strtolower($namaFta);

        // Mapping manual supaya sesuai format database
        if ($namaFtaLower === 'seminar-i') {
            return 'seminar_1';
        } elseif ($namaFtaLower === 'seminar-ii') {
            return 'seminar_2';
        } elseif ($namaFtaLower === 'seminar-iii') {
            return 'seminar_3';
        } elseif ($namaFtaLower === 'sidang-akhir') {
            return 'sidang';
        } else {
            return null;
        }
    }

    /**
     * Ambil dokumen terbaru berdasarkan kota dan kategori
     * 
     * @param int $idKota
     * @param string $kategori
     * @return array
     */
    private function getLatestDokumenByKota($idKota, $kategori)
    {
        // Ubah kategori menjadi huruf kecil untuk konsistensi
        $kategori = strtolower($kategori);

        // Mapping manual kategori supaya sesuai dengan format di database
        $kategori = match ($kategori) {
            'seminar-i' => 'seminar1', // Seminar I
            'seminar-ii' => 'seminar2', // Seminar II
            'seminar-iii' => 'seminar3', // Seminar III
            'sidang-akhir' => 'sidang', // Sidang Akhir
            default => $kategori // Kategori lainnya
        };

        // Ambil dokumen laporan terbaru berdasarkan kota dan kategori
        $laporan = Dokumen::where('id_kota', $idKota)
            ->where('kategori', $kategori)
            ->where('id_subkategori', 1) // Subkategori 1: Laporan
            ->orderByDesc('versi') // Urutkan berdasarkan versi terbaru
            ->first();

        // Ambil dokumen PowerPoint terbaru berdasarkan kota dan kategori
        $powerpoint = Dokumen::where('id_kota', $idKota)
            ->where('kategori', $kategori)
            ->where('id_subkategori', 3) // Subkategori 3: PowerPoint
            ->orderByDesc('versi') // Urutkan berdasarkan versi terbaru
            ->first();

        // Kembalikan dokumen laporan dan PowerPoint dalam bentuk array
        return [
            'laporan' => $laporan,
            'powerpoint' => $powerpoint
        ];
    }

    /**
     * Simpan masukan seminar
     * 
     * @param Request $request
     * @param string $namaFta
     * @param int $idKota
     * @return \Illuminate\Http\RedirectResponse
     */ 
    public function simpanMasukanSeminar(Request $request, $namaFta, $idKota)
    {
        // Ubah nama FTA menjadi slug
        $namaFtaSlug = Str::slug($namaFta, ' ');

        // Ambil username dosen yang sedang login
        $nip = auth()->user()->username;

        // Ambil action dari form
        $action = $request->form_action;

        // Ambil ID kota berdasarkan input
        $idKota = Kota::where('id_kota', $idKota)->first()->id_kota;

        // Ambil data feedback dari request
        $feedbacks = $request->input('feedback');

        // Cari ID FTA berdasarkan nama FTA dan jenis form
        $idFta = FormPenilaian::where('nama_fta', $namaFtaSlug)
            ->where('jenis_form', 'feedback')
            ->pluck('id_fta')
            ->first();

        // Jika ID FTA tidak ditemukan, kembalikan error
        if (!$idFta) {
            return redirect()->back()->with('error', 'Data tidak ditemukan.');
        }

        // Ambil ID feedback berdasarkan ID FTA
        $idFeedbacks = AspekFeedback::where('id_fta', $idFta)
            ->pluck('id_feedback', 'nama_aspek_feedback')
            ->toArray();

        // Validasi masukan dari request
        $errorMessage = null;
        foreach ($feedbacks as $feedback) {
            $plainText = trim(strip_tags($feedback['masukan'] ?? ''));

            if ($plainText === '') {
                $errorMessage = "Masukan tidak boleh kosong.";
                break;
            }

            $wordCount = str_word_count($plainText);
            if ($wordCount < 5) {
                $errorMessage = "Masukan minimal 5 kata.";
                break;
            }
        }

        // Jika ada error validasi, kembalikan ke halaman sebelumnya
        if ($errorMessage) {
            return redirect()->back()
                ->withErrors(['feedback.masukan' => $errorMessage])
                ->withInput();
        }

        // Mulai transaksi database
        DB::beginTransaction();

        try {
            // Loop melalui semua feedback untuk disimpan
            foreach ($feedbacks as $feedback) {
                $idFeedback = $feedback['id_feedback'] ?? null;

                // Jika ID feedback tidak ada, lewati
                if (!$idFeedback) continue;

                // Cek apakah feedback sudah ada di database
                $existingFeedback = DetailFeedback::where('id_feedback', $idFeedback)
                    ->where('id_kota', $idKota)
                    ->where('nip', $nip)
                    ->first();

                if ($existingFeedback) {
                    // Update feedback yang sudah ada
                    $existingFeedback->update([
                        'isi_feedback' => $feedback['masukan'],
                        'status_penilaian_dosen' => in_array($namaFtaSlug, ['seminar i', 'seminar ii']) ? 'dipublikasikan' : 'draf',
                    ]);
                } else {
                    // Buat feedback baru
                    DetailFeedback::create([
                        'id_feedback' => $idFeedback,
                        'id_kota' => $idKota,
                        'nip' => $nip,
                        'status_penilaian_dosen' => in_array($namaFtaSlug, ['seminar i', 'seminar ii']) ? 'dipublikasikan' : 'draf',
                        'isi_feedback' => $feedback['masukan'],
                    ]);
                }
            }

            // Ke MHS
            // try {
            //     if ($idKota) {
            //         $mahasiswa = KotaUser::where('id_kota', $idKota)->pluck('username');
            //         $namaMhs = User::where('username', $mahasiswa)->value('nama');
            //         $judulTA = Kota::where('id_kota', $idKota)->value('judul_ta');
            //         foreach ($mahasiswa as $username) {
            //             if ($username) {
            //                 $username->notify(new TestEmailNotification(
            //                     'Penilaian Telah Dilakukan, Periksa Feedback Dosen!',
            //                     [
            //                         'nama' => $namaMhs,
            //                         'topik' => $judulTA,
            //                         'feedback_dokumen' => $feedback['masukan'],
            //                         'feedback_presentasi' => $feedback['masukan'],
            //                         'feed_penguasaan_materi' => $feedback['masukan']
            //                     ]
            //                 ));
            //             }
            //         }
            //     }
            // } catch (\Exception $notifEx) {
            //     \Log::error('Gagal mengirim notifikasi pemberian feedback: ' . $notifEx->getMessage(), [
            //         'id_kota' => $idKota,
            //         'username' => $username
            //     ]);
            // }
            // Commit transaksi jika berhasil
            DB::commit();

            $namaFtaSlug = str_replace(' ', '-', $namaFtaSlug);
            return redirect()->route('nilai.index', ['kegiatan' => $namaFtaSlug])
                ->with('success', 'Masukan berhasil disimpan.');
        } catch (\Exception $e) {
            // Rollback transaksi jika terjadi kesalahan
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan masukan.');
        }
    }

    /**
     * Mengunduh dokumen
     * @param string $kategori
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function download($kategori, $id)
    {
        try {
            $dokumen = Dokumen::where('id_dokumen', $id)->where('kategori', $kategori)->firstOrFail();

            if (!$dokumen->file_path || !Storage::disk('public')->exists($dokumen->file_path)) {
                return redirect()->route('Repository.index', $kategori)->with('error', 'File tidak ditemukan');
            }

            $extension = pathinfo(storage_path('app/public/' . $dokumen->file_path), PATHINFO_EXTENSION);
            $filename = $dokumen->judul . '-v' . $dokumen->versi . '.' . $extension;

            return response()->download(storage_path('app/public/' . $dokumen->file_path), $filename);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat mengunduh dokumen: ' . $e->getMessage());
        }
    }
}
