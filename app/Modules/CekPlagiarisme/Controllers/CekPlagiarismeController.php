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
        // Ambil id kota dari user yang sedang login
        if (auth()->user()->role_user === 'mahasiswa') {
            $idKota = auth()->user()->mahasiswa->id_kota;
        } else {
            $idKota = auth()->user()  
                ->dosen                 
                ->alokasiDosen        
                ->first()
                ->pengajuanPembimbing   
                ->kota                  
                ->id_kota;              

        }

        // Ambil data dokumen kategori laporan beserta relasi ke ambang batas, user dan review dosen pembimbing
        $dokumen = Dokumen::with('AmbangBatas', 'User', 'ReviewDosenPembimbing')
            ->where('kategori', 'laporan')
            ->where('id_kota', $idKota)
            ->get();

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
                'review' => $item->reviewDosenPembimbing->first() ? $item->reviewDosenPembimbing->first()->review : null
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
        $request->validate([
            'file' => 'required|mimes:pdf,doc,docx|max:15360',
        ]);

        $filePath = $request->file('file')->store('uploads');
        $checker = new PlagiarismChecker();

        // Ekstrak teks dari file
        $result = $checker->checkPlagiarism(storage_path('app/' . $filePath));

        return view('CekPlagiarisme.views.view', [
            'results' => $result['results'],
            'percentage' => $result['percentage']
        ]);
    }
}
