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

use App\Exports\RekapitulasiNilaiExport;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\FromCollection;

class PemberianFeedbackController extends Controller
{
    /**
     * Tampilkan halaman pengisian masukan seminar
     * 
     * @param string $namaFta
     * @param int $idKota
     * @param int $idProdi
     * @return View
     */
    public function pengisianMasukanSeminar($namaFta, $idKota, $idProdi): View
    {
        // Mengubah nama FTA menjadi slug
        $namaFtaSlug = Str::slug($namaFta, ' ');

        // Konversi nama FTA ke format yang sesuai dengan database
        $namaAgenda = $this->konversiNamaAgenda($namaFta);

        // Ambil data umum penilaian berdasarkan kota
        $keteranganUmumPenilaian = Kota::with('penjadwalan', 'mahasiswa.user')
            ->find($idKota);

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
        Log::info('Mengambil dokumen terbaru untuk kota: ' . $idKota . ' dengan kategori: ' . $kategori);
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
            if ($wordCount < 30) {
                $errorMessage = "Masukan minimal 30 kata.";
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
            // Commit transaksi jika berhasil
            DB::commit();

            $namaFtaSlug = str_replace(' ', '-', $namaFtaSlug);
            return redirect()->route('nilai.index', ['kegiatan' => $namaFtaSlug])
                ->with('success', 'Masukan berhasil disimpan.');
        } catch (\Exception $e) {
            // Rollback transaksi jika terjadi kesalahan
            DB::rollBack();
            Log::error("Gagal menyimpan masukan: " . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan masukan.');
        }
    }

    /**
     * Mengunduh dokumen.
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
