<?php

namespace App\Modules\CekPlagiarisme\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\View\View;
use App\Modules\CekPlagiarisme\Services\PlagiarismChecker;
use App\Models\Dokumen;
use App\Models\AmbangBatas;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Smalot\PdfParser\Parser as PdfParser;
use ZipArchive;
use App\Services\Notifikasi;

use Carbon\Carbon;

Carbon::setLocale('id');

// console::info
// import this console::info
use Illuminate\Support\Facades\Log as console;

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

    // ! Aplikasi dari open source
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
            'dokumen' => 'required|file|mimes:pdf,docx,txt|max:15360',
        ]);

        // Simpan file yang diunggah ke storage
        $file = $request->file('dokumen');
        $filePath = $file->store('dokumen', 'public');

        // Kirim file ke server Django untuk pengecekan plagiarisme
        $plagiarismUrl = config('app.plagiarism_url', env('PLAGIARISM_URL'));
        try {
            $client = new Client();
            $response = $client->post("{$plagiarismUrl}/filetest/", [
                'multipart' => [
                    [
                        'name'     => 'docfile',
                        'contents' => fopen($file->getPathname(), 'r'),
                        'filename' => $file->getClientOriginalName(),
                    ],
                ],
            ]);

            $data = json_decode($response->getBody()->getContents(), true);
            $percentage = isset($data['percent']) ? number_format($data['percent'], 2) : null;
            $link = $data['link'] ?? null;
            // console::info("RESPONSE DARI DJANGO: ", $data);
            dump($data);
        } catch (RequestException $e) {
            return back()->withErrors(['error' => 'Gagal menghubungi server Django.']);
        }

        // Simpan informasi file ke database
        $fileSizeInKB = round($file->getSize() / 1024, 2);
        $ambangBatasAktif = AmbangBatas::where('status_ambang_batas', 'digunakan')->first();
        $nim = auth()->user()->username;
        $statusPlagiarisme = ($percentage > ($ambangBatasAktif->nilai ?? 0)) ? 'plagiarisme' : 'tidak_plagiarisme';

        Dokumen::create([
            'judul' => $request->judul,
            'file_path' => $filePath,
            'user_id' => auth()->id(),
            'username' => $nim,
            'status_plagiarisme' => $statusPlagiarisme,
            'persentase_plagiarisme' => $percentage,
            'versi' => 1,
            'ukuran_file' => $fileSizeInKB,
            'kategori' => 'plagiarisme',
            'id_kota' => auth()->user()->mahasiswa->id_kota ?? null,
            'highlight_dokumen' => 0,
            'status_berkas' => 'valid',
            'id_ambang_batas' => $ambangBatasAktif?->id_ambang_batas,
            'id_subkategori' => 3,
            'kode_fta' => null,
        ]);

        //Notifikasi Cek Plagiarisme Selesai
        // Kirim ke mahasiswa pengirim
        Notifikasi::kirim(
            '[Pemberitahuan] Pengajuan Jadwal Seminar/Sidang Baru Oleh Mahasiswa!',
            $nim,
            []
        );
        // Kirim ke dosen pembimbing dan 2 anggota kelompok TA lainnya jika ada
        $mahasiswa = \App\Models\Mahasiswa::where('nim', $nim)->first();
        if ($mahasiswa) {
            // Asumsi ada relasi pengajuanPembimbing dan anggotaKelompok pada model Mahasiswa
            $pengajuan = $mahasiswa->pengajuanPembimbing()->latest()->first();
            $pembimbing = $pengajuan?->pembimbing1?->user?->username;
            if ($pembimbing) {
                Notifikasi::kirim(
                    '[Pemberitahuan] Pengajuan Jadwal Seminar/Sidang Baru Oleh Mahasiswa!',
                    $pembimbing,
                    []
                );
            }
            // Anggota kelompok TA lain (selain pengirim)
            $anggotaKelompok = $mahasiswa->anggotaKelompokTA()
                ->where('nim', '!=', $nim)
                ->limit(2)
                ->pluck('nim');
            if ($anggotaKelompok->count() > 0) {
                $anggota2 = $anggotaKelompok[0] ?? null;
                if ($anggota2) {
                    Notifikasi::kirim(
                        '[Pemberitahuan] Pengajuan Jadwal Seminar/Sidang Baru Oleh Mahasiswa!',
                        $anggota2,
                        []
                    );
                }
                $anggota3 = $anggotaKelompok[1] ?? null;
                if ($anggota3) {
                    Notifikasi::kirim(
                        '[Pemberitahuan] Pengajuan Jadwal Seminar/Sidang Baru Oleh Mahasiswa!',
                        $anggota3,
                        []
                    );
                }
            }
        }

        // Tampilkan hasil ke view PengecekanTugasAkhir
        return view('CekPlagiarisme.views.PengecekanTugasAkhir', compact('percentage', 'link'));
    }


    // ! Aplikasi berbayar
    // public function process(Request $request)
    // {
    //     // Validasi input
    //     $request->validate([
    //         'judul' => [
    //             'required',
    //             'string',
    //             'max:255',
    //             function ($attribute, $value, $fail) {
    //                 if (str_word_count($value) > 20) {
    //                     $fail('Judul dokumen tidak boleh lebih dari 20 kata.');
    //                 }
    //             }
    //         ],
    //         'dokumen' => 'required|file|mimes:pdf,docx,txt|max:15360',
    //     ]);

    //     // Simpan file ke storage
    //     $file = $request->file('dokumen');
    //     $filePath = $file->store('dokumen', 'public');

    //     try {
    //         $text = '';

    //         // Ekstrak teks dari file
    //         $extension = $file->getClientOriginalExtension();
    //         if ($extension === 'txt') {
    //             $text = file_get_contents($file->getPathname());
    //         } elseif ($extension === 'pdf') {
    //             $parser = new PdfParser();
    //             $pdf = $parser->parseFile($file->getPathname());
    //             $text = $pdf->getText();
    //         } elseif ($extension === 'docx') {
    //             $zip = new ZipArchive;
    //             if ($zip->open($file->getPathname()) === true) {
    //                 $xml = $zip->getFromName('word/document.xml');
    //                 $text = strip_tags(str_replace(["</w:p>", "</w:tbl>"], "\n", $xml));
    //                 $zip->close();
    //             } else {
    //                 throw new \Exception("Gagal membaca file DOCX.");
    //             }
    //         } else {
    //             throw new \Exception("Format dokumen tidak didukung untuk ekstraksi teks.");
    //         }

    //         if (empty(trim($text))) {
    //             return back()->withErrors(['error' => 'Gagal membaca isi dokumen. Pastikan file memiliki isi teks.']);
    //         }

    //         $client = new Client([
    //             'headers' => [
    //                 'X-API-TOKEN' => 'LHrwX5_0EyVEEOw74qJ4ff1Bah0eNmqM'
    //             ]
    //         ]);

    //         // Kirim teks ke PlagiarismCheck API
    //         $uploadResponse = $client->post('https://plagiarismcheck.org/api/v1/text', [
    //             'form_params' => [
    //                 'language' => 'en',
    //                 'text' => $text,
    //             ]
    //         ]);

    //         $uploadData = json_decode($uploadResponse->getBody()->getContents(), true);
    //         $textId = $uploadData['data']['text']['id'] ?? null;

    //         if (!$textId) {
    //             throw new \Exception("Gagal mendapatkan ID teks dari API.");
    //         }

    //         // Tunggu 3 detik (opsional, jika perlu delay untuk pemrosesan)
    //         sleep(3);

    //         // Dapatkan hasil report
    //         $reportResponse = $client->get("https://plagiarismcheck.org/api/v1/text/report/{$textId}");
    //         $reportData = json_decode($reportResponse->getBody()->getContents(), true);

    //         $percentage = isset($reportData['data']['report']['percent']) 
    //         ? number_format($reportData['data']['report']['percent'], 2) 
    //         : null;

    //         $link = "https://plagiarismcheck.org/report/{$textId}";

    //         $sources = [];

    //         if (isset($reportData['data']['report_data']['sources'])) {
    //             foreach ($reportData['data']['report_data']['sources'] as $source) {
    //                 $sources[] = [
    //                     'percent' => $source['percent'],
    //                     'url' => $source['source'],
    //                 ];
    //             }
    //         }


    //     } catch (RequestException $e) {
    //         return back()->withErrors(['error' => 'Gagal menghubungi API PlagiarismCheck.']);
    //     } catch (\Exception $e) {
    //         return back()->withErrors(['error' => $e->getMessage()]);
    //     }

    //     // Simpan ke database
    //     $fileSizeInKB = round($file->getSize() / 1024, 2);
    //     $ambangBatasAktif = AmbangBatas::where('status_ambang_batas', 'digunakan')->first();
    //     $nim = auth()->user()->username;
    //     $statusPlagiarisme = ($percentage > ($ambangBatasAktif->nilai ?? 0)) ? 'plagiarisme' : 'tidak_plagiarisme';

    //     Dokumen::create([
    //         'judul' => $request->judul,
    //         'file_path' => $filePath,
    //         'user_id' => auth()->id(),
    //         'username' => $nim,
    //         'status_plagiarisme' => $statusPlagiarisme,
    //         'persentase_plagiarisme' => $percentage,
    //         'versi' => 1,
    //         'ukuran_file' => $fileSizeInKB,
    //         'kategori' => 'plagiarisme',
    //         'id_kota' => auth()->user()->mahasiswa->id_kota ?? null,
    //         'highlight_dokumen' => 0,
    //         'status_berkas' => 'valid',
    //         'id_ambang_batas' => $ambangBatasAktif?->id_ambang_batas,
    //         'id_subkategori' => 3,
    //         'kode_fta' => null,
    //     ]);

    //     // Tampilkan hasil ke view
    //     return view('CekPlagiarisme.views.PengecekanTugasAkhir', compact('percentage', 'link', 'sources'));
    // }


    public function getKota()
    {
        // Mengambil id_kota dan nama_kota dari relasi preferensiKota -> kota
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
