<?php

namespace App\Modules\CekPlagiarisme\Controllers;

set_time_limit(300);

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\View\View;
use App\Modules\CekPlagiarisme\Services\PlagiarismChecker;
use App\Models\Dokumen;

class CekPlagiarismeController extends Controller
{
    public function getData()
    {
        // Ambil id kota dari user yang sedang login, serta Ambil data dokumen kategori laporan beserta relasi ke ambang batas, user dan review dosen pembimbing
        if (auth()->user()->role_user === 'mahasiswa') {
            $idKota = auth()->user()->mahasiswa->id_kota;
            $dokumen = Dokumen::with('AmbangBatas', 'User', 'ReviewDosenPembimbing')
                ->where('kategori', 'laporan')
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
                ->where('kategori', 'laporan')
                ->whereIn('id_kota', $idKota)
                ->get();
        }

        // Format data agar sesuai dengan struktur jsGrid
        $data = $dokumen->map(function ($item) {
            return [
                'id_dokumen' => $item->id_dokumen,
                'judul' => $item->judul,
                'waktu' => $item->created_at->format('Y-m-d H:i:s'),
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
                    // Menambahkan validasi custom untuk memastikan jumlah kata tidak lebih dari 20
                    if (str_word_count($value) > 20) {
                        $fail('Judul dokumen tidak boleh lebih dari 20 kata.');
                    }
                }
            ],
            'dokumen' => 'required|file|mimes:pdf,docx|max:15360', // Validasi file: PDF, DOCX, max 15MB
        ]);

        // Proses file yang diunggah
        $filePath = $request->file('dokumen')->store('uploads', 'public');

        // Lakukan pengecekan plagiarisme
        $checker = new PlagiarismChecker();
        $result = $checker->checkPlagiarism(storage_path('app/public/' . $filePath));

        // Simpan dokumen dan hasil pengecekan ke database
        Dokumen::create([
            'judul' => $request->judul,
            'file_path' => $filePath,
            'user_id' => auth()->id(),
            'persentase_plagiarisme' => $result['percentage'],
            'status' => $result['status'],
        ]);

        // Kembalikan hasil pengecekan
        return view('CekPlagiarisme.views.DaftarDokumen', [
            'results' => $result['results'],
            'percentage' => $result['percentage']
        ]);
    }

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
