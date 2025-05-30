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
use App\Models\Rubrik;
use App\Models\DetailRubrik;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class FormulirPenilaianController extends Controller {
    
    /**
     * Menampilkan daftar formulir penilaian.
     */
    public function getFormPenilaian()
    {
        $data = DB::table('form_penilaian')
            ->join('prodi', 'form_penilaian.id_prodi', '=', 'prodi.id_prodi') // Join dengan tabel prodi
            ->select(
                'form_penilaian.kode_fta',
                'form_penilaian.nama_fta',
                'prodi.nama_prodi',
                'form_penilaian.jenis_form',
                'form_penilaian.jenis_ta',
                'form_penilaian.tanggal_tenggat_pengisian',
                'form_penilaian.id_fta'
            )
            ->get();

        return view('KelolaPenilaianTA.views.formulir-penilaian.formulir_penilaian_ta', compact('data'));
    }

    /**
     * Menyimpan data formulir penilaian baru ke database.
     */
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
            'jenisTA' => 'required_if:namaProdi,2',
        ], $messages);

        if ($validator->fails()) {
            return redirect()->back()
                            ->withErrors($validator)
                            ->withInput();
        }

        $exists = FormPenilaian::where('id_prodi', $request->namaProdi)
            ->where('jenis_form', $request->jenisForm)
            ->where('kode_fta', $request->kodeFTA)
            ->when(in_array($request->namaProdi, [1, 2]), function ($query) use ($request) {
                // Cek jenis_ta jika prodi adalah D3 (id = 1) atau D4 (id = 2)
                $query->where('jenis_ta', $request->jenisTA);
            })
            ->exists();

        if ($exists) {
            return redirect()->back()
                ->withErrors(['kombinasi' => 'FTA sudah tersedia'])
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
                'jenis_ta' => $request->jenisTA ?? null,
            ]);

            $id_fta = $formPenilaian->id_fta;

            if ($request->jenisForm === 'Penilaian') {
                if ($request->has('nama_kriteria')) {
                    // Kalkulasi total bobot
                    $totalBobot = array_sum($request->bobot_kriteria);

                    // Untuk mengecek apakah total bobot adalah 100
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
    
    /**
     * Menampilkan halaman untuk menambahkan aspek formulir.
     */
    public function tambahAspekFormulir(): View
    {
        $prodiList = Prodi::all(); // Ambil semua data Prodi dari database
        return view('KelolaPenilaianTA.views.formulir-penilaian.tambah_aspek_formulir', compact('prodiList'));
    }

    /**
     * Mengambil data rentang nilai dari database.
     */
    public function rentangPenilaian()
    {
        $rentangNilai = DB::table('rentang_nilai')
            ->whereIn('id_nilai', ['A', 'AB', 'B', 'BC', 'C', 'CD'])
            ->select('id_nilai', 'batas_atas', 'batas_bawah')
            ->orderBy('batas_atas', 'desc')
            ->get();

        return $rentangNilai;
    }

    /**
     * Menampilkan halaman untuk menambahkan rubrik penilaian.
     */
    public function tambahRubrikPenilaian(): View
    {
        $formPenilaianList = FormPenilaian::all();  // Ambil semua data Form Penilaian dari database
        $kriteriaList = KriteriaPenilaian::all();   // Ambil semua data Kriteria Penilaian dari database
        $rentangNilai = $this->rentangPenilaian();  // Ambil rentang nilai

        return view('KelolaPenilaianTA.views.formulir-penilaian.tambah_rubrik_penilaian', compact('formPenilaianList', 'kriteriaList', 'rentangNilai'));
    }

    /**
     * Mengambil data kriteria berdasarkan kode FTA.
     */
    public function getKriteriaByKodeFTA($kodeFTA)
    {
        $kriteria = KriteriaPenilaian::where('kode_fta', $kodeFTA)
            ->select('id', 'nama_kriteria', 'bobot_kriteria')
            ->get();

        return response()->json($kriteria);
    }

    /**
     * Menampilkan halaman untuk mengubah aspek formulir penilaian.
     */
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
            'jenisTA' => $formPenilaian->jenis_ta,
            'tanggalTenggat' => $formPenilaian->tanggal_tenggat_pengisian,
            'waktuTenggat' => $formPenilaian->waktu_tenggat_pengisian,
            'penilaian' => $formPenilaian->kriteriaPenilaian,
            'feedback' => $formPenilaian->aspekFeedback
        ];
        
        return view('KelolaPenilaianTA.views.formulir-penilaian.ubah_aspek_penilaian', 
            compact('aspek', 'prodiList'));
    }

    /**
     * Memperbarui aspek formulir penilaian.
     */
    public function updateAspek(Request $request, $id)
    {
        // Validasi request
        $request->validate([
            'tanggalTenggat' => 'required|date',
            'waktuTenggat' => 'required|date_format:H:i',
            'bobot_kriteria.*' => 'required|integer|min:0|max:100',
        ]);

        $formPenilaian = FormPenilaian::where('id_fta', $id)->with('kriteriaPenilaian')->firstOrFail();
        $kriteriaPenilaian = $formPenilaian->kriteriaPenilaian->pluck('id_kriteria')->toArray();
        
        // Update tanggal dan waktu tenggat pengisian
        $formPenilaian->tanggal_tenggat_pengisian = $request->tanggalTenggat;
        $formPenilaian->waktu_tenggat_pengisian = $request->waktuTenggat;
        $formPenilaian->save();
        
        $kodeFTA = $formPenilaian->kode_fta;
        $id_fta = $formPenilaian->id_fta;
        
        if ($formPenilaian->jenis_form == 'penilaian') {
            $totalBobot = array_sum($request->bobot_kriteria);
            
            if ($totalBobot != 100) {
                return redirect()->back()->withErrors(['bobot_kriteria' => 'Total bobot harus 100 persen. Saat ini: ' . $totalBobot . '%']);
            }
            
            $namaKriteria = $request->nama_kriteria;
            $bobotKriteria = $request->bobot_kriteria;

            $kriteriaPenilaian = $formPenilaian->kriteriaPenilaian->pluck('id_kriteria')->toArray();
            
            foreach ($kriteriaPenilaian as $index => $idKriteria) {
                if (isset($namaKriteria[$index])) {
                    KriteriaPenilaian::where('id_kriteria', $idKriteria)->update([
                        'nama_kriteria' => $namaKriteria[$index],
                        'bobot_kriteria' => $bobotKriteria[$index] ?? 0,
                    ]);
                }
            }

            // Jika ada kriteria baru (input lebih banyak dari data lama), insert sisanya
            for ($i = count($kriteriaPenilaian); $i < count($namaKriteria); $i++) {
                if (!empty($namaKriteria[$i])) {
                    KriteriaPenilaian::create([
                        'kode_fta' => $kodeFTA,
                        'id_fta' => $id_fta,
                        'nama_kriteria' => $namaKriteria[$i],
                        'bobot_kriteria' => $bobotKriteria[$i] ?? 0,
                    ]);
                }
            }

            // Jika ada kriteria lama yang dihapus di form, hapus dari DB
            if (count($namaKriteria) < count($kriteriaPenilaian)) {
                $idToKeep = [];
                for ($i = 0; $i < count($namaKriteria); $i++) {
                    if (!empty($namaKriteria[$i])) {
                        $idToKeep[] = $kriteriaPenilaian[$i];
                    }
                }
                KriteriaPenilaian::where('id_fta', $id_fta)
                    ->whereNotIn('id_kriteria', $idToKeep)
                    ->delete();
            }
        } else {
            $namaAspekFeedback = $request->nama_aspek_feedback;
            // Ambil data aspek lama
            $aspekLama = AspekFeedback::where('id_fta', $id_fta)->pluck('id_feedback')->toArray();

            // Update aspek yang sudah ada
            foreach ($aspekLama as $index => $idAspek) {
                if (isset($namaAspekFeedback[$index]) && !empty($namaAspekFeedback[$index])) {
                    AspekFeedback::where('id_feedback', $idAspek)->update([
                        'nama_aspek_feedback' => $namaAspekFeedback[$index],
                    ]);
                }
            }

            // Tambah aspek baru jika ada
            for ($i = count($aspekLama); $i < count($namaAspekFeedback); $i++) {
                if (!empty($namaAspekFeedback[$i])) {
                    AspekFeedback::create([
                        'kode_fta' => $kodeFTA,
                        'id_fta' => $id_fta,
                        'nama_aspek_feedback' => $namaAspekFeedback[$i],
                    ]);
                }
            }

            // Hapus aspek lama jika dikurangi di form
            if (count($namaAspekFeedback) < count($aspekLama)) {
                $idToKeep = [];
                for ($i = 0; $i < count($namaAspekFeedback); $i++) {
                    if (!empty($namaAspekFeedback[$i])) {
                        $idToKeep[] = $aspekLama[$i];
                    }
                }
                AspekFeedback::where('id_fta', $id_fta)
                    ->whereNotIn('id_feedback', $idToKeep)
                    ->delete();
            }
        }
        
        return redirect()->route('formulir-penilaian.index')
            ->with('success', 'Aspek penilaian berhasil diperbarui.');
    }

    /**
     * Menampilkan detail jenis FTA penilaian berdasarkan ID FTA dan ID Prodi.
     */
    public function viewDetailPenilaian($idFta, $idProdi): View
    {
        // Ambil data berdasarkan id_fta
        $kategori = DB::table('form_penilaian')
        ->join('prodi', 'form_penilaian.id_prodi', '=', 'prodi.id_prodi') // Join dengan tabel prodi
        ->where('form_penilaian.id_fta', $idFta)
        ->select(
            'form_penilaian.kode_fta',
            'form_penilaian.nama_fta',
            'prodi.nama_prodi',
            'form_penilaian.jenis_form',
            'form_penilaian.jenis_ta',
            'form_penilaian.tanggal_tenggat_pengisian',
            'form_penilaian.waktu_tenggat_pengisian'
        )
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

    /**
     * Menampilkan detail jenis FTA feedback berdasarkan ID FTA dan ID Prodi.
     */
    public function viewDetailFeedback($idFta, $idProdi): View
    {
        // Ambil data berdasarkan id_fta
        $kategori = DB::table('form_penilaian')
        ->join('prodi', 'form_penilaian.id_prodi', '=', 'prodi.id_prodi') // Join dengan tabel prodi
        ->where('form_penilaian.id_fta', $idFta)
        ->select(
            'form_penilaian.kode_fta',
            'form_penilaian.nama_fta',
            'prodi.nama_prodi',
            'form_penilaian.jenis_form',
            'form_penilaian.jenis_ta',
            'form_penilaian.tanggal_tenggat_pengisian',
            'form_penilaian.waktu_tenggat_pengisian'
        )
        ->first();
        
        if ($kategori) {
            $kategori->tanggal_tenggat_pengisian = Carbon::parse($kategori->tanggal_tenggat_pengisian)->translatedFormat('d F Y');
        }

        $aspekFeedback = DB::table('aspek_feedback')
            ->where('id_fta', $idFta)
            ->select('nama_aspek_feedback')
            ->get();
        
        return view('KelolaPenilaianTA.views.formulir-penilaian.detail_fta_feedback', compact('kategori', 'aspekFeedback'));
    }

    /**
     * Menampilkan detail jenis FTA dosen pembimbing berdasarkan ID FTA dan ID Prodi.
     */
    public function viewDetailDosenPembimbing($idFta, $idProdi): View
    {
        // Ambil data berdasarkan id_fta
        $kategori = DB::table('form_penilaian')
        ->join('prodi', 'form_penilaian.id_prodi', '=', 'prodi.id_prodi') // Join dengan tabel prodi
        ->where('form_penilaian.id_fta', $idFta)
        ->select(
            'form_penilaian.kode_fta',
            'form_penilaian.nama_fta',
            'prodi.nama_prodi',
            'form_penilaian.jenis_form',
            'form_penilaian.jenis_ta',
            'form_penilaian.tanggal_tenggat_pengisian',
            'form_penilaian.waktu_tenggat_pengisian'
        )
        ->first();
            
        if ($kategori) {
            $kategori->tanggal_tenggat_pengisian = Carbon::parse($kategori->tanggal_tenggat_pengisian)->translatedFormat('d F Y');
        }
        
        if (!$kategori) {
            abort(404, 'Data formulir penilaian tidak ditemukan.');
        }

        $aspekPenilaian = [
            'A Luaran Tugas Akhir' => [],
            'B Proses Bimbingan' => []
        ];

        $kriteriaPenilaian = DB::table('kriteria_penilaian')
            ->where('id_fta', $idFta)
            ->select('nama_kriteria', 'bobot_kriteria')
            ->get();


        foreach ($kriteriaPenilaian as $kriteria) {
            if ($kriteria->nama_kriteria == 'Dokumen' || $kriteria->nama_kriteria == 'Produk Perangkat Lunak/Hasil Penelitian') {
                $aspekPenilaian['A Luaran Tugas Akhir'][] = [
                    'nama' => $kriteria->nama_kriteria,
                    'bobot' => $kriteria->bobot_kriteria
                ];
            } elseif ($kriteria->nama_kriteria == 'Softskill' || $kriteria->nama_kriteria == 'Hardskill') {
                $aspekPenilaian['B Proses Bimbingan'][] = [
                    'nama' => $kriteria->nama_kriteria,
                    'bobot' => $kriteria->bobot_kriteria
                ];
            }
        }

        return view('KelolaPenilaianTA.views.formulir-penilaian.detail_fta_dosen_pembimbing', compact('kategori', 'aspekPenilaian'));
    }

    /**
     * Menampilkan halaman untuk menambahkan rubrik pada formulir penilaian.
     */
    public function tambahFormRubrik($idFta)
    {
        $data = DB::table('form_penilaian')
        ->join('prodi', 'form_penilaian.id_prodi', '=', 'prodi.id_prodi') // Join dengan tabel prodi
        ->where('form_penilaian.id_fta', $idFta)
        ->select(
            'form_penilaian.kode_fta',
            'form_penilaian.nama_fta',
            'prodi.nama_prodi',
            'form_penilaian.jenis_form',
            'form_penilaian.jenis_ta',
            'form_penilaian.tanggal_tenggat_pengisian',
            'form_penilaian.id_fta'
        )
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

    /**
     * Menampilkan halaman untuk mengubah rubrik pada formulir penilaian.
     */
    public function ubahFormRubrik($idFta)
    {
        $data = DB::table('form_penilaian')
            ->join('prodi', 'form_penilaian.id_prodi', '=', 'prodi.id_prodi') // Join dengan tabel prodi
            ->where('form_penilaian.id_fta', $idFta)
            ->select(
                'form_penilaian.kode_fta',
                'form_penilaian.nama_fta',
                'prodi.nama_prodi', 
                'form_penilaian.jenis_form',
                'form_penilaian.jenis_ta',
                'form_penilaian.tanggal_tenggat_pengisian',
                'form_penilaian.id_fta'
            )
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

    /**
     * Menampilkan tabel rubrik penilaian.
     */
    public function viewTabelRubrik(): View 
    {
        $data = DB::table('form_penilaian')
            ->join('prodi', 'form_penilaian.id_prodi', '=', 'prodi.id_prodi')
            ->where('jenis_form', 'penilaian')
            ->select(
                'form_penilaian.kode_fta',
                'form_penilaian.nama_fta',
                'prodi.nama_prodi',
                'form_penilaian.jenis_form',
                'form_penilaian.jenis_ta',
                'form_penilaian.tanggal_tenggat_pengisian',
                'form_penilaian.id_fta')
            ->get()
            ->map(function ($row) {
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

    /**
     * Menyimpan rubrik penilaian ke database.
     */
    public function storeRubrik(Request $request)
    {
        DB::beginTransaction();

        try {
            $kodeFta = $request->input('kode_fta');
            
            $kriterias = is_array($request->input('nama_kriteria')) 
                ? $request->input('nama_kriteria') 
                : [$request->input('nama_kriteria')];
                
            $details = $request->input('detail');
            
            foreach ($kriterias as $index => $idKriteria) {
                $detail = $details[$index] ?? '';
                
                $idRubrik = DB::table('rubrik')->insertGetId([
                    'id_kriteria' => $idKriteria,
                    'nama_rubrik' => $detail
                ]);
                
                $rentangNilai = DB::table('rentang_nilai')
                    ->whereIn('id_nilai', ['A', 'AB', 'B', 'BC', 'C', 'CD'])
                    ->select('id_nilai')
                    ->orderBy('batas_atas', 'desc')
                    ->get();
                    
                foreach ($rentangNilai as $nilai) {
                    $nilaiKey = 'nilai_' . $nilai->id_nilai;
                    $detailArray = $request->input($nilaiKey);
                    $detailNilai = $detailArray[$index] ?? '';
                    
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

    /**
     * Memperbarui rubrik penilaian di database.
     */
    public function updateRubrik(Request $request)
    {
        Log::info('Update Rubrik Request: ' . json_encode($request->all(), JSON_PRETTY_PRINT));
        DB::beginTransaction();

        try {
            $kodeFta = $request->input('kode_fta');
            
            // Ambil data kriteria dari request
            $namaFta = $request->input('nama_fta');
            $namaProdi = $request->input('nama_prodi');
            $jenisTA = $request->input('jenisTA');
            $kriterias = $request->input('nama_kriteria');
            $details = $request->input('detail');
            $status = $request->input('status');

            $formulirPenilaian = FormPenilaian::where('nama_fta', $namaFta)
                ->whereHas('prodi', function ($query) use ($namaProdi) {
                    $query->where('nama_prodi', $namaProdi);
                })
                ->where('jenis_ta', $jenisTA)
                ->where('jenis_form', 'penilaian')
                ->with('kriteriaPenilaian.rubrik.detailRubrik')
                ->get();
            Log::info('Formulir Penilaian: ' . json_encode($formulirPenilaian, JSON_PRETTY_PRINT));
            
            $globalRubrikIndex = 0;
            $globalDetailIndex = 0;
            
            foreach ($formulirPenilaian as $form) {
                foreach ($form->kriteriaPenilaian as $kriteria) {
                    foreach ($kriteria->rubrik as $rubrik) {
                        if (empty($status[$globalRubrikIndex])) {
                            Rubrik::where('id_rubrik', $rubrik->id_rubrik)->delete();
                            $globalRubrikIndex++;
                            $globalDetailIndex++;
                            continue;
                        }

                        Rubrik::where('id_rubrik', $rubrik->id_rubrik)
                            ->update([
                                'nama_rubrik' => $details[$globalRubrikIndex],
                                'id_kriteria' => $kriterias[$globalRubrikIndex]
                            ]);
                        $globalRubrikIndex++;

                        foreach ($rubrik->detailRubrik as $idx => $detailRubrik) {
                            DetailRubrik::where('id_detail_rubrik', $detailRubrik->id_detail_rubrik)
                                ->update([
                                    'detail_rubrik_penilaian' => $request->input('nilai_' . $globalDetailIndex)[$idx]
                                ]);
                        }
                        $globalDetailIndex++;
                    }
                }
            }

            $formulirPenilaian = FormPenilaian::where('nama_fta', $namaFta)
                ->whereHas('prodi', function ($query) use ($namaProdi) {
                    $query->where('nama_prodi', $namaProdi);
                })
                ->where('jenis_ta', $jenisTA)
                ->where('jenis_form', 'penilaian')
                ->with('kriteriaPenilaian.rubrik.detailRubrik')
                ->get();

            $jumlahRubrik = $formulirPenilaian->sum(function ($form) {
                return $form->kriteriaPenilaian->sum(function ($kriteria) {
                    return $kriteria->rubrik->count();
                });
            });

            // insert rubrik baru jika lebih dari jumlah rubrik yang ada
            Log::info('globalRubrikIndex: ' . $globalRubrikIndex);
            Log::info('Jumlah Kriteria: ' . count($kriterias));
            Log::info('Jumlah Rubrik: ' . $jumlahRubrik);
            Log::info('Kriteria: ' . json_encode(count($kriterias) + $jumlahRubrik - 2, JSON_PRETTY_PRINT));
            for ($i = $globalRubrikIndex; $i < count($kriterias) + $jumlahRubrik - 2; $i++) {
                if (empty($status[$i])) {
                    continue;
                }

                $idRubrik = DB::table('rubrik')->insertGetId([
                    'id_kriteria' => $kriterias[$i],
                    'nama_rubrik' => $details[$i]
                ]);

                $rentangNilai = DB::table('rentang_nilai')
                    ->whereIn('id_nilai', ['A', 'AB', 'B', 'BC', 'C', 'CD'])
                    ->select('id_nilai')
                    ->orderBy('batas_atas', 'desc')
                    ->get();

                foreach ($rentangNilai as $index => $nilai) {
                    $detailNilai = $request->input("nilai_".$globalRubrikIndex)[$index];
                    
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