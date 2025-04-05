<?php

namespace App\Modules\KelolaPenilaianTA\Controllers;

use App\Modules\Controller;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

use App\Models\Mahasiswa;
use App\Models\Kota;
use App\Models\KriteriaPenilaian;
use App\Models\KategoriPenilaian;
use App\Models\AspekFeedback;
use App\Models\DetailFeedback;
use App\Models\FormPenilaian;

use App\Exports\RekapitulasiNilaiExport;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\FromCollection;

class PemberianFeedbackController extends Controller
{
    /**
     * Menampilkan halaman pengisian masukan seminar
     * 
     * @param string $namaFta
     * @param int $idKota
     */
    public function pengisianMasukanSeminar($namaFta, $idKota): View
    {
        $namaFtaSlug = Str::slug($namaFta, ' ');
        
        // Ambil informasi seminar
        $seminar = FormPenilaian::where('nama_fta', $namaFtaSlug)
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
    
        // Ambil aspek feedback berdasarkan id_fta
        $aspekFeedback = AspekFeedback::whereHas('formPenilaian', function ($query) use ($namaFtaSlug) {
            $query->where('nama_fta', $namaFtaSlug);
        })->get();
    
        // Siapkan data untuk dikirim ke view
        $data = [
            'kode_fta' => $seminar->kode_fta ?? null,
            'namaFta' => $namaFtaSlug,
            // 'tanggal' => $seminar->tanggal_tenggat_pengisian ?? null,
            // 'start' => $mahasiswa->first()->kota->penjadwalan[0]->start ?? null,
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
    
        return view('KelolaPenilaianTA.views.pemberian-nilai-dan-feedback.formulir_masukan', compact('seminar', 'mahasiswa', 'aspekFeedback', 'data'));
    }

    /**
     * Simpan feedback 
     */
    public function simpanMasukanSeminar(Request $request, $namaFta, $idKota)
    {   
        $namaFtaSlug = Str::slug($namaFta, ' ');

        $nip = auth()->user()->username; // Ambil NIP dosen yang login
        $action = $request->form_action;

        // Validasi data masukan
        $request->validate([
            'feedback' => 'required|array',
            'feedback.*.masukan' => 'required|string',
        ]);

        $idKota = Kota::where('id_kota', $idKota)->first()->id_kota;

        // Ambil semua masukan dari parameter request
        $feedbacks = $request->input('feedback');

        // Ambil id_fta berdasarkan nama_fta dan jenis_form = 'feedback'
        $idFta = FormPenilaian::where('nama_fta', $namaFtaSlug)
            ->where('jenis_form', 'feedback') // Pastikan hanya feedback
            ->pluck('id_fta')
            ->first(); // Ambil satu nilai yang cocok

        // Pastikan id_fta ditemukan
        if (!$idFta) {
            return redirect()->back()->with('error', 'Data tidak ditemukan.');
        }

        // Ambil semua id_feedback yang sesuai dengan id_fta
        $idFeedbacks = AspekFeedback::where('id_fta', $idFta)
            ->pluck('id_feedback', 'nama_aspek_feedback')
            ->toArray();

        // Buat array baru dengan array_merge
        $data = [];
        foreach ($feedbacks as $feedback) {
            if (isset($idFeedbacks[$feedback['nama_aspek_feedback']])) {
                $data[] = array_merge($feedback, [
                    'id_feedback' => $idFeedbacks[$feedback['nama_aspek_feedback']]
                ]);
            } else {
                Log::warning("Feedback dengan nama aspek '{$feedback['nama_aspek_feedback']}' tidak ditemukan.");
            }
        }

        // Mulai transaksi database
        DB::beginTransaction();

        try {
            // Simpan setiap masukan ke dalam database
            foreach ($data as $feedback) {
                $existingFeedback = DetailFeedback::where('id_feedback', $feedback['id_feedback'])
                    ->where('id_kota', $idKota)
                    ->where('nip', $nip)
                    ->first();
        
                if ($existingFeedback) {
                    // Update jika sudah ada
                    $existingFeedback->update([
                        'isi_feedback' => $feedback['masukan'],
                        'status_penilaian_dosen' => 'dipublikasikan', // update status juga kalau perlu
                    ]);
                } else {
                    // Insert jika belum ada
                    DetailFeedback::create([
                        'id_feedback' => $feedback['id_feedback'],
                        'id_kota' => $idKota,
                        'nip' => $nip,
                        'status_penilaian_dosen' => 'dipublikasikan',
                        'isi_feedback' => $feedback['masukan'],
                    ]);
                }
            }
        
            DB::commit();
        
            return redirect()->route('kelola.penilaian')->with('success', 'Masukan berhasil disimpan.');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error("Gagal menyimpan masukan: " . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan masukan.');
        }
    }
}
