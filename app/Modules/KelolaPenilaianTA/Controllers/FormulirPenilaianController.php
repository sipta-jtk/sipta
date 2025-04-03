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
        $messages = [
            'kodeFTA.regex' => 'Kode FTA harus mengikuti format FTA.XX atau FTA.XXX.',
        ];
        
        // Validasi input form
        $validator = \Validator::make($request->all(), [
            'kodeFTA' => ['required', 'regex:/^FTA\.\d{2,3}$/'], // Format FTA.XX atau FTA.XXX
            'namaFTA' => 'required',
            'namaProdi' => 'required',
            'jenisForm' => 'required',
            'tanggalTenggat' => 'required|date',
            'waktuTenggat' => 'required',
        ], $messages);

        // Validasi untuk Kode FTA unik dalam jenis formulir yang sama
        $validator->after(function ($validator) use ($request) {
            $exists = FormPenilaian::where('kode_fta', $request->kodeFTA)
                                ->where('jenis_form', $request->jenisForm)
                                ->where('id_prodi', $request->namaProdi)
                                ->exists();
            
            if ($exists) {
                $validator->errors()->add('kodeFTA', 'Kode FTA sudah digunakan');
            }
        });

        if ($validator->fails()) {
            return redirect()->back()
                            ->withErrors($validator)
                            ->withInput();
        }

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

            $id_fta = $formPenilaian->id_fta;

            // Proses berdasarkan jenis formulir (kode yang sudah ada)
            if ($request->jenisForm === 'Penilaian') {
                // Kode untuk Penilaian (tetap sama seperti sebelumnya)
                if ($request->has('nama_kriteria')) {
                    // Kalkulasi total bobot
                    $totalBobot = array_sum($request->bobot_kriteria);

                    // Cek apakah total bobot adalah 100
                    if ($totalBobot != 100) {
                        return redirect()->back()->withErrors(['bobot_kriteria' => 'Total bobot harus 100 persen. Saat ini: ' . $totalBobot . '%']);
                    }

                    foreach ($request->nama_kriteria as $index => $kriteria) {
                        KriteriaPenilaian::create([
                            'kode_fta' => $formPenilaian->kode_fta,
                            'id_fta' => $id_fta,
                            'nama_kriteria' => $kriteria,
                            'bobot_kriteria' => $request->bobot_kriteria[$index] ?? null,
                        ]);
                    }
                }
            } else if ($request->jenisForm === 'Feedback') {
                // Kode untuk Feedback (tetap sama seperti sebelumnya)
                if ($request->has('nama_aspek_feedback')) {
                    foreach ($request->nama_aspek_feedback as $kriteria) {
                        AspekFeedback::create([
                            'kode_fta' => $formPenilaian->kode_fta,
                            'id_fta' => $id_fta,
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
        $formPenilaian = FormPenilaian::with(['kriteriaPenilaian', 'aspekFeedback'])
            ->where('id_fta', $id)
            ->firstOrFail();
        
        $prodiList = Prodi::all();
        
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
        
        $formPenilaian->tanggal_tenggat_pengisian = $request->tanggalTenggat;
        $formPenilaian->save();
        
        $kodeFTA = $formPenilaian->kode_fta;
        $id_fta = $formPenilaian->id_fta;
        
        if ($formPenilaian->jenis_form == 'penilaian') {
            $totalBobot = array_sum($request->bobot_kriteria);
            
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
            AspekFeedback::where('id_fta', $id_fta)->delete();
            
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
            
            // Perulangan untuk setiap baris input form
            foreach ($kriterias as $index => $idKriteria) {
                $detail = $details[$index] ?? '';
                
                // Insert ke tabel rubrik menggunakan DB Query Builder
                $idRubrik = DB::table('rubrik')->insertGetId([
                    'id_kriteria' => $idKriteria,
                    'nama_rubrik' => $detail
                ]);
                
                // Simpan detail untuk setiap rentang nilai
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
            
            // Perulangan untuk setiap baris input form
            foreach ($kriterias as $index => $idKriteria) {
                $detail = $details[$index] ?? '';
                
                // Insert new rubrik
                $idRubrik = DB::table('rubrik')->insertGetId([
                    'id_kriteria' => $idKriteria,
                    'nama_rubrik' => $detail
                ]);
                
                // Simpan detail untuk setiap rentang nilai
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

}