<?php

namespace App\Modules\KelolaPenilaianTA\Controllers;

use App\Modules\Controller;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

use App\Models\Mahasiswa;
use App\Models\Kota;
use App\Models\KriteriaPenilaian;
use App\Models\KategoriPenilaian;
use App\Models\AspekFeedback;
use App\Models\DetailFeedback;
use App\Models\FormPenilaian;
use App\Models\Penjadwalan;
use App\Models\Dokumen;

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
        $namaFtaSlug = Str::slug($namaFta, ' ');

        $keteranganUmumPenilaian = Kota::where('id_kota', $idKota)
            ->with('penjadwalan', 'mahasiswa.user')
            ->first();

        // Konversi namaFta ke format database
        $namaAgenda = $this->konversiNamaAgenda($namaFta);

        // Ambil jadwal langsung filter di query
        $jadwal = Penjadwalan::where('id_kota', $idKota)
            ->where('agenda', $namaAgenda)
            ->where('status', 'fix')
            ->select('tanggal', 'start', 'end', 'agenda')
            ->first();
       
        // Ambil informasi seminar
        $seminar = FormPenilaian::where('nama_fta', $namaFtaSlug)
            ->where('id_prodi', $idProdi)
            ->where('jenis_form', 'feedback')
            ->with('kategoriPenilaian')
            ->first();
    
        // Ambil data mahasiswa berdasarkan id_kota
        $mahasiswa = Mahasiswa::where('id_kota', $idKota)
            ->with([
                'user', // Relasi ke tabel user
                'kota.penjadwalan' // Relasi ke penjadwalan melalui kota
            ])
            ->get();
    
        // Ambil aspek feedback berdasarkan nama FTA dan id_prodi
        $aspekFeedback = AspekFeedback::whereHas('formPenilaian', function ($query) use ($namaFtaSlug, $idProdi) {
            $query->where('nama_fta', $namaFtaSlug)
                  ->where('id_prodi', $idProdi)
                  ->where('jenis_form', 'feedback');
        })->get();
    
        // Siapkan data untuk dikirim ke view
        $data = [
            'kode_fta' => $seminar->kode_fta ?? null,
            'namaFta' => $namaFtaSlug,
            'namaKota' => $mahasiswa->first()->kota->nama_kota ?? null,
            'id_kota' => $idKota,
            'aspekFeedback' => $aspekFeedback
        ];

        $nip = auth()->user()->username;

        // Ambil feedback yang sudah diisi dosen ini untuk FTA dan kota terkait
        $detailFeedback = DetailFeedback::whereIn('id_feedback', $aspekFeedback->pluck('id_feedback'))
            ->where('id_kota', $idKota)
            ->where('nip', $nip)
            ->get()
            ->keyBy('id_feedback'); // agar bisa diakses dengan mudah di Blade

        $data['detailFeedback'] = $detailFeedback;

        $dokumen = $this->getLatestDokumenByKota($idKota, $namaFta);
    
        return view('KelolaPenilaianTA.views.pemberian-nilai-dan-feedback.formulir_masukan', compact('seminar', 'mahasiswa', 'aspekFeedback', 'data', 'keteranganUmumPenilaian', 'keteranganUmumPenilaian', 'jadwal', 'detailFeedback', 'dokumen'));
    }

    /**
     * Konversi nama FTA ke format yang sesuai dengan database
     * 
     * @param string $namaFta
     * @return string|null
     */
    private function konversiNamaAgenda($namaFta)
    {
        $namaFtaLower = strtolower($namaFta);

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
        $kategori = strtolower($kategori);

        // Mapping manual supaya sesuai format database
        $kategori = match ($kategori) {
            'seminar-i' => 'seminar1',
            'seminar-ii' => 'seminar2',
            'seminar-iii' => 'seminar3',
            'sidang-akhir' => 'sidang',
            default => $kategori
        };

        $laporan = Dokumen::where('id_kota', $idKota)
            ->where('kategori', $kategori)
            ->where('id_subkategori', 1) // Laporan
            ->orderByDesc('versi')
            ->first();

        $powerpoint = Dokumen::where('id_kota', $idKota)
            ->where('kategori', $kategori)
            ->where('id_subkategori', 3) // PowerPoint
            ->orderByDesc('versi')
            ->first();
        
        // LOG UNTUK DEBUG
        Log::info('Preview Dokumen:', [
            'id_kota' => $idKota,
            'kategori' => $kategori,
            'laporan_file_path' => optional($laporan)->file_path,
            'powerpoint_file_path' => optional($powerpoint)->file_path,
        ]);

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
        $namaFtaSlug = Str::slug($namaFta, ' ');
        $nip = auth()->user()->username;
        $action = $request->form_action;
        $idKota = Kota::where('id_kota', $idKota)->first()->id_kota;
        $feedbacks = $request->input('feedback');

        $idFta = FormPenilaian::where('nama_fta', $namaFtaSlug)
            ->where('jenis_form', 'feedback')
            ->pluck('id_fta')
            ->first();

        if (!$idFta) {
            return redirect()->back()->with('error', 'Data tidak ditemukan.');
        }

        $idFeedbacks = AspekFeedback::where('id_fta', $idFta)
            ->pluck('id_feedback', 'nama_aspek_feedback')
            ->toArray();

        // Ambil masukan dari request
        $feedbacks = $request->input('feedback');
        $errorMessage = null;

        // Loop semua masukan dan cek kondisi validasi
        foreach ($feedbacks as $feedback) {
            $plainText = trim(strip_tags($feedback['masukan'] ?? ''));

            if ($plainText === '') {
                $errorMessage = "Masukan tidak boleh kosong.";
                break;
            }

            if (Str::length($plainText) < 15) {
                $errorMessage = "Masukan minimal 15 karakter.";
                // Jangan break dulu, simpan kalau belum ada error
                // Tapi kalau sudah ada error "kosong", ini tidak akan dijalankan
            }

            if (Str::length($plainText) > 100) {
                // Kalau belum ada error apapun, set ini
                if (!$errorMessage) {
                    $errorMessage = "Masukan maksimal 100 karakter.";
                }
            }
        }

        // Kalau ada error, tampilkan 1 saja
        if ($errorMessage) {
            return redirect()->back()
                ->withErrors(['feedback.masukan' => $errorMessage])
                ->withInput();
        }

        // Proses simpan
        DB::beginTransaction();

        try {
            foreach ($feedbacks as $feedback) {
                $idFeedback = $feedback['id_feedback'] ?? null;            

                if (!$idFeedback) continue;

                $existingFeedback = DetailFeedback::where('id_feedback', $idFeedback)
                    ->where('id_kota', $idKota)
                    ->where('nip', $nip)
                    ->first();

                if ($existingFeedback) {
                    $existingFeedback->update([
                        'isi_feedback' => $feedback['masukan'],
                        'status_penilaian_dosen' => in_array($namaFtaSlug, ['seminar i', 'seminar ii']) ? 'dipublikasikan' : 'draf',
                    ]);
                } else {
                    DetailFeedback::create([
                        'id_feedback' => $idFeedback,
                        'id_kota' => $idKota,
                        'nip' => $nip,
                        'status_penilaian_dosen' => in_array($namaFtaSlug, ['seminar i', 'seminar ii']) ? 'dipublikasikan' : 'draf',
                        'isi_feedback' => $feedback['masukan'],
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('kelola.penilaian')->with('success', 'Masukan berhasil disimpan.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Gagal menyimpan masukan: " . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan masukan.');
        }
    }
}
