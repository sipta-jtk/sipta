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
use App\Models\RentangNilai;
use App\Models\KriteriaPenilaian;
use App\Models\AspekFeedback;
use Illuminate\Support\Facades\Log;

class FormulirPenilaianController extends Controller {

    public function getFormPenilaian()
    {
        $data = DB::table('form_penilaian')
            ->select('kode_fta', 'nama_fta', 'id_prodi', 'jenis_form', 'tanggal_tenggat_pengisian', 'id_fta', )
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
                'waktu_tenggat_pengisian' => $request->waktuTenggat,
            ]);

            // Get the newly created id_fta
            $id_fta = $formPenilaian->id_fta;

            // Simpan ke tabel kriteria_penilaian jika jenis form adalah "Penilaian"
            if ($request->jenisForm === 'Penilaian') {
                if ($request->has('nama_kriteria')) {
                    // Calculate total weight
                    $totalBobot = array_sum($request->bobot_kriteria);

                    // Check if total weight is 100
                    if ($totalBobot != 100) {
                        return redirect()->back()->withErrors(['bobot_kriteria' => 'Total bobot harus 100 persen. Saat ini: ' . $totalBobot . '%']);
                    }

                    foreach ($request->nama_kriteria as $index => $kriteria) {
                        KriteriaPenilaian::create([
                            'kode_fta' => $formPenilaian->kode_fta,
                            'id_fta' => $id_fta,  // Added this line
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
                            'id_fta' => $id_fta,  // Added this line
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

    public function rentangPenilaian()
    {
        $rentangNilai = DB::table('rentang_nilai')
            ->whereIn('id_nilai', ['A', 'AB', 'B', 'BC', 'C', 'CD'])
            ->select('id_nilai', 'batas_atas', 'batas_bawah')
            ->orderBy('batas_atas', 'desc')
            ->get();

        return $rentangNilai;
    }

    public function tambahRubrikPenilaian(): View
    {
        $formPenilaianList = FormPenilaian::all(); // Ambil semua data Form Penilaian dari database
        $kriteriaList = KriteriaPenilaian::all(); // Ambil semua data Kriteria Penilaian dari database
        $rentangNilai = $this->rentangPenilaian(); // Ambil rentang nilai

        return view('KelolaPenilaianTA.views.formulir-penilaian.tambah_rubrik_penilaian', compact('formPenilaianList', 'kriteriaList', 'rentangNilai'));
    }

    public function getKriteriaByKodeFTA($kodeFTA)
    {
        $kriteria = KriteriaPenilaian::where('kode_fta', $kodeFTA)
            ->select('id', 'nama_kriteria', 'bobot_kriteria')
            ->get();

        return response()->json($kriteria);
    }

    public function ubahAspek($id)
    {
        // Get the form data with related criteria and feedback
        $formPenilaian = FormPenilaian::with(['kriteriaPenilaian', 'aspekFeedback'])
            ->where('id_fta', $id)
            ->firstOrFail();
        
        // Check if relationships are properly defined
        // Make sure kriteriaPenilaian and aspekFeedback are using the correct foreign key
        
        $prodiList = Prodi::all();
        
        // Create the aspek object structure expected by the view
        $aspek = (object)[
            'id' => $formPenilaian->id_fta,
            'kodeFTA' => $formPenilaian->kode_fta,
            'namaFTA' => $formPenilaian->nama_fta,
            'jenisForm' => $formPenilaian->jenis_form,
            'namaProdi' => $formPenilaian->prodi->nama_prodi,
            'tanggalTenggat' => $formPenilaian->tanggal_tenggat_pengisian,
            'waktuTenggat' => $formPenilaian->waktu_tenggat_pengisian,
            'penilaian' => $formPenilaian->kriteriaPenilaian,
            'feedback' => $formPenilaian->aspekFeedback
        ];
        
        // Debug - make sure data is found
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
        $formPenilaian = FormPenilaian::where('id_fta', $id)->firstOrFail();
        
        // Update form deadline
        $formPenilaian->tanggal_tenggat_pengisian = $request->tanggalTenggat;
        $formPenilaian->save();
        
        // Get both kode_fta and id_fta
        $kodeFTA = $formPenilaian->kode_fta;
        $id_fta = $formPenilaian->id_fta;
        
        if ($formPenilaian->jenis_form == 'penilaian') {
            // Calculate total weight
            $totalBobot = array_sum($request->bobot_kriteria);
            
            // Check if total weight is 100
            if ($totalBobot != 100) {
                return redirect()->back()->withErrors(['bobot_kriteria' => 'Total bobot harus 100 persen. Saat ini: ' . $totalBobot . '%']);
            }
            
            // Delete existing criteria using id_fta
            KriteriaPenilaian::where('id_fta', $id_fta)->delete();
            
            // Add new criteria with both kode_fta and id_fta
            $namaKriteria = $request->nama_kriteria;
            $bobotKriteria = $request->bobot_kriteria;
            
            if ($namaKriteria) {
                foreach ($namaKriteria as $index => $nama) {
                    if (empty($nama)) {
                        continue;
                    }
                    
                    KriteriaPenilaian::create([
                        'kode_fta' => $kodeFTA,
                        'id_fta' => $id_fta,
                        'nama_kriteria' => $nama,
                        'bobot_kriteria' => $bobotKriteria[$index] ?? 0,
                    ]);
                }
            }
        } else {
            // Delete existing feedback aspects using id_fta
            AspekFeedback::where('id_fta', $id_fta)->delete();
            
            // Add new feedback aspects with both kode_fta and id_fta
            $namaAspekFeedback = $request->nama_aspek_feedback;
            
            if ($namaAspekFeedback) {
                foreach ($namaAspekFeedback as $nama) {
                    if (empty($nama)) {
                        continue;
                    }
                    
                    AspekFeedback::create([
                        'kode_fta' => $kodeFTA,
                        'id_fta' => $id_fta,
                        'nama_aspek_feedback' => $nama,
                    ]);
                }
            }
        }
        
        return redirect()->route('formulir-penilaian.index')
            ->with('success', 'Aspek penilaian berhasil diperbarui.');
    }

    public function viewDetailPenilaian($idFta, $idProdi): View
    {
        // Ambil data berdasarkan id_fta
        $kategori = DB::table('form_penilaian')
            ->where('id_fta', $idFta)
            ->select('kode_fta', 'nama_fta', 'tanggal_tenggat_pengisian', 'waktu_tenggat_pengisian') // Pastikan kode_fta juga diambil
            ->first();

        // Ambil rentang nilai hanya untuk A, AB, B, BC, C, dan CD
        $rentangNilai = DB::table('rentang_nilai')
            ->whereIn('id_nilai', ['A', 'AB', 'B', 'BC', 'C', 'CD'])
            ->select('id_nilai', 'batas_atas', 'batas_bawah')
            ->orderBy('batas_atas', 'desc')
            ->get();

        // Ambil data kriteria beserta rubriknya
        $namaKriteria = DB::table('kriteria_penilaian')
            ->where('id_fta', $idFta)
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

        return view('KelolaPenilaianTA.views.formulir-penilaian.detail_fta_penilaian', compact('kategori', 'rentangNilai', 'namaKriteria'));
    }


    public function viewDetailFeedback($idFta, $idProdi): View
    {
        // Ambil data berdasarkan id_fta
        $kategori = DB::table('form_penilaian')
            ->where('id_fta', $idFta)
            ->select('kode_fta', 'nama_fta', 'tanggal_tenggat_pengisian', 'waktu_tenggat_pengisian') // Pastikan kode_fta juga diambil
            ->first();

        $aspekFeedback = DB::table('aspek_feedback')
            ->where('id_fta', $idFta)
            ->select('nama_aspek_feedback')
            ->get();
        
        return view('KelolaPenilaianTA.views.formulir-penilaian.detail_fta_feedback', compact('kategori', 'aspekFeedback'));
    }

    // public function storeRubrik(Request $request)
    // {
    //     DB::beginTransaction();

    //     try {
    //         // Get the selected form penilaian
    //         $kodeFTA = $request->kode_fta;
    //         $formPenilaian = FormPenilaian::where('kode_fta', $kodeFTA)->first();
            
    //         $idFTA = $formPenilaian->id_fta;
            
    //         // Get the selected criteria names and details
    //         $namaKriteria = $request->nama_kriteria;
    //         $detailKriteria = $request->detail;
            
    //         // Get rentang nilai for reference
    //         $rentangNilai = DB::table('rentang_nilai')
    //             ->whereIn('id_nilai', ['A', 'AB', 'B', 'BC', 'C', 'CD'])
    //             ->select('id_nilai')
    //             ->get();
                
    //         // Process each row of rubric data
    //         if (is_array($namaKriteria)) {
    //             foreach ($namaKriteria as $index => $kriteria) {
    //                 // Find the id_kriteria based on nama_kriteria and kode_fta
    //                 $kriteriaPenilaian = KriteriaPenilaian::where('id_fta', $kodeFTA)
    //                     ->where('nama_kriteria', $kriteria)
    //                     ->first();
                    
    //                 if (!$kriteriaPenilaian) {
    //                     continue; // Skip if criteria not found
    //                 }
                    
    //                 // Create a new rubrik
    //                 $rubrik = DB::table('rubrik')->insertGetId([
    //                     'id_kriteria' => $kriteriaPenilaian->id_kriteria,
    //                     'nama_rubrik' => $detailKriteria[$index],
    //                     'created_at' => now(),
    //                     'updated_at' => now()
    //                 ]);
                    
    //                 // Create detail_rubrik entries for each nilai
    //                 foreach ($rentangNilai as $nilai) {
    //                     $nilaiKey = 'nilai_' . $nilai->id_nilai;
                        
    //                     if (isset($request->$nilaiKey) && isset($request->$nilaiKey[$index])) {
    //                         DB::table('detail_rubrik')->insert([
    //                             'id_rubrik' => $rubrik,
    //                             'id_nilai' => $nilai->id_nilai,
    //                             'detail_rubrik_penilaian' => $request->$nilaiKey[$index],
    //                             'created_at' => now(),
    //                             'updated_at' => now()
    //                         ]);
    //                     }
    //                 }
    //             }
    //         }
            
    //         DB::commit();
    //         return redirect()->route('formulir-penilaian.index')
    //             ->with('success', 'Rubrik penilaian berhasil ditambahkan.');
                
    //     } catch (\Exception $e) {
    //         DB::rollback(); // Rollback if there's an error
    //         return redirect()->back()
    //             ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
    //     }
    // }

    // public function showFormRubrik($request)
    // {
    //     $kodeFTA = $request->kode_fta;

    //     // Jika kodeFTA dipilih, ambil id_fta dan kriteria yang sesuai
    //     if ($kodeFTA) {
    //         $formPenilaian = FormPenilaian::where('kode_fta', $kodeFTA)->first();

    //         if ($formPenilaian) {
    //             $idFTA = $formPenilaian->id_fta;

    //             // Ambil daftar kriteria sesuai dengan id_fta
    //             $kriteriaList = KriteriaPenilaian::where('id_fta', $idFTA)->get();
    //         } else {
    //             $kriteriaList = [];
    //         }
    //     } else {
    //         $kriteriaList = []; // Jika belum ada kodeFTA yang dipilih
    //     }

    //     // Dapatkan data yang dibutuhkan untuk form (misalnya daftar form penilaian dan rentang nilai)
    //     $formPenilaianList = FormPenilaian::all();
    //     $rentangNilai = RentangNilai::all();

    //     return view('KelolaPenilaianTA.views.formulir-penilaian.ubah_formulir_ta', compact('formPenilaianList', 'kriteriaList', 'rentangNilai', 'kodeFTA'));
    // }

    public function tambahFormRubrik($idFta)
    {
        $data = DB::table('form_penilaian')
            ->where('id_fta', $idFta)
            ->select('kode_fta', 'nama_fta', 'id_prodi', 'jenis_form', 'tanggal_tenggat_pengisian', 'id_fta', )
            ->first();

            $rentangNilai = DB::table('rentang_nilai')
            ->whereIn('id_nilai', ['A', 'AB', 'B', 'BC', 'C', 'CD'])
            ->select('id_nilai', 'batas_atas', 'batas_bawah')
            ->orderBy('batas_atas', 'desc')
            ->get();

        $kriteriaList = DB::table('kriteria_penilaian')
            ->where('id_fta', $idFta)
            ->select('id_kriteria', 'nama_kriteria', 'bobot_kriteria')
            ->get();


        return view('KelolaPenilaianTA.views.formulir-penilaian.tambah_rubrik_penilaian', compact('data', 'rentangNilai', 'kriteriaList'));
    }

    public function ubahFormRubrik($idFta)
    {
        $data = DB::table('form_penilaian')
            ->where('id_fta', $idFta)
            ->select('kode_fta', 'nama_fta', 'id_prodi', 'jenis_form', 'tanggal_tenggat_pengisian', 'id_fta')
            ->first();

        $rentangNilai = DB::table('rentang_nilai')
            ->whereIn('id_nilai', ['A', 'AB', 'B', 'BC', 'C', 'CD'])
            ->select('id_nilai', 'batas_atas', 'batas_bawah')
            ->orderBy('batas_atas', 'desc')
            ->get();

        $kriteriaList = DB::table('kriteria_penilaian')
            ->where('id_fta', $idFta)
            ->select('id_kriteria', 'nama_kriteria', 'bobot_kriteria')
            ->get();

        // Ambil data rubrik berdasarkan kriteria
        $rubrikList = $kriteriaList->map(function ($kriteria) use ($rentangNilai) {
            $kriteria->rubrik = DB::table('rubrik')
                ->where('id_kriteria', $kriteria->id_kriteria)
                ->select('id_rubrik', 'nama_rubrik')
                ->get()
                ->map(function ($rubrik) use ($rentangNilai) {
                    // Ambil detail rubrik berdasarkan id_rubrik dan id_nilai
                    $rubrik->detail = DB::table('detail_rubrik')
                        ->where('id_rubrik', $rubrik->id_rubrik)
                        ->select('id_detail_rubrik', 'id_nilai', 'detail_rubrik_penilaian')
                        ->get();

                    return $rubrik;
                });

            return $kriteria;
        });

        return view('KelolaPenilaianTA.views.formulir-penilaian.ubah_rubrik_penilaian', compact('data', 'rentangNilai', 'kriteriaList', 'rubrikList'));
    }


    public function viewTabelRubrik(): View 
    {
        $data = DB::table('form_penilaian')
            ->where('jenis_form', 'penilaian')
            ->select('kode_fta', 'nama_fta', 'id_prodi', 'jenis_form', 'tanggal_tenggat_pengisian', 'id_fta')
            ->get()
            ->map(function ($row) {
                // Cek apakah ada data detail_rubrik_penilaian untuk id_fta tertentu
                $hasRubrik = DB::table('detail_rubrik')
                    ->join('rubrik', 'detail_rubrik.id_rubrik', '=', 'rubrik.id_rubrik')
                    ->join('kriteria_penilaian', 'rubrik.id_kriteria', '=', 'kriteria_penilaian.id_kriteria')
                    ->where('kriteria_penilaian.id_fta', $row->id_fta)
                    ->exists();
    
                $row->hasRubrik = $hasRubrik;
                return $row;
            });
    
        return view('KelolaPenilaianTA.views.formulir-penilaian.formulir_penilaian_ta_rubrik', compact('data'));
    }    

    public function storeRubrik(Request $request)
    {
        DB::beginTransaction();

        try {
            // Get form data
            $kodeFta = $request->input('kode_fta');
            
            // Ambil data kriteria dari request
            $kriterias = is_array($request->input('nama_kriteria')) 
                ? $request->input('nama_kriteria') 
                : [$request->input('nama_kriteria')];
                
            $details = $request->input('detail');
            
            // Loop through each row
            foreach ($kriterias as $index => $idKriteria) {
                $detail = $details[$index] ?? '';
                
                // Insert ke tabel rubrik menggunakan DB Query Builder
                $idRubrik = DB::table('rubrik')->insertGetId([
                    'id_kriteria' => $idKriteria,
                    'nama_rubrik' => $detail
                ]);
                
                // Save details for each rentang nilai
                $rentangNilai = DB::table('rentang_nilai')
                    ->whereIn('id_nilai', ['A', 'AB', 'B', 'BC', 'C', 'CD'])
                    ->select('id_nilai')
                    ->orderBy('batas_atas', 'desc')
                    ->get();
                    
                foreach ($rentangNilai as $nilai) {
                    $nilaiKey = 'nilai_' . $nilai->id_nilai;
                    $detailArray = $request->input($nilaiKey);
                    $detailNilai = $detailArray[$index] ?? '';
                    
                    // Insert ke tabel detail_rubrik
                    DB::table('detail_rubrik')->insert([
                        'id_rubrik' => $idRubrik,
                        'id_nilai' => $nilai->id_nilai,
                        'detail_rubrik_penilaian' => $detailNilai
                    ]);
                }
            }
            
            DB::commit();
            // Ganti dengan route yang benar
            return redirect()->route('tabelRubrik')
                ->with('success', 'Rubrik penilaian berhasil ditambahkan.');
                
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function updateRubrik(Request $request)
    {
        DB::beginTransaction();

        try {
            // Get form data
            $kodeFta = $request->input('kode_fta');
            
            // Ambil data kriteria dari request
            $kriterias = $request->input('nama_kriteria');
            $details = $request->input('detail');
            
            // Ambil id_fta dari kode_fta
            $idFta = DB::table('form_penilaian')
                ->where('kode_fta', $kodeFta)
                ->value('id_fta');
                
            // Dapatkan semua id_kriteria yang ada untuk form ini
            $existingKriteriaIds = DB::table('kriteria_penilaian')
                ->where('id_fta', $idFta)
                ->pluck('id_kriteria')
                ->toArray();
            
            // Sebelum memproses data baru, hapus semua rubrik dan detail yang terkait dengan kriteria di form ini
            // Langkah 1: Dapatkan semua id_rubrik yang terkait dengan kriteria
            $existingRubrikIds = DB::table('rubrik')
                ->whereIn('id_kriteria', $existingKriteriaIds)
                ->pluck('id_rubrik')
                ->toArray();
                
            // Langkah 2: Hapus semua detail_rubrik terlebih dahulu (constraint foreign key)
            if (!empty($existingRubrikIds)) {
                DB::table('detail_rubrik')
                    ->whereIn('id_rubrik', $existingRubrikIds)
                    ->delete();
                    
                // Langkah 3: Hapus semua rubrik
                DB::table('rubrik')
                    ->whereIn('id_rubrik', $existingRubrikIds)
                    ->delete();
            }
            
            // Loop through each row from the form input
            foreach ($kriterias as $index => $idKriteria) {
                $detail = $details[$index] ?? '';
                
                // Insert new rubrik
                $idRubrik = DB::table('rubrik')->insertGetId([
                    'id_kriteria' => $idKriteria,
                    'nama_rubrik' => $detail
                ]);
                
                // Save details for each rentang nilai
                $rentangNilai = DB::table('rentang_nilai')
                    ->whereIn('id_nilai', ['A', 'AB', 'B', 'BC', 'C', 'CD'])
                    ->select('id_nilai')
                    ->orderBy('batas_atas', 'desc')
                    ->get();
                    
                foreach ($rentangNilai as $nilai) {
                    $nilaiKey = 'nilai_' . $nilai->id_nilai;
                    $detailArray = $request->input($nilaiKey);
                    $detailNilai = $detailArray[$index] ?? '';
                    
                    // Insert ke tabel detail_rubrik
                    DB::table('detail_rubrik')->insert([
                        'id_rubrik' => $idRubrik,
                        'id_nilai' => $nilai->id_nilai,
                        'detail_rubrik_penilaian' => $detailNilai
                    ]);
                }
            }
            
            DB::commit();
            return redirect()->route('tabelRubrik')
                ->with('success', 'Rubrik penilaian berhasil diperbarui.');
                
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    // public function updateRubrik(Request $request)
    // {
    //     DB::beginTransaction();

    //     try {
    //         // Get form data
    //         $kodeFta = $request->input('kode_fta');
            
    //         // Ambil data kriteria dari request
    //         $kriterias = $request->input('nama_kriteria');
    //         $details = $request->input('detail');
            
    //         // Hapus rubrik lama yang terkait dengan id_fta ini
    //         $idFta = DB::table('form_penilaian')
    //             ->where('kode_fta', $kodeFta)
    //             ->value('id_fta');
                
    //         // Get existing rubrik IDs to update instead of recreate
    //         $kriteriaIds = DB::table('kriteria_penilaian')
    //             ->where('id_fta', $idFta)
    //             ->pluck('id_kriteria')
    //             ->toArray();
                
    //         $existingRubriks = DB::table('rubrik')
    //             ->whereIn('id_kriteria', $kriteriaIds)
    //             ->get()
    //             ->keyBy('id_kriteria');
            
    //         // Loop through each row
    //         foreach ($kriterias as $index => $idKriteria) {
    //             $detail = $details[$index] ?? '';
                
    //             // Check if rubrik exists for this criteria
    //             if (isset($existingRubriks[$idKriteria])) {
    //                 $existingRubrik = $existingRubriks[$idKriteria];
    //                 $idRubrik = $existingRubrik->id_rubrik;
                    
    //                 // Update existing rubrik
    //                 DB::table('rubrik')
    //                     ->where('id_rubrik', $idRubrik)
    //                     ->update([
    //                         'nama_rubrik' => $detail
    //                     ]);
                        
    //                 // Delete existing detail_rubrik for this rubrik
    //                 DB::table('detail_rubrik')
    //                     ->where('id_rubrik', $idRubrik)
    //                     ->delete();
    //             } else {
    //                 // Insert new rubrik if not exists
    //                 $idRubrik = DB::table('rubrik')->insertGetId([
    //                     'id_kriteria' => $idKriteria,
    //                     'nama_rubrik' => $detail
    //                 ]);
    //             }
                
    //             // Save details for each rentang nilai
    //             $rentangNilai = DB::table('rentang_nilai')
    //                 ->whereIn('id_nilai', ['A', 'AB', 'B', 'BC', 'C', 'CD'])
    //                 ->select('id_nilai')
    //                 ->orderBy('batas_atas', 'desc')
    //                 ->get();
                    
    //             foreach ($rentangNilai as $nilai) {
    //                 $nilaiKey = 'nilai_' . $nilai->id_nilai;
    //                 $detailArray = $request->input($nilaiKey);
    //                 $detailNilai = $detailArray[$index] ?? '';
                    
    //                 // Insert ke tabel detail_rubrik
    //                 DB::table('detail_rubrik')->insert([
    //                     'id_rubrik' => $idRubrik,
    //                     'id_nilai' => $nilai->id_nilai,
    //                     'detail_rubrik_penilaian' => $detailNilai
    //                 ]);
    //             }
    //         }
            
    //         // Remove any rubrik that is no longer in the form
    //         $newKriteriaIds = $kriterias;
    //         $rubriksToDelete = DB::table('rubrik')
    //             ->whereIn('id_kriteria', $kriteriaIds)
    //             ->whereNotIn('id_kriteria', $newKriteriaIds)
    //             ->pluck('id_rubrik')
    //             ->toArray();
                
    //         if (count($rubriksToDelete) > 0) {
    //             // Delete related detail_rubrik first
    //             DB::table('detail_rubrik')
    //                 ->whereIn('id_rubrik', $rubriksToDelete)
    //                 ->delete();
                    
    //             // Then delete the rubrik
    //             DB::table('rubrik')
    //                 ->whereIn('id_rubrik', $rubriksToDelete)
    //                 ->delete();
    //         }
            
    //         DB::commit();
    //         return redirect()->route('tabelRubrik')
    //             ->with('success', 'Rubrik penilaian berhasil diperbarui.');
                
    //     } catch (\Exception $e) {
    //         DB::rollback();
    //         return redirect()->back()
    //             ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
    //             ->withInput();
    //     }
    // }

}