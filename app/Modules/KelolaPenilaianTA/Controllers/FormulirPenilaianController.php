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
use Illuminate\Support\Facades\Log;

class FormulirPenilaianController extends Controller {

    public function getFormPenilaian()
    {
        $data = DB::table('form_penilaian')
            ->select('kode_fta', 'nama_fta', 'id_prodi', 'jenis_form', 'tanggal_tenggat_pengisian')
            ->get();

        $data = FormPenilaian::all();
        return view('KelolaPenilaianTA.views.formulir-penilaian.formulir_penilaian_ta', compact('data'));
    }

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

    public function tambahRubrikPenilaian(): View
    {
        $formPenilaianList = FormPenilaian::all(); // Ambil semua data Form Penilaian dari database
        $kriteriaList = KriteriaPenilaian::all(); // Ambil semua data Kriteria Penilaian dari database
        return view('KelolaPenilaianTA.views.formulir-penilaian.tambah_rubrik_penilaian', compact('formPenilaianList', 'kriteriaList'));
    }

    // public function edit($id)
    // {
    //     $aspek = AspekPenilaian::with(['penilaian', 'feedback'])->findOrFail($id);

    //     if ($aspek->jenisForm == 'Penilaian') {
    //         $aspek->penilaian = KriteriaPenilaian::where('kode_fta', $aspek->kodeFTA)->get();
    //     } else {
    //         $aspek->feedback = AspekFeedback::where('kode_fta', $aspek->kodeFTA)->get();
    //     }

    //     return view('KelolaPenilaianTA.ubah_aspek_penilaian', compact('aspek'));
    // }


    // public function simpanAspekFormulir(Request $request)
    // {
    //     // Validasi input
    //     $request->validate([
    //         'kodeFTA' => 'required|string|max:255',
    //         'namaFTA' => 'required|string|max:255',
    //         'jenisForm' => 'required|string|max:255',
    //         'namaProdi' => 'required|string|max:255',
    //         'tanggalTenggat' => 'required|date',
    //         'kriteria' => 'required|array',
    //         'kriteria.*' => 'required|string|max:255',
    //     ]);

    //     // Cari ID Prodi berdasarkan nama prodi
    //     $idProdi = Prodi::where('nama_prodi', $request->namaProdi)->value('id_prodi');

    //     // Simpan data ke tabel form_penilaian
    //     $formPenilaian = FormPenilaian::create([
    //         'kode_fta' => $request->kodeFTA,
    //         'nama_fta' => $request->namaFTA,
    //         'id_prodi' => $idProdi,
    //         'jenis_form' => $request->jenisForm,
    //         'tanggal_tenggat_pengisian' => $request->tanggalTenggat,
    //     ]);

    //     // Simpan data ke tabel aspek_feedback
    //     foreach ($request->kriteria as $kriteria) {
    //         AspekFeedback::create([
    //             'kode_fta' => $formPenilaian->kode_fta,
    //             'nama_aspek_feedback' => $kriteria,
    //         ]);
    //     }

    //     return redirect()->route('formulir-penilaian.index')->with('success', 'Formulir penilaian berhasil ditambahkan.');
    // }

    // public function updateAspek(Request $request, $id)
    // {
    //     DB::beginTransaction();

    //     try {
    //         // Update data di tabel form_penilaian
    //         $formPenilaian = FormPenilaian::findOrFail($id);
    //         $formPenilaian->update([
    //             'kode_fta' => $request->kodeFTA,
    //             'nama_fta' => $request->namaFTA,
    //             'id_prodi' => $request->namaProdi,
    //             'jenis_form' => $request->jenisForm,
    //             'tanggal_tenggat_pengisian' => $request->tanggalTenggat,
    //         ]);

    //         // Hapus data lama di tabel kriteria_penilaian dan aspek_feedback
    //         KriteriaPenilaian::where('kode_fta', $formPenilaian->kode_fta)->delete();
    //         AspekFeedback::where('kode_fta', $formPenilaian->kode_fta)->delete();

    //         // Simpan data baru ke tabel kriteria_penilaian jika jenis form adalah "Penilaian"
    //         if ($request->jenisForm === 'Penilaian') {
    //             if ($request->has('nama_kriteria')) {
    //                 foreach ($request->nama_kriteria as $index => $kriteria) {
    //                     KriteriaPenilaian::create([
    //                         'kode_fta' => $formPenilaian->kode_fta,
    //                         'nama_kriteria' => $kriteria,
    //                         'bobot_kriteria' => $request->bobot_kriteria[$index] ?? null,
    //                     ]);
    //                 }
    //             }
    //         }

    //         // Simpan data baru ke tabel aspek_feedback jika jenis form adalah "Feedback"
    //         if ($request->jenisForm === 'Feedback') {
    //             if ($request->has('nama_aspek_feedback')) {
    //                 foreach ($request->nama_aspek_feedback as $kriteria) {
    //                     AspekFeedback::create([
    //                         'kode_fta' => $formPenilaian->kode_fta,
    //                         'nama_aspek_feedback' => $kriteria,
    //                     ]);
    //                 }
    //             }
    //         }

    //         DB::commit();
    //         return redirect()->route('formulir-penilaian.index')
    //             ->with('success', 'Formulir dan Aspek berhasil diperbarui.');

    //     } catch (\Exception $e) {
    //         DB::rollback(); // Batalkan perubahan jika ada error
    //         return redirect()->back()
    //             ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
    //     }
    // }

    // public function updateAspek(Request $request, $id)
    // {
    //     DB::beginTransaction();

    //     try {
    //         // Update data di tabel form_penilaian
    //         $formPenilaian = FormPenilaian::findOrFail($id);
    //         $formPenilaian->update([
    //             'kode_fta' => $request->kodeFTA,
    //             'nama_fta' => $request->namaFTA,
    //             'id_prodi' => $request->namaProdi,
    //             'jenis_form' => $request->jenisForm,
    //             'tanggal_tenggat_pengisian' => $request->tanggalTenggat,
    //         ]);

    //         // Hapus data lama di tabel kriteria_penilaian dan aspek_feedback
    //         KriteriaPenilaian::where('kode_fta', $formPenilaian->kode_fta)->delete();
    //         AspekFeedback::where('kode_fta', $formPenilaian->kode_fta)->delete();

    //         // Simpan data baru ke tabel kriteria_penilaian jika jenis form adalah "Penilaian"
    //         if ($request->jenisForm === 'Penilaian') {
    //             if ($request->has('nama_kriteria')) {
    //                 foreach ($request->nama_kriteria as $index => $kriteria) {
    //                     KriteriaPenilaian::create([
    //                         'kode_fta' => $formPenilaian->kode_fta,
    //                         'nama_kriteria' => $kriteria,
    //                         'bobot_kriteria' => $request->bobot_kriteria[$index] ?? null,
    //                     ]);
    //                 }
    //             }
    //         }

    //         // Simpan data baru ke tabel aspek_feedback jika jenis form adalah "Feedback"
    //         if ($request->jenisForm === 'Feedback') {
    //             if ($request->has('nama_aspek_feedback')) {
    //                 foreach ($request->nama_aspek_feedback as $kriteria) {
    //                     AspekFeedback::create([
    //                         'kode_fta' => $formPenilaian->kode_fta,
    //                         'nama_aspek_feedback' => $kriteria,
    //                     ]);
    //                 }
    //             }
    //         }

    //         DB::commit();
    //         return redirect()->route('formulir-penilaian.index')
    //             ->with('success', 'Formulir dan Aspek berhasil diperbarui.');

    //     } catch (\Exception $e) {
    //         DB::rollback(); // Batalkan perubahan jika ada error
    //         return redirect()->back()
    //             ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
    //     }
    // }

    // public function ubahAspek($id)
    // {
    //     // Get the form data
    //     $formPenilaian = FormPenilaian::where('kode_fta', $id)->firstOrFail();
    //     $prodiList = Prodi::all();
        
    //     // Create the aspek object structure expected by the view
    //     $aspek = (object)[
    //         'id' => $formPenilaian->kode_fta,
    //         'kodeFTA' => $formPenilaian->kode_fta,
    //         'namaFTA' => $formPenilaian->nama_fta,
    //         'jenisForm' => $formPenilaian->jenis_form,
    //         'namaProdi' => $formPenilaian->prodi->nama_prodi,
    //         'tanggalTenggat' => $formPenilaian->tanggal_tenggat_pengisian,
    //         'waktuTenggat' => $formPenilaian->waktu_tenggat_pengisian ?? '23:59',
    //     ];
        
    //     // Get related criteria based on form type
    //     if ($formPenilaian->jenis_form === 'Penilaian') {
    //         $aspek->penilaian = KriteriaPenilaian::where('kode_fta', $id)->get();
    //         $aspek->feedback = []; // Empty array to avoid null errors
    //     } else {
    //         $aspek->feedback = AspekFeedback::where('kode_fta', $id)->get();
    //         $aspek->penilaian = []; // Empty array to avoid null errors
    //     }
        
    //     return view('KelolaPenilaianTA.views.formulir-penilaian.ubah_aspek_penilaian', 
    //         compact('aspek', 'prodiList'));
    // }

    public function ubahAspek($id)
    {
        // Get the form data with related penilaian
        $formPenilaian = FormPenilaian::with(['kriteriaPenilaian', 'aspekFeedback'])
            ->where('kode_fta', $id)
            ->firstOrFail();
        
        $prodiList = Prodi::all();
        
        // Create the aspek object structure expected by the view
        $aspek = (object)[
            'id' => $formPenilaian->kode_fta,
            'kodeFTA' => $formPenilaian->kode_fta,
            'namaFTA' => $formPenilaian->nama_fta,
            'jenisForm' => $formPenilaian->jenis_form,
            'namaProdi' => $formPenilaian->prodi->nama_prodi,
            'tanggalTenggat' => $formPenilaian->tanggal_tenggat_pengisian,
            // 'waktuTenggat' => $formPenilaian->waktu_tenggat_pengisian ?? '23:59',
            'penilaian' => $formPenilaian->kriteriaPenilaian,
            'feedback' => $formPenilaian->aspekFeedback
        ];
        
        // Debug - memastikan data ditemukan
        Log::info("Aspek Data: ", ['aspek' => $aspek, 'kriteria' => $formPenilaian->kriteriaPenilaian]);
        
        return view('KelolaPenilaianTA.views.formulir-penilaian.ubah_aspek_penilaian', 
            compact('aspek', 'prodiList'));
    }

    public function updateAspek(Request $request, $id)
    {
        // Validate the request
        $request->validate([
            'tanggalTenggat' => 'required|date',
            'bobot_kriteria.*' => 'required|integer|min:0|max:100',
        ]);

        // Find the form
        $formPenilaian = FormPenilaian::where('kode_fta', $id)->firstOrFail();

        // Update the form's deadline
        $formPenilaian->tanggal_tenggat_pengisian = $request->tanggalTenggat;
        $formPenilaian->save();

        // Check form type and update accordingly
        if ($formPenilaian->jenis_form == 'penilaian') {
            // Calculate total weight
            $totalBobot = array_sum($request->bobot_kriteria);

            // Check if total weight is 100
            if ($totalBobot != 100) {
                return redirect()->back()->withErrors(['bobot_kriteria' => 'Total bobot harus 100 persen. Saat ini: ' . $totalBobot . '%']);
            }

            // Delete existing criteria
            KriteriaPenilaian::where('kode_fta', $id)->delete();

            // Add new criteria
            $namaKriteria = $request->nama_kriteria;
            $bobotKriteria = $request->bobot_kriteria;

            if ($namaKriteria) {
                foreach ($namaKriteria as $index => $nama) {
                    // Skip empty rows
                    if (empty($nama)) {
                        continue;
                    }

                    KriteriaPenilaian::create([
                        'kode_fta' => $id,
                        'nama_kriteria' => $nama,
                        'bobot_kriteria' => $bobotKriteria[$index] ?? 0,
                    ]);
                }
            }
        } else {
            // Delete existing feedback aspects
            AspekFeedback::where('kode_fta', $id)->delete();

            // Add new feedback aspects
            $namaAspekFeedback = $request->nama_aspek_feedback;

            if ($namaAspekFeedback) {
                foreach ($namaAspekFeedback as $nama) {
                    // Skip empty rows
                    if (empty($nama)) {
                        continue;
                    }

                    AspekFeedback::create([
                        'kode_fta' => $id,
                        'nama_aspek_feedback' => $nama,
                    ]);
                }
            }
        }

        return redirect()->route('formulir-penilaian.index')
            ->with('success', 'Aspek penilaian berhasil diperbarui.');
    }


}