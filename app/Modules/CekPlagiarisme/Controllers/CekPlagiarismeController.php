<?php

namespace App\Modules\CekPlagiarisme\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\View\View;
use App\Models\Dokumen;
use App\Models\Keyword;
use App\Models\AmbangBatas;
use App\Models\Kota;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\DB;


use Carbon\Carbon;

Carbon::setLocale('id');

class CekPlagiarismeController extends Controller
{
    public function getData()
    {
        // Ambil id kota dari user yang sedang login, serta Ambil data dokumen kategori laporan beserta relasi ke ambang batas, user dan review dosen pembimbing
        if (auth()->user()->role_user === 'mahasiswa') {
            $idKota = auth()->user()->mahasiswa->id_kota;
            $dokumen = Dokumen::with('AmbangBatas', 'User', 'ReviewDosenPembimbing')
                ->where('kategori', 'plagiarisme')
                ->where('id_kota', $idKota)
                ->get();
        } else if (auth()->user()->dosen->role_dosen === 'koordinator_ta') {
            $dokumen = Dokumen::with('AmbangBatas', 'User', 'ReviewDosenPembimbing')
                ->where('kategori', 'plagiarisme')
                ->get();
        } else {
            $idKota = auth()->user()
                ->dosen
                ->preferensiKota
                ->map(function ($preferensiKota) {
                    return $preferensiKota->id_kota;
                });
            $dokumen = Dokumen::with('AmbangBatas', 'User', 'ReviewDosenPembimbing')
                ->where('kategori', 'plagiarisme')
                ->whereIn('id_kota', $idKota)
                ->get();
        }

        // Format data agar sesuai dengan struktur jsGrid
        $data = $dokumen->map(function ($item) {
            return [
                'id_dokumen' => $item->id_dokumen,
                'judul' => $item->judul,
                'waktu' => $item->created_at
                    ? Carbon::parse($item->created_at)->translatedFormat('H:i d F Y')
                    : Carbon::now()->translatedFormat('H:i d F Y'),
                'penulis' => $item->user ? $item->user->nama : 'Tidak Diketahui',
                'persentase_plagiarisme' => $item->persentase_plagiarisme,
                'ambang_batas' => $item->ambangBatas ? $item->ambangBatas->ambang_batas : null, // Ambil nilai ambang batas
                'status' => $this->getStatus($item->persentase_plagiarisme, $item->ambangBatas ? $item->ambangBatas->ambang_batas : 20), // Default 20 jika tidak ada
                'review' => $item->reviewDosenPembimbing->first() ? $item->reviewDosenPembimbing->first()->review : null,
                'id_kota' => $item->id_kota
            ];
        });

        return response()->json($data);
    }

    private function getStatus($persentase, $ambangBatas)
    {
        if ($persentase === null) {
            return '<span class="badge badge-warning">Processing</span>';
        } elseif ($persentase < $ambangBatas) {
            return '<span class="badge badge-success">Tidak Plagiat</span>';
        } else {
            return '<span class="badge badge-danger">Plagiat</span>';
        }
    }
    public function show($id): View
    {
        return view('CekPlagiarisme.views.detail', [
            'id' => $id
        ]);
    }


    public function process(Request $request)
    {
        // Validasi input
        $request->validate([
            'judul' => [
                'required',
                'string',
                'max:255',
                function ($attribute, $value, $fail) {
                    if (str_word_count($value) > 20) {
                        $fail('Judul dokumen tidak boleh lebih dari 20 kata.');
                    }
                }
            ],
            'dokumen' => 'required|file|mimes:pdf,docx|max:15360',
            'digital_receipt' => 'required|file|mimes:pdf,docx|max:15360',
            'keywords' => 'required|string|min:1',
        ]);

        // Debug: Log input data
        \Log::info('Processing document upload request', [
            'judul' => $request->judul,
            'dokumen_name' => $request->file('dokumen')->getClientOriginalName(),
            'digital_receipt_name' => $request->file('digital_receipt')->getClientOriginalName(),
            'keywords' => $request->keywords,
            'user_id' => auth()->id(),
            'nim' => auth()->user()->username,
        ]);

        DB::beginTransaction();

        try {
            // Simpan file utama (hasil plagiarisme)
            $fileDokumen = $request->file('dokumen');
            $filePathDokumen = $fileDokumen->store('dokumen', 'public');
            $fileSizeDokumen = round($fileDokumen->getSize() / 1024, 2); // KB

            // Simpan file digital receipt
            $fileReceipt = $request->file('digital_receipt');
            $filePathReceipt = $fileReceipt->store('digital_receipt', 'public');
            $fileSizeReceipt = round($fileReceipt->getSize() / 1024, 2); // KB

            // Debug: Log file paths
            \Log::info('Files uploaded successfully', [
                'dokumen_path' => $filePathDokumen,
                'dokumen_size' => $fileSizeDokumen,
                'receipt_path' => $filePathReceipt,
                'receipt_size' => $fileSizeReceipt,
            ]);

            $ambangBatasAktif = AmbangBatas::where('status_ambang_batas', 'digunakan')->first();
            $nim = auth()->user()->username;
            $idKota = auth()->user()->mahasiswa->id_kota ?? null;

            // Debug: Log ambang batas and user info
            \Log::info('User and threshold info', [
                'ambang_batas_id' => $ambangBatasAktif?->id_ambang_batas,
                'nim' => $nim,
                'id_kota' => $idKota,
            ]);

            // Simpan dokumen hasil plagiarisme
            $dokumen = Dokumen::create([
                'judul' => $request->judul,
                'file_path' => $filePathDokumen,
                'user_id' => auth()->id(),
                'username' => $nim,
                'versi' => 1,
                'ukuran_file' => $fileSizeDokumen,
                'kategori' => 'plagiarisme',
                'deskripsi' => $request->deskripsi,
                'id_kota' => $idKota,
                'highlight_dokumen' => 0,
                'status_berkas' => 'valid',
                'id_ambang_batas' => $ambangBatasAktif?->id_ambang_batas,
                'id_subkategori' => 3,
                'kode_fta' => null,
            ]);

            // Debug: Log dokumen plagiarisme
            \Log::info('Dokumen plagiarisme saved', [
                'id_dokumen' => $dokumen->id_dokumen,
                'judul' => $dokumen->judul,
                'kategori' => $dokumen->kategori,
            ]);

            // Simpan dokumen digital receipt
            $dokumenReceipt = Dokumen::create([
                'judul' => $request->judul . ' - Digital Receipt',
                'file_path' => $filePathReceipt,
                'user_id' => auth()->id(),
                'username' => $nim,
                'versi' => 1,
                'ukuran_file' => $fileSizeReceipt,
                'kategori' => 'digital_receipt',
                'deskripsi' => $request->deskripsi,
                'id_kota' => $idKota,
                'highlight_dokumen' => 0,
                'status_berkas' => 'valid',
                'id_ambang_batas' => $ambangBatasAktif?->id_ambang_batas,
                'id_subkategori' => 3,
                'kode_fta' => null,
            ]);

            // Debug: Log digital receipt dokumen
            \Log::info('Dokumen digital receipt saved', [
                'id_dokumen' => $dokumenReceipt->id_dokumen,
                'judul' => $dokumenReceipt->judul,
                'kategori' => $dokumenReceipt->kategori,
            ]);

            // Tangani keywords (dipisahkan koma)
            $keywordsInput = explode(',', $request->keywords);
            $keywordIds = [];

            foreach ($keywordsInput as $keyword) {
                $normalized = ucwords(strtolower(trim($keyword))); // Capitalize setiap kata
                if (empty($normalized)) continue;

                $existing = Keyword::firstOrCreate(['nama_keyword' => $normalized]);
                $keywordIds[] = $existing->id_keyword;
            }

            // Debug: Log keywords
            \Log::info('Keywords processed', [
                'keyword_ids' => $keywordIds,
                'raw_keywords' => $keywordsInput,
            ]);

            // Attach ke dokumen menggunakan direct DB insert ke pivot table (dokumen_keyword)
            foreach ($keywordIds as $keywordId) {
                // Insert langsung ke pivot table, sesuai dengan nama tabel yang ada
                DB::table('dokumen_keyword')->insert([
                    'id_dokumen' => $dokumen->id_dokumen,
                    'id_keyword' => $keywordId,
                ]);
            }
            
            // Debug: Log after direct pivot insert
            \Log::info('Keywords attached to document using direct pivot insert');

            DB::commit();
            \Log::info('Transaction committed successfully');

            // Generate log output in storage/logs/laravel.log
            \Log::info('Document processing completed successfully', [
                'id_dokumen_plagiarisme' => $dokumen->id_dokumen,
                'id_dokumen_receipt' => $dokumenReceipt->id_dokumen,
                'judul' => $dokumen->judul,
                'user_id' => auth()->id(),
                'username' => $nim,
            ]);

            return view('CekPlagiarisme.views.DaftarDokumen');
        } catch (\Throwable $e) {
            DB::rollBack();
            // Debug: Log error
            \Log::error('Failed to save document', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return back()->withErrors(['error' => 'Gagal menyimpan dokumen dan keyword: ' . $e->getMessage()]);
        }
    }


    public function getKota()
    {
        // Mengambil id_kota dan nama_kota dari relasi preferensiKota -> kota

        if (auth()->user()->dosen->role_dosen === 'koordinator_ta') {
            $kotas = Kota::all()->map(function ($kota) {
                // Mengembalikan id_kota dan nama_kota
                return [
                    'id_kota' => $kota->id_kota,
                    'nama_kota' => $kota->nama_kota, // Pastikan relasi dengan model Kota
                ];
            });
        } else
            $kotas = auth()->user()
                ->dosen
                ->preferensiKota
                ->map(function ($preferensiKota) {
                    // Mengembalikan id_kota dan nama_kota
                    return [
                        'id_kota' => $preferensiKota->id_kota,
                        'nama_kota' => $preferensiKota->kota->nama_kota, // Pastikan relasi dengan model Kota
                    ];
                });

        // Mengembalikan hasil dalam format JSON
        return response()->json($kotas);
    }
}
