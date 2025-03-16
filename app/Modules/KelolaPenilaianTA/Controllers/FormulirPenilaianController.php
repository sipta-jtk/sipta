<?php

namespace App\Modules\KelolaPenilaianTA\Controllers;

use App\Modules\Controller;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Exports\RekapitulasiNilaiExport;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Support\Facades\DB;
use App\Models\Prodi;
use App\Models\FormPenilaian;
use App\Models\KriteriaPenilaian;
use App\Models\AspekFeedback;

class FormulirPenilaianController extends Controller {

    public function getFormPenilaian()
    {
        $data = DB::table('form_penilaian')
            ->select('kode_fta', 'nama_fta', 'id_prodi', 'jenis_form', 'tanggal_tenggat_pengisian')
            ->get();

        return view('KelolaPenilaianTA.views.formulir-penilaian.formulir_penilaian_ta', compact('data'));
    }
    
    // public function formulirPenilaian(): View
    // {
    //     for ($i = 1; $i <= 100; $i++) {
    //         $data = [
    //             [
    //                 'kode' => 'FTA 04',
    //                 'nama' => 'Penilaian Seminar I',
    //             ],
    //             [
    //                 'kode' => 'FTA 07',
    //                 'nama' => 'Penilaian Semester II',
    //             ],
    //             [
    //                 'kode' => 'FTA 08',
    //                 'nama' => 'Masukan Seminar II',
    //             ],
    //             [
    //                 'kode' => 'FTA 011',
    //                 'nama' => 'Penilaian Semester III',
    //             ],
    //             [
    //                 'kode' => 'FTA 012',
    //                 'nama' => 'Masukan Seminar III',
    //             ],
    //         ];
    //     }

    //     return view('KelolaPenilaianTA.views.formulir-penilaian.formulir_penilaian_ta', compact('data'));
    // }

    public function tambahRubrikPenilaian(): View
    {
        return view('KelolaPenilaianTA.views.formulir-penilaian.tambah_rubrik_penilaian');
    }

    // public function tambahAspekFormulir(): View
    // {
    //     return view('KelolaPenilaianTA.views.formulir-penilaian.tambah_aspek_formulir');
    // }

    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            // Simpan ke tabel form_penilaian
            $formPenilaian = FormPenilaian::create([
                'kode_fta' => $request->kodeFTA,
                'nama_fta' => $request->namaFTA,
                'id_prodi' => $request->namaProdi,
                'jenis_form' => $request->jenisForm,
                'tanggal_tenggat_pengisian' => $request->tanggalTenggat,
            ]);

            // Simpan ke tabel kriteria_penilaian jika jenis form adalah "Penilaian"
            if ($request->jenisForm === 'Penilaian') {
                if ($request->has('nama_kriteria')) {
                    foreach ($request->nama_kriteria as $index => $kriteria) {
                        KriteriaPenilaian::create([
                            'kode_fta' => $formPenilaian->kode_fta,
                            'nama_kriteria' => $kriteria,
                            'bobot_kriteria' => $request->bobot_kriteria[$index] ?? null,
                        ]);
                    }
                }
            }

            // Simpan ke tabel aspek_feedback jika jenis form adalah "Feedback"
            if ($request->jenisForm === 'Feedback') {
                if ($request->has('nama_aspek_feedback')) {
                    foreach ($request->nama_aspek_feedback as $kriteria) {
                        AspekFeedback::create([
                            'kode_fta' => $formPenilaian->kode_fta,
                            'nama_aspek_feedback' => $kriteria,
                        ]);
                    }
                }
            }

            DB::commit();
            return redirect()->route('formulir-penilaian.index')
                ->with('success', 'Formulir dan Aspek berhasil ditambahkan.');

        } catch (\Exception $e) {
            DB::rollback(); // Batalkan perubahan jika ada error
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
    
    public function tambahAspekFormulir(): View
    {
        $prodiList = Prodi::all(); // Ambil semua data Prodi dari database
        return view('KelolaPenilaianTA.views.formulir-penilaian.tambah_aspek_formulir', compact('prodiList'));
    }

    public function simpanAspekFormulir(Request $request)
    {
        // Validasi input
        $request->validate([
            'kodeFTA' => 'required|string|max:255',
            'namaFTA' => 'required|string|max:255',
            'jenisForm' => 'required|string|max:255',
            'namaProdi' => 'required|string|max:255',
            'tanggalTenggat' => 'required|date',
            'kriteria' => 'required|array',
            'kriteria.*' => 'required|string|max:255',
        ]);

        // Cari ID Prodi berdasarkan nama prodi
        $idProdi = Prodi::where('nama_prodi', $request->namaProdi)->value('id_prodi');

        // Simpan data ke tabel form_penilaian
        $formPenilaian = FormPenilaian::create([
            'kode_fta' => $request->kodeFTA,
            'nama_fta' => $request->namaFTA,
            'id_prodi' => $idProdi,
            'jenis_form' => $request->jenisForm,
            'tanggal_tenggat_pengisian' => $request->tanggalTenggat,
        ]);

        // Simpan data ke tabel aspek_feedback
        foreach ($request->kriteria as $kriteria) {
            AspekFeedback::create([
                'kode_fta' => $formPenilaian->kode_fta,
                'nama_aspek_feedback' => $kriteria,
            ]);
        }

        return redirect()->route('formulir-penilaian.index')->with('success', 'Formulir penilaian berhasil ditambahkan.');
    }

    public function ubahFormulir()
    {
        // Data dummy untuk sementara
        $formulir = (object) [
            'kodeFTA' => 'FTA.011',
            'namaFTA' => 'PENILAIAN SEMINAR III',
            'tanggalTenggat' => '2025-03-05',
            'waktuTenggat' => '23:59',
            'aspekPenilaian' => [
                (object) [
                    'kriteria' => 'Kejelasan isi dokumen',
                    'bobot' => 40,
                    'detail' => 'Kejelasan kaitan antar bab/sub bab/kaitan (hubungan sebab akibat/ reasoning, rasionalitas)',
                    'lebih80' => 'Deskripsi sangat jelas dan rinci',
                    'tujuhPuluhLima' => 'Deskripsi cukup jelas namun ada bagian yang perlu diperjelas',
                    'tujuhPuluh' => 'Deskripsi kurang jelas dan perlu banyak perbaikan',
                    'enamPuluhLima' => 'Deskripsi tidak jelas dan membingungkan',
                    'enamPuluh' => 'Deskripsi tidak jelas dan membingungkan',
                    'kurang60' => 'Deskripsi tidak jelas dan membingungkan',
                ],
            ],
        ];

        return view('KelolaPenilaianTA.views.formulir-penilaian.ubah_formulir_ta', compact('formulir'));
    }

    public function ubahAspek($id): View
    {
        // Data dummy untuk aspek penilaian
        $aspek = (object) [
            'id' => $id,
            'kodeFTA' => 'FTA001',
            'namaFTA' => 'Nama FTA Dummy',
            'jenisForm' => 'Penilaian',
            'penilaian' => [
                (object) ['kriteria' => 'Kriteria 1', 'bobot' => 20],
                (object) ['kriteria' => 'Kriteria 2', 'bobot' => 30],
            ],
            'feedback' => [
                (object) ['kriteria' => 'Kriteria Feedback 1'],
                (object) ['kriteria' => 'Kriteria Feedback 2'],
            ],
            'tanggalTenggat' => '2025-03-15',
            'waktuTenggat' => '23:59',
        ];

        return view('KelolaPenilaianTA.views.formulir-penilaian.ubah_aspek_penilaian', compact('aspek'));
    }

    public function updateFormulir(Request $request)
    {
        return redirect()->route('formulir-penilaian.index')->with('success', 'Formulir penilaian berhasil diperbarui.');
    }

    public function updateAspek(Request $request)
    {
        // Logika untuk memperbarui aspek penilaian di database

        return redirect()->route('formulir-penilaian.index')->with('success', 'Aspek penilaian berhasil diperbarui.');
    }

}