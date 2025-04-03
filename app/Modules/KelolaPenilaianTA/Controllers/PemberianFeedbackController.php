<?php

namespace App\Modules\KelolaPenilaianTA\Controllers;

use App\Modules\Controller;
use App\Models\Mahasiswa;
use App\Models\Kota;
use App\Models\KriteriaPenilaian;
use App\Models\KategoriPenilaian;
use App\Models\AspekFeedback;
use App\Models\DetailFeedback;
use App\Models\FormPenilaian;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Exports\RekapitulasiNilaiExport;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Support\Facades\Log;

class PemberianFeedbackController extends Controller
{
    public function pengisianMasukanSeminar($id, $kota): View
    {
        $seminar = KategoriPenilaian::where('id_fta', $id)
            ->with('formulirPenilaian')
            ->first();

        $mahasiswa = Mahasiswa::where('id_kota', $kota)
            ->with('user', 'kota.penjadwalan')
            ->get();

        $aspekFeedback = AspekFeedback::where('id_fta', $id)->get();

        $data = [
            'kode_fta' => $seminar->formulirPenilaian->kode_fta,
            'tanggal' => $seminar->formulirPenilaian->tanggal_tenggat_pengisian,
            'start' => date('H:i', strtotime($mahasiswa->first()->kota->penjadwalan[0]->start)),
            'kota' => $mahasiswa->first()->kota->nama_kota,
            'id_kota' => $kota
        ];

        return view('KelolaPenilaianTA.views.pemberian-nilai-dan-feedback.pengisian_masukan_seminar_II', 
                    compact('seminar', 'mahasiswa', 'id', 'aspekFeedback', 'kota', 'data'));
    }

    /**
     * Helper mapping untuk data mahasiswa di form pengisian masukan seminar 2
     */
    public function mappingDataMahasiswaMasukan($kategoriPenilaian, $mahasiswa)
    {
        // Log::info($mahasiswa);
        Log::info(json_encode($mahasiswa, JSON_PRETTY_PRINT));
        $data = [
            'kode_fta' => $kategoriPenilaian->formulirPenilaian->kode_fta,
            'tanggal' => $kategoriPenilaian->formulirPenilaian->tanggal_tenggat_pengisian,
            'start' => date('H:i', strtotime($mahasiswa->first()->kota->penjadwalan[0]->start)),
            'kota' => $mahasiswa->first()->kota->nama_kota
        ];


        return $data;
    }

    /**
     * Simpan feedback berdasarkan seminar yang dipilih
     */
    public function simpanFeedback(Request $request, $seminar, $kota)
    {
        switch ($seminar) {
            case 1:
                return $this->simpanFeedbackSeminarI($request, $seminar, $kota);
            case 2:
                return $this->simpanFeedbackSeminarII($request, $seminar, $kota);
            case 3:
                return $this->simpanFeedbackSeminarIII($request, $kota);
            default:
                return $this->simpanFeedbackSidangAkhir($request, $kota);
        }
    }

    /**
     * Simpan feedback untuk Seminar 2
     */
    public function simpanMasukanSeminar(Request $request, $id, $kota)
    {
        if ($id > 1){
            $id_fta = $id + 1;
        }
        
        $nip = auth()->user()->username; // Ambil NIP dosen yang login
    
        // Validasi data masukan
        $request->validate([
            'feedback' => 'required|array',
            'feedback.*.masukan' => 'required|string',
        ]);
    
        $idKota = Kota::where('id_kota', $kota)->first()->id_kota;
    
        // Ambil semua masukan dari parameter request
        $feedbacks = $request->input('feedback');
    
        // Ambil semua id_feedback yang sesuai dengan id_fta dari URL
        $idFeedbacks = AspekFeedback::where('id_fta', $id_fta)
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
                DetailFeedback::create([
                    'id_feedback' => $feedback['id_feedback'],
                    'id_kota' => $idKota, // Kota tujuan dari URL
                    'nip' => $nip,
                    'status_penilaian_dosen' => 'draf', // Status default
                    'isi_feedback' => $feedback['masukan'], // Data feedback dari form
                ]);
            }
    
            // Commit transaksi jika semua berhasil
            DB::commit();
    
            $pengelolaanNilai = new PengelolaanNilaiController();
            return $pengelolaanNilai->detailNilaiMahasiswa($id);
            // return View
            // return redirect('kelola-penilaian-ta/pengelolaan-nilai')->with('success', 'Masukan berhasil disimpan.');
        } catch (\Exception $e) {
            // Rollback transaksi jika terjadi error
            DB::rollback();
    
            Log::error("Gagal menyimpan masukan: " . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan masukan.');
        }
    }
    
}
