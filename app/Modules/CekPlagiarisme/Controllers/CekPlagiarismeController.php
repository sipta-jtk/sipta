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
    public function getOverallSimilarity() {

    }
    
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
        $validated = $request->validate([
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
            'dokumen' => 'required|file|max:51200', // max 50MB
            'digital_receipt' => 'required|file|mimes:pdf|max:51200',
            'keywords' => 'required|string|min:1',
            'deskripsi' => 'nullable|string|max:1000',
        ]);

        DB::beginTransaction();

        try {
            // Simpan file dokumen
            $fileDokumen = $request->file('dokumen');
            $filePathDokumen = $fileDokumen->store('dokumen', 'public');
            $fileSizeDokumen = round($fileDokumen->getSize() / 1024, 2); // KB

            // Simpan file digital receipt
            $fileReceipt = $request->file('digital_receipt');
            $filePathReceipt = $fileReceipt->store('digital_receipt', 'public');
            $fileSizeReceipt = round($fileReceipt->getSize() / 1024, 2); // KB

            // Ambil data tambahan user
            $user = auth()->user();
            $nim = $user->username;
            $idKota = $user->mahasiswa->id_kota ?? null;
            $ambangBatasAktif = AmbangBatas::where('status_ambang_batas', 'digunakan')->first();

            // Simpan dokumen utama
            $dokumen = Dokumen::create([
                'judul' => $validated['judul'],
                'file_path' => $filePathDokumen,
                'user_id' => $user->id,
                'username' => $nim,
                'versi' => 1,
                'ukuran_file' => $fileSizeDokumen,
                'kategori' => 'plagiarisme',
                'deskripsi' => $validated['deskripsi'] ?? null,
                'id_kota' => $idKota,
                'highlight_dokumen' => 0,
                'status_berkas' => 'valid',
                'id_ambang_batas' => $ambangBatasAktif?->id_ambang_batas,
                'id_subkategori' => 3,
                'kode_fta' => null,
            ]);

            // Simpan digital receipt
            $dokumenReceipt = Dokumen::create([
                'judul' => $validated['judul'] . ' - Digital Receipt',
                'file_path' => $filePathReceipt,
                'user_id' => $user->id,
                'username' => $nim,
                'versi' => 1,
                'ukuran_file' => $fileSizeReceipt,
                'kategori' => 'digital_receipt',
                'deskripsi' => $validated['deskripsi'] ?? null,
                'id_kota' => $idKota,
                'highlight_dokumen' => 0,
                'status_berkas' => 'valid',
                'id_ambang_batas' => $ambangBatasAktif?->id_ambang_batas,
                'id_subkategori' => 3,
                'kode_fta' => null,
            ]);

            // Proses keywords
            $keywordsInput = array_filter(array_map('trim', explode(',', $validated['keywords'])));
            $keywordIds = [];

            foreach ($keywordsInput as $keyword) {
                $normalized = ucwords(strtolower($keyword));
                if (empty($normalized)) continue;

                $existing = Keyword::firstOrCreate(['nama_keyword' => $normalized]);
                $keywordIds[] = $existing->id_keyword;
            }

            // Simpan relasi ke dokumen utama (bukan receipt)
            foreach ($keywordIds as $keywordId) {
                DB::table('dokumen_keyword')->insert([
                    'id_dokumen' => $dokumen->id_dokumen,
                    'id_keyword' => $keywordId,
                ]);
            }

            DB::commit();

            return redirect()
                ->route('dokumen.index')
                ->with('success', 'Dokumen berhasil diunggah dan diproses.');
        } catch (\Throwable $e) {
            DB::rollBack();

            \Log::error('Gagal memproses dokumen:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return back()->withErrors([
                'error' => 'Terjadi kesalahan saat menyimpan dokumen: ' . $e->getMessage()
            ]);
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
