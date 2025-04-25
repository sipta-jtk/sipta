<?php

namespace App\Modules\KelolaPenilaianTA\Controllers;

use App\Modules\Controller;
use App\Models\Mahasiswa;
use App\Models\Kota;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Exports\RekapitulasiNilaiExport;
use App\Models\AlokasiPembimbing;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\FromCollection;

class MonitoringNilaiMahasiswaController extends Controller{

     /**
     * Menampilkan halaman monitoring nilai mahasiswa
     */
    public function monitoringMahasiswa(): View
    {
        // Ambil mahasiswa berdasarkan nim = username
        $mahasiswa = DB::table('mahasiswa')->where('nim', auth()->user()->username)->first();

        $idKota = $mahasiswa->id_kota;
        $idProdi = $mahasiswa->id_prodi;

        // Ambil mahasiswa dengan id_kota yang sama
        $mahasiswaList = Mahasiswa::with('user')
            ->where('id_kota', $idKota)
            ->get();

        // Ambil informasi Kota berdasarkan id_kota
        $kotaInfo = Kota::where('id_kota', $idKota)->first();

        // Ambil nama dosen pembimbing dengan query yang lebih singkat
        $dosenPembimbing = DB::table('pengajuan_pembimbing')
            ->join('alokasi_dosen', 'pengajuan_pembimbing.id_pengajuan_pembimbing', '=', 'alokasi_dosen.id_pengajuan_pembimbing')
            ->join('dosen', 'alokasi_dosen.nip', '=', 'dosen.nip')
            ->join('user', 'dosen.nip', '=', 'user.username')
            ->where('pengajuan_pembimbing.id_kota', $idKota)
            ->where('alokasi_dosen.status_alokasi', 'fix')
            ->where('alokasi_dosen.tipe_alokasi', 'pembimbing')
            ->select('dosen.nip', 'user.nama as nama_dosen')
            ->get();

        // 1. Ambil semua kode_fta berdasarkan id_prodi mahasiswa
        $kodeFtaList = DB::table('form_penilaian')
            ->where('id_prodi', $idProdi)
            ->pluck('id_fta');

        // 2. Ambil semua id_fta dengan nama_fta unik (id_fta paling kecil untuk nama_fta yang sama)
        $namaFta = DB::table('form_penilaian')
            ->whereIn('id_fta', $kodeFtaList)
            ->selectRaw('MIN(id_fta) as id_fta, nama_fta')
            ->groupBy('nama_fta')
            ->orderBy('id_fta', 'asc')
            ->get();

        // 3. Ambil semua id_fta yang memiliki jenis_form = 'penilaian'
        $ftaPenilaianList = DB::table('form_penilaian')
            ->whereIn('id_fta', $kodeFtaList)
            ->where('id_prodi', $idProdi)
            ->where('jenis_form', 'penilaian')
            ->select('id_fta', 'nama_fta')
            ->get()
            ->keyBy('nama_fta');

        // 4. Ambil semua id_fta yang memiliki jenis_form = 'feedback'
        $ftaFeedbackList = DB::table('form_penilaian')
            ->whereIn('id_fta', $kodeFtaList)
            ->where('id_prodi', $idProdi)
            ->where('jenis_form', 'feedback')
            ->select('id_fta', 'nama_fta')
            ->get()
            ->keyBy('nama_fta');

        // $ftaWithFeedback = DB::table('aspek_feedback')
        //     ->join('detail_feedback', 'aspek_feedback.id_feedback', '=', 'detail_feedback.id_feedback')
        //     ->whereIn('aspek_feedback.id_fta', $ftaFeedbackList->pluck('id_fta')) // Hanya id_fta yang ada di ftaFeedbackList
        //     ->where('detail_feedback.status_penilaian_dosen', 'dipublikasikan')
        //     ->pluck('aspek_feedback.id_fta') // Ambil hanya id_fta
        //     ->unique()
        //     ->toArray(); // Konversi ke array
        
        $ftaWithFeedback = DB::table('aspek_feedback')
            ->join('detail_feedback', 'aspek_feedback.id_feedback', '=', 'detail_feedback.id_feedback')
            ->whereIn('aspek_feedback.id_fta', $ftaFeedbackList->pluck('id_fta'))
            ->where('detail_feedback.status_penilaian_dosen', 'dipublikasikan')
            ->where('detail_feedback.id_kota', $idKota) // filter tambahan penting
            ->pluck('aspek_feedback.id_fta')
            ->unique()
            ->toArray();
        


        // 5. Ambil daftar nama FTA yang unik & urutkan berdasarkan kode_fta
        $ftaList = $namaFta->sortBy('id_fta')->pluck('nama_fta');

        // Kirimkan data ke tampilan Blade
        return view('KelolaPenilaianTA.views.monitoring-nilai-mahasiswa.monitoring_mahasiswa', compact(
            'mahasiswaList', 'kotaInfo', 'dosenPembimbing', 'kodeFtaList', 'idProdi', 'ftaPenilaianList', 'ftaFeedbackList', 'ftaList', 'ftaWithFeedback'
        ));
    }


    /**
     * Menampilkan halaman monitoring feedback
     * 
     */
    public function monitoringFeedback($idFta, $idKota): View
    {
        // Ambil mahasiswa berdasarkan nim = username
        $mahasiswa = DB::table('mahasiswa')->where('nim', auth()->user()->username)->first();

        if (!$mahasiswa) {
            abort(404, "Data mahasiswa tidak ditemukan.");
        }

        $idKota = $mahasiswa->id_kota;
        $idProdi = $mahasiswa->id_prodi;

        // Ambil informasi Kota berdasarkan id_kota
        $kotaInfo = Kota::where('id_kota', $idKota)->first();

        // Ambil mahasiswa dengan id_kota yang sama (anggota kelompok)
        $mahasiswaList = DB::table('mahasiswa')
            ->join('user', 'mahasiswa.nim', '=', 'user.username')
            ->where('mahasiswa.id_kota', $idKota)
            ->select('mahasiswa.nim', 'user.nama')
            ->get();

        // Ambil dosen pembimbing
        $dosenPembimbing = DB::table('pengajuan_pembimbing')
            ->join('alokasi_dosen', 'pengajuan_pembimbing.id_pengajuan_pembimbing', '=', 'alokasi_dosen.id_pengajuan_pembimbing')
            ->join('dosen', 'alokasi_dosen.nip', '=', 'dosen.nip')
            ->join('user', 'dosen.nip', '=', 'user.username')
            ->where('pengajuan_pembimbing.id_kota', $idKota)
            ->where('alokasi_dosen.status_alokasi', 'fix')
            ->where('alokasi_dosen.tipe_alokasi', 'pembimbing')
            ->select('dosen.nip', 'user.nama as nama_dosen')
            ->get();

        // Ambil data FTA yang sesuai dengan kodeFta yang dipilih
        $ftaPenilaian = DB::table('form_penilaian')
            ->where('id_fta', $idFta)
            ->select('id_fta', 'nama_fta', 'id_prodi')
            ->first();

        if (!$ftaPenilaian) {
            abort(404, "FTA tidak ditemukan.");
        }

        // Cari kode_fta yang memiliki nama_fta & id_prodi yang sama, tetapi jenis_form = 'feedback'
        $ftaFeedback = DB::table('form_penilaian')
            ->where('nama_fta', $ftaPenilaian->nama_fta)
            ->where('id_prodi', $ftaPenilaian->id_prodi)
            ->where('jenis_form', 'feedback')
            ->select('id_fta')
            ->first();

        if (!$ftaFeedback) {
            abort(404, "FTA untuk feedback tidak ditemukan.");
        }

        $kodeFtaFeedback = $ftaFeedback->id_fta; // Gunakan kodeFta yang baru ditemukan

        // Ambil aspek feedback berdasarkan kodeFta baru
        $aspekFeedback = DB::table('aspek_feedback')
            ->where('id_fta', $kodeFtaFeedback)
            ->select('id_feedback', 'nama_aspek_feedback')
            ->get();

        // Ambil detail feedback dengan kondisi status_penilaian = 'dipublikasikan'
        $detailFeedback = DB::table('detail_feedback')
            ->where('id_kota', $idKota)
            ->whereIn('id_feedback', $aspekFeedback->pluck('id_feedback'))
            ->where('status_penilaian_dosen', 'dipublikasikan')
            ->select('id_feedback', 'nip', 'isi_feedback')
            ->get();

        // Ambil dosen penguji dari feedback yang diberikan
        $nipPenguji = $detailFeedback->pluck('nip')->unique();

        $dosenPenguji = DB::table('dosen')
            ->join('user', 'dosen.nip', '=', 'user.username')
            ->whereIn('dosen.nip', $nipPenguji)
            ->select('dosen.nip', 'user.nama as nama_dosen')
            ->get();

        // Ambil penjadwalan sesuai dengan FTA yang diklik
        $agendaMapping = [
            'Seminar I' => 'seminar_1',
            'Seminar II' => 'seminar_2',
            'Seminar III' => 'seminar_3',
            'Sidang Akhir' => 'sidang'
        ];

        $agenda = $agendaMapping[$ftaPenilaian->nama_fta] ?? null;

        $penjadwalan = DB::table('penjadwalan')
            ->where('id_kota', $idKota)
            ->where('agenda', $agenda)
            ->orderBy('tanggal', 'desc')
            ->first();

        $hariTanggal = $penjadwalan ? $penjadwalan->tanggal : "Tanggal belum tersedia";

        Log::info($detailFeedback);

        // Kirim data ke tampilan
        return view('KelolaPenilaianTA.views.monitoring-nilai-mahasiswa.monitoring_feedback', compact(
            'mahasiswa', 'kotaInfo', 'mahasiswaList', 'dosenPembimbing', 'ftaPenilaian', 'hariTanggal', 'dosenPenguji',
            'aspekFeedback', 'detailFeedback'
        ));
    }   


    /**
     * Menampilkan halaman monitoring rubrik
     */
    public function monitoringRubrik($kodeFta, $idProdi): View
    {
        // Ambil nama_kategori dari kategori_penilaian berdasarkan kode_fta
        $kategori = DB::table('form_penilaian')
            ->where('id_fta', $kodeFta)
            ->select('nama_fta')
            ->first();

        // Ambil rentang nilai hanya untuk A, AB, B, BC, C, dan CD
        $rentangNilai = DB::table('rentang_nilai')
            ->whereIn('id_nilai', ['A', 'AB', 'B', 'BC', 'C', 'CD'])
            ->select('id_nilai', 'batas_atas', 'batas_bawah')
            ->orderBy('batas_atas', 'desc')
            ->get();

        // Ambil data kriteria beserta rubriknya
        $namaKriteria = DB::table('kriteria_penilaian')
            ->where('id_fta', $kodeFta)
            ->select('id_kriteria', 'nama_kriteria', 'bobot_kriteria')
            ->get()
            ->map(function ($kriteria) use ($rentangNilai) {
                // Ambil rubrik berdasarkan id_kriteria
                $kriteria->rubrik = DB::table('rubrik')
                    ->where('id_kriteria', $kriteria->id_kriteria)
                    ->select('id_rubrik', 'nama_rubrik')
                    ->get()
                    ->map(function ($rubrik) use ($rentangNilai) {
                        // Ambil detail rubrik berdasarkan id_rubrik dan id_nilai
                        $rubrik->detail = collect();
                        foreach ($rentangNilai as $nilai) {
                            $deskripsi = DB::table('detail_rubrik')
                                ->where('id_rubrik', $rubrik->id_rubrik)
                                ->where('id_nilai', $nilai->id_nilai)
                                ->select('detail_rubrik_penilaian')
                                ->first();
                            $rubrik->detail[$nilai->id_nilai] = $deskripsi->detail_rubrik_penilaian ?? '-';
                        }
                        return $rubrik;
                    });

                return $kriteria;
            });

        return view('KelolaPenilaianTA.views.monitoring-nilai-mahasiswa.monitoring_rubrik', compact('kategori', 'rentangNilai', 'namaKriteria'));
    }

}