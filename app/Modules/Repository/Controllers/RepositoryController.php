<?php

namespace App\Modules\Repository\Controllers;

use Illuminate\Http\Request;
use App\Models\Dokumen;
use App\Models\Subkategori;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Storage;

class RepositoryController extends Controller
{

    public function dashboard()
    {
        // Daftar kategori yang akan ditampilkan
        $kategoriList = [
            'hasil_revisi_sidang',
            'seminar3',
            'seminar2',
            'seminar1',
            'cover_abstrak',
            'artikel_ilmiah',
            'poster',
            'fta',
            'source_code',
            'link_source_code',
            'artefak',
        ];

        return view('Repository.views.dashboard', compact('kategoriList'));
    }
    /**
     * Menampilkan daftar dokumen Seminar 1.
     */
    public function index($kategori)
    {
        $dokumen = Dokumen::where('kategori', $kategori)->get();
        $subkategoris = [];
        if ($kategori === 'artefak') {
            $subkategoris = Subkategori::all(); // Ambil semua data subkategori
        }

        return view('Repository.views.cruddSeminar1', compact('dokumen', 'kategori', 'subkategoris'));
    }

    /**
     * Menyimpan dokumen baru ke database.
     */
    public function store(Request $request, $kategori)
    {
        // dd($request->all());
        // Validasi dasar 
        $request->validate([
            'judul' => 'required|string|max:255',
            'file' => 'required|file|mimes:pdf,doc,docx|max:2048',
            'deskripsi' => 'required|string',
        ]);

        // Jika kategori adalah "fta", tambahkan validasi untuk kode_fta
        if ($kategori === 'fta') {
            $request->validate([
                'kode_fta' => 'required|string|max:10', // Sesuaikan aturan validasi sesuai kebutuhan
            ]);
        }

        if ($kategori === 'artefak') {
            $request->validate([
                'id_subkategori' => 'required|exists:subkategori,id_subkategori',
            ]);
        }

        // Get the latest version for documents with same kategori "laporan"
        if($kategori == 'fta'){
            $latestVersion = Dokumen::where('kategori', $kategori)
            ->where('kode_fta', $request->kode_fta)
            ->orderBy('versi', 'desc')
            ->value('versi');
        }else{
            $latestVersion = Dokumen::where('kategori', $kategori)
            ->orderBy('versi', 'desc')
            ->value('versi');
        }
        // $latestVersion = Dokumen::where('kategori', $kategori)
        //     ->orderBy('versi', 'desc')
        //     ->value('versi');

        if (!$latestVersion) {
            $newVersion = '1';
        } else {
            $newVersion = $latestVersion + 1;
        }

        // Handle file upload
        $file = $request->file('file');
        $fileName = 'dokumen/' . $file->hashName(); // Simpan hanya path relatif
        $file->storeAs('public/dokumen', $file->hashName());
        $fileSize = $file->getSize() / 1024; // Ukuran file dalam KB

        // Data yang akan disimpan ke database
        $data = [
            'judul' => $request->judul,
            'versi' => $newVersion,
            'kategori' => $kategori,
            'deskripsi' => $request->deskripsi,
            'id_kota' => 1, // Sesuaikan dengan kebutuhan
            'id_subkategori' => $kategori === 'artefak' ? $request->id_subkategori : null, // Sesuaikan dengan kebutuhan
            'file_path' => $fileName,
            'ukuran_file' => $fileSize,
            'status_berkas' => 'valid',
            'username' => '221524059', // Sesuaikan dengan kebutuhan
            'created_at' => now()->timezone('Asia/Jakarta'),
            'updated_at' => now()->timezone('Asia/Jakarta'),
        ];

        // Jika kategori adalah "fta", tambahkan kode_fta ke data
        if ($kategori === 'fta') {
            $data['kode_fta'] = $request->kode_fta;
        }

        // Simpan data ke database
        Dokumen::create($data);

        return redirect()->route('Repository.index', $kategori)->with('success', 'Dokumen berhasil diunggah');
    }

    public function edit($kategori, $id)
    {
        $dokumen = Dokumen::where('id_dokumen', $id)->where('kategori', $kategori)->firstOrFail();
        return view('repository.edit', compact('dokumen', 'kategori'));
    }

    /**
     * Mengupdate dokumen yang sudah ada.
     */
    public function update(Request $request, $kategori, $id)
    {
        $dokumen = Dokumen::where('id_dokumen', $id)->where('kategori', $kategori)->firstOrFail();

        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'file' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
        ]);

        $dokumen->judul = $request->judul;
        $dokumen->deskripsi = $request->deskripsi;
        $dokumen->updated_at = now()->timezone('Asia/Jakarta');

        // Jika ada file baru yang diupload
        // if ($request->hasFile('file')) {
        //     if ($dokumen->file_path && Storage::disk('public')->exists($dokumen->file_path)) {
        //         Storage::disk('public')->delete($dokumen->file_path);
        //     }

        //     $file = $request->file('file');
        //     $filePath = $file->store('dokumen', 'public');
        //     $dokumen->file_path = $filePath;
        // }

        $dokumen->save();

        return redirect()->route('Repository.index', $kategori)->with('success', 'Dokumen berhasil diperbarui');
    }

    /**
     * Menghapus dokumen dari database dan storage.
     */
    public function destroy($kategori, $id)
    {
        $dokumen = Dokumen::where('id_dokumen', $id)->where('kategori', $kategori)->firstOrFail();

        // Delete the file if it exists
        if ($dokumen->file_path && Storage::disk('public')->exists($dokumen->file_path)) {
            Storage::disk('public')->delete($dokumen->file_path);
        }

        $dokumen->delete();

        return redirect()->route('Repository.index', $kategori)->with('success', 'Dokumen berhasil dihapus');
    }

    /**
     * Mengunduh dokumen.
     */
    public function download($kategori, $id)
    {
        $dokumen = Dokumen::where('id_dokumen', $id)->where('kategori', $kategori)->firstOrFail();

        if (!$dokumen->file_path || !Storage::disk('public')->exists($dokumen->file_path)) {
            return redirect()->route('Repository.index', $kategori)->with('error', 'File tidak ditemukan');
        }

        $extension = pathinfo(storage_path('app/public/' . $dokumen->file_path), PATHINFO_EXTENSION);
        $filename = $dokumen->judul . '-v' . $dokumen->versi . '.' . $extension;

        return response()->download(storage_path('app/public/' . $dokumen->file_path), $filename);
    }
}
