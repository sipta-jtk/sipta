<?php

namespace App\Modules\CekPlagiarisme\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\View\View;
use App\Models\Dokumen;
use App\Models\Keyword;
use App\Models\AmbangBatas;
use App\Models\Kota;
use App\Models\Prodi;
use App\Models\Mahasiswa;
use App\Models\ListJurnalPlagiarisme;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Smalot\PdfParser\Parser;
use App\Models\LogAktivitas;
use Carbon\Carbon;
use Spatie\PdfToText\Pdf;
use Illuminate\Support\Facades\Crypt;

Carbon::setLocale('id');



class CekPlagiarismeController extends Controller
{
    private function getRedirectPath(): string
    {
        $prefix = env('PREFIX_URL');
        return $prefix ? "/{$prefix}/cek-plagiarisme" : "/cek-plagiarisme";
    }

    function extractOverallSimilarity($pdfText)
    {
        if (preg_match('/Overall\s+Similarity\s*[:\-]?\s*(\d{1,3})%/i', $pdfText, $matches)) {
            return (int)$matches[1];
        }

        if (preg_match('/(\d{1,3})%\s+Overall\s+Similarity/i', $pdfText, $matches)) {
            return (int)$matches[1];
        }

        return null;
    }

    function extractSourceLinksWithPercentages($pdfText)
    {
        // Hapus newline agar lebih mudah diproses
        $cleanText = preg_replace("/\r|\n/", ' ', $pdfText);

        // Hanya cocokkan domain yang valid dan diikuti persentase
        preg_match_all('/(?<source>[a-zA-Z0-9.-]+\.[a-z]{2,})(?:\s+\d+)?\s+(?<percent><1%|[1-9][0-9]?%)/i', $cleanText, $matches);

        $sources = $matches['source'];
        $percents = $matches['percent'];

        $result = [];

        foreach ($sources as $index => $source) {
            $source = strtolower(trim($source)); // pakai lowercase biar konsisten
            $percent = $percents[$index];

            if (!isset($result[$source])) {
                $result[$source] = [];
            }

            if (!in_array($percent, $result[$source])) {
                $result[$source][] = $percent;
            }
        }

        return $result;
    }


    function convertToFloat($percent)
    {
        if (strpos($percent, '<') !== false) {
            return 0.99; // atau 0.5 tergantung preferensi
        }
        return floatval(str_replace('%', '', $percent));
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
        } elseif (auth()->user()->role_user === 'admin') {
            // Jika user adalah admin, ambil semua dokumen tanpa filter kota
            $dokumen = Dokumen::with('AmbangBatas', 'User', 'ReviewDosenPembimbing')
                ->where('kategori', 'plagiarisme')
                ->get();
        } elseif (auth()->user()->dosen->role_dosen === 'koordinator_ta') {
            // Jika user adalah koordinator TA, ambil semua dokumen tanpa filter kota
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
                'kota' => $item->kota ? $item->kota->nama_kota : 'Tidak Diketahui',
                'judul' => $item->judul,
                'waktu' => $item->created_at
                    ? Carbon::parse($item->created_at)->translatedFormat('d F Y H:i')
                    : Carbon::now()->translatedFormat('d F Y H:i'),
                'penulis' => $item->user ? $item->user->nama : 'Tidak Diketahui',
                'persentase_plagiarisme' => $item->persentase_plagiarisme,
                'ambang_batas' => $item->ambangBatas ? $item->ambangBatas->ambang_batas : null, // Ambil nilai ambang batas
                'status' => $this->getStatus($item->status_plagiarisme), // Default 20 jika tidak ada
                'review' => $item->reviewDosenPembimbing->first() ? $item->reviewDosenPembimbing->first()->review : null,
                'id_kota' => $item->id_kota,
                'id_prodi' => $item->user ? $item->user->mahasiswa->id_prodi : null,
                'tahun_angkatan' => $item->user ? $item->user->mahasiswa->tahun_masuk : null
            ];
        });

        return response()->json($data);
    }

    private function getStatus($statusPlagiarisme)
    {
        if ($statusPlagiarisme === null) {
            return '<span class="badge badge-warning">Processing</span>';
        } elseif ($statusPlagiarisme === 'tidak_plagiarisme') {
            return '<span class="badge badge-success">Tidak Plagiat</span>';
        } else {
            return '<span class="badge badge-danger">Plagiat</span>';
        }
    }

    public function show($id): View
    {
        return view('CekPlagiarisme.views.Detail', [
            'id' => $id
        ]);
    }

    public function process(Request $request)
    {
        if (!$request->isMethod('post')) {
            Log::warning('GET request tidak sah ke /cek-plagiarisme/process', [
                'ip' => $request->ip(),
                'agent' => $request->userAgent(),
                'user_id' => auth()->id(),
            ]);
            abort(405);
        }
        // Validasi input
        $validated = $request->validate([
            'judul' => [
                'required',
                'string',
                'max:255',
                function ($attribute, $value, $fail) {
                    if (str_word_count($value) > 20) {
                        $fail('Judul TA tidak boleh lebih dari 20 kata.');
                    }
                }
            ],
            'dokumen' => 'required|file|max:51200', // max 50MB
            'digital_receipt' => 'required|file|mimes:pdf|max:51200',
            'keywords' => 'required|string|min:1',
            'deskripsi' => 'nullable|string|max:5000',
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

            // Simpan jumlah kata dan halaman
            $jumlahKata = (int) $request->input('jumlah_kata', 0);
            $jumlahHalaman = (int) $request->input('jumlah_halaman', 0);

            // Debug: Log file paths and sizes
            Log::info('File paths and sizes', [
                'dokumen_path' => $filePathDokumen,
                'dokumen_size' => $fileSizeDokumen,
                'receipt_path' => $filePathReceipt,
                'receipt_size' => $fileSizeReceipt,
                'jumlah_kata' => $jumlahKata,
                'jumlah_halaman' => $jumlahHalaman
            ]);

            // Ambil data tambahan user
            $user = auth()->user();
            $nim = $user->username;
            $idKota = $user->mahasiswa->id_kota ?? null;
            $ambangBatasAktif = AmbangBatas::where('status_ambang_batas', 'digunakan')->first();
            $nim = auth()->user()->username;
            $idKota = auth()->user()->mahasiswa->id_kota ?? null;

            // Debug: Log ambang batas and user info
            Log::info('User and threshold info', [
                'ambang_batas_id' => $ambangBatasAktif?->id_ambang_batas,
                'nim' => $nim,
                'id_kota' => $idKota,
            ]);

            $pathToPdf = storage_path('app/public/' . $filePathDokumen);
            $text = (new Pdf('pdftotext'))->setPdf($pathToPdf)->text();

            // Detailed logging for PDF parsing
            Log::info('PDF parsing complete', [
                'file_path' => $filePathDokumen,
                'text_length' => strlen($text),
                'ISI TEXT' => $text,
                'storage_path' => storage_path('app/public/' . $filePathDokumen)
            ]);

            // Fix: Use $this-> to call class methods
            $similarity = $this->extractOverallSimilarity($text);
            $sources = $this->extractSourceLinksWithPercentages($text);

            // Log the extracted data with more context
            Log::info('Extracted plagiarism data from PDF', [
                'similarity_percentage' => $similarity,
                'sources_count' => count($sources),
                'sources' => $sources,
                'document_title' => $validated['judul'],
                'user_id' => $user->id,
                'username' => $nim
            ]);

            if ($similarity < $ambangBatasAktif->ambang_batas) {
                $statusPlagiarisme = 'tidak_plagiarisme';
            } else {
                $statusPlagiarisme = 'plagiarisme';
            }

            // Create document with detailed attribute logging
            $dokumen = Dokumen::create([
                'judul' => $validated['judul'],
                'file_path' => $filePathDokumen,
                'user_id' => $user->id,
                'username' => $nim,
                'versi' => 1,
                'ukuran_file' => $fileSizeDokumen,
                'jumlah_kata' => $jumlahKata,
                'jumlah_halaman' => $jumlahHalaman,
                'kategori' => 'plagiarisme',
                'deskripsi' => $validated['deskripsi'] ?? null,
                'id_kota' => $idKota,
                'highlight_dokumen' => 0,
                'status_plagiarisme' => $statusPlagiarisme,
                'status_berkas' => 'valid',
                'id_ambang_batas' => $ambangBatasAktif?->id_ambang_batas,
                'id_subkategori' => 3,
                'kode_fta' => null,
                'persentase_plagiarisme' => $similarity,
            ]);

            // Log dokumen creation
            Log::info('Dokumen created successfully', [
                'dokumen_id' => $dokumen->id_dokumen,
                'judul' => $dokumen->judul,
                'user_id' => $user->id,
                'username' => $nim,
                'jumlah_kata' => $dokumen->jumlah_kata,
                'jumlah_halaman' => $dokumen->jumlah_halaman,
                'status_plagiarisme' => $dokumen->status_plagiarisme,
                'persentase_plagiarisme' => $dokumen->persentase_plagiarisme,
                'ambang_batas_id' => $dokumen->id_ambang_batas,
                'id_kota' => $dokumen->id_kota
            ]);

            // Debug isi hasil parsing
            Log::info('Extracted sources:', $sources);

            foreach ($sources as $link => $persentaseList) {
                foreach ($persentaseList as $percent) {
                    // Log sebelum simpan ke DB
                    Log::info('Mencoba simpan:', [
                        'link_jurnal' => $link,
                        'judul' => '-',
                        'persentase_kemunculan' => $percent
                    ]);

                    try {
                        DB::table('list_jurnal_plagiarisme')->insert([
                            'link_jurnal' => $link,
                            'judul' => '-',
                            'persentase_kemunculan' => (float) str_replace(['<', '%'], '', $percent),
                            'id_dokumen' => $dokumen->id_dokumen,
                        ]);
                    } catch (\Exception $e) {
                        // Log error jika gagal
                        Log::error('Gagal simpan jurnal:', [
                            'link_jurnal' => $link,
                            'percent' => $percent,
                            'error' => $e->getMessage(),
                        ]);
                    }
                }
            }

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

            // Dalam CekPlagiarismeController
            try {
                LogAktivitas::create([
                    'username' => auth()->user()->username,
                    'id_kota' => auth()->user()->mahasiswa->id_kota ?? null,
                    'id_dokumen' => $dokumen->id_dokumen,
                    'action' => 'upload', // Pastikan menggunakan nilai enum yang valid
                    'waktu_aktivitas' => now()
                ]);
            } catch (\Exception $e) {
                Log::warning('Gagal mencatat log aktivitas: ' . $e->getMessage());
            }

            return redirect($this->getRedirectPath())->with('success', 'Dokumen berhasil diunggah.');
        } catch (\Throwable $e) {
            DB::rollBack();

            Log::error('Gagal memproses dokumen:', [
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
        if (auth()->user()->role_user === 'admin') {
            $kotas = Kota::all()->map(function ($kota) {
                // Mengembalikan id_kota dan nama_kota
                return [
                    'id_kota' => $kota->id_kota,
                    'nama_kota' => $kota->nama_kota,
                ];
            });
        } elseif (auth()->user()->role_user === 'dosen') {
            if (auth()->user()->dosen->role_dosen === 'koordinator_ta') {
                $kotas = Kota::all()->map(function ($kota) {
                    // Mengembalikan id_kota dan nama_kota
                    return [
                        'id_kota' => $kota->id_kota,
                        'nama_kota' => $kota->nama_kota,
                    ];
                });
            } elseif (auth()->user()->dosen->role_dosen === 'dosen' || auth()->user()->dosen->role_dosen === 'kajur') {
                $kotas = auth()->user()
                    ->dosen
                    ->preferensiKota
                    ->map(function ($preferensiKota) {
                        // Mengembalikan id_kota dan nama_kota
                        return [
                            'id_kota' => $preferensiKota->id_kota,
                            'nama_kota' => $preferensiKota->kota->nama_kota,
                        ];
                    });
            }
        }

        Log::info('Mengambil daftar kota untuk user', [
            'user_id' => auth()->id(),
            'kotas_count' => $kotas->count(),
            'kotas' => $kotas
        ]);
        // Mengembalikan hasil dalam format JSON
        return response()->json($kotas);
    }

    public function getProdi()
    {
        // Mengambil id_prodi dan nama_prodi 
        if (auth()->user()->role_user === 'admin') {
            $prodis = Prodi::all()->map(function ($prodi) {
                // Mengembalikan id_prodi dan nama_prodi
                return [
                    'id_prodi' => $prodi->id_prodi,
                    'nama_prodi' => $prodi->nama_prodi,
                ];
            });
        } elseif (auth()->user()->role_user === 'dosen') {
            if (auth()->user()->dosen->role_dosen === 'koordinator_ta') {
                $prodis = Kota::all()->map(function ($prodi) {
                    // Mengembalikan id_prodi dan nama_prodi
                    return [
                        'id_prodi' => $prodi->id_prodi,
                        'nama_prodi' => $prodi->nama_prodi,
                    ];
                });
            } elseif (auth()->user()->dosen->role_dosen === 'dosen' || auth()->user()->dosen->role_dosen === 'kajur') {
                $prodis = auth()->user()
                    ->dosen
                    ->preferensiKota
                    ->flatMap(function ($preferensiKota) {
                        // Mengambil prodi dari relasi kota->mahasiswa
                        return $preferensiKota->kota->mahasiswa
                            ->flatMap(function ($mahasiswa) {
                                return [
                                    [
                                        'id_prodi' => $mahasiswa->prodi->id_prodi,
                                        'nama_prodi' => $mahasiswa->prodi->nama_prodi,
                                    ]
                                ];
                            });
                    })->unique('id_prodi')->values();
            }
        }

        Log::info('Mengambil daftar prodi untuk user', [
            'user_id' => auth()->id(),
            'prodi_count' => $prodis->count(),
            'prodi' => $prodis
        ]);
        // Mengembalikan hasil dalam format JSON
        return response()->json($prodis);
    }

    public function getTahunAngkatan()
    {
        // Mengambil tahun_angkatan dan nama_prodi 
        if (auth()->user()->role_user === 'admin') {
            $years = Mahasiswa::all()->map(function ($year) {
                // Mengembalikan tahun_angkatan dan nama_year
                return [
                    'tahun_angkatan' => $year->tahun_masuk,
                ];
            });
        } elseif (auth()->user()->role_user === 'dosen') {
            if (auth()->user()->dosen->role_dosen === 'koordinator_ta') {
                $years = Mahasiswa::all()->map(function ($year) {
                    // Mengembalikan tahun_angkatan 
                    return [
                        'tahun_angkatan' => $year->tahun_masuk,
                    ];
                });
            } elseif (auth()->user()->dosen->role_dosen === 'dosen' || auth()->user()->dosen->role_dosen === 'kajur') {
                $years = auth()->user()
                    ->dosen
                    ->preferensiKota
                    ->map(function ($preferensiKota) {
                        // Mengembalikan tahun_angkatan dan nama_year
                        return [
                            'tahun_angkatan' => $preferensiKota->tahun_masuk,
                        ];
                    });
            }
        }

        Log::info('Mengambil daftar kota untuk user', [
            'user_id' => auth()->id(),
            'year_count' => $years->count(),
            'year' => $years
        ]);
        // Mengembalikan hasil dalam format JSON
        return response()->json($years);
    }

    public function encryptId(Request $request)
    {
        if (!$request->has('id')) {
            return response()->json(['error' => 'ID tidak ditemukan'], 400);
        }

        try {
            $encryptedId = Crypt::encryptString($request->input('id'));
            return response()->json(['encrypted_id' => $encryptedId]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Gagal mengenkripsi ID'], 500);
        }
    }
}
