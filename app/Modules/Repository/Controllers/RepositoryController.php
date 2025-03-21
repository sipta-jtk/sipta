<?php

namespace App\Modules\Repository\Controllers;

use Illuminate\Http\Request;
use App\Models\Dokumen;
use App\Models\Mahasiswa;
use Illuminate\Support\Facades\Auth;
use App\Models\Subkategori;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Storage;

class RepositoryController extends Controller
{

    // Akmal Gonniyu Hartono
    /**
     * Menampilkan daftar dokumen Seminar 1.
     */
    public function index($kategori)
    {
        // Ambil data mahasiswa yang sedang login
        $mahasiswa = Mahasiswa::where('nim', Auth::user()->username)->first();

        // Ambil status_ta dari mahasiswa yang sedang login
        $status_ta = $mahasiswa ? $mahasiswa->status_ta : null;

        // Ambil id_kota mahasiswa yang sedang login
        $id_kota_user = $mahasiswa ? $mahasiswa->id_kota : null;

        // Ambil parameter filter dari request
        $search = request('search');
        $versi = request('versi');
        $tanggal_dibuat = request('tanggal_dibuat');

        // Mulai query untuk mengambil dokumen
        $dokumen = Dokumen::where('kategori', $kategori);

        // Terapkan filter jika ada
        if ($search) {
            $dokumen->where('judul', 'like', '%' . $search . '%');
        }

        if ($versi) {
            // Menangani versi (contoh: V1, V2, ...)
            $dokumen->where('versi', $versi);
        }

        if ($tanggal_dibuat) {
            // Filter berdasarkan tanggal dibuat
            $dokumen->whereDate('created_at', $tanggal_dibuat);
        }

        // Ambil data dokumen sesuai dengan filter
        $dokumen = $dokumen->get();

        // Ambil subkategori jika kategori adalah 'artefak'
        $subkategoris = [];
        if ($kategori === 'artefak') {
            $subkategoris = Subkategori::all();
        }

        // Ambil versi tertinggi yang ada di kategori ini
        $maxVersion = Dokumen::where('kategori', $kategori)
            ->max('versi'); // Mengambil versi terbesar yang ada

        // Kirimkan maxVersion, dokumen, status_ta dan data lainnya ke view
        return view('Repository.views.cruddDokumen', compact('dokumen', 'kategori', 'subkategoris', 'maxVersion', 'status_ta'));
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
        if ($kategori == 'fta') {
            $latestVersion = Dokumen::where('kategori', $kategori)
                ->where('kode_fta', $request->kode_fta)
                ->orderBy('versi', 'desc')
                ->value('versi');
        } else {
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

    // Farrel Keiza Muhammad Yamin Putra
    public function dashboard()
    {
        $data = collect([
            'Laporan Tugas Akhir' => [
                ['key' => 'laporan_revisi_sidang', 'label' => 'Laporan Tugas Akhir versi hasil revisi sidang', 'url' => route('Repository.index', ['kategori' => 'hasil_revisi_sidang'])],
                ['key' => 'laporan_seminar_3', 'label' => 'Laporan Tugas Akhir versi hasil seminar 3', 'url' => route('Repository.index', ['kategori' => 'seminar1'])],
                ['key' => 'laporan_seminar_2', 'label' => 'Laporan Tugas Akhir versi hasil seminar 2', 'url' => route('Repository.index', ['kategori' => 'seminar2'])],
                ['key' => 'laporan_seminar_1', 'label' => 'Laporan Tugas Akhir versi hasil seminar 1', 'url' => route('Repository.index', ['kategori' => 'seminar3'])],
            ],
            'Dokumen Pendukung' => [
                ['key' => 'cover_abstrak', 'label' => 'Cover dan Abstrak', 'url' => route('Repository.index', ['kategori' => 'cover_abstrak'])],
                ['key' => 'artikel', 'label' => 'Artikel Ilmiah', 'url' => route('Repository.index', ['kategori' => 'artikel_ilmiah'])],
                ['key' => 'poster', 'label' => 'Poster', 'url' => route('Repository.index', ['kategori' => 'poster'])],
                ['key' => 'fta', 'label' => 'FTA', 'url' => route('Repository.index', ['kategori' => 'fta'])],
            ],
            'Kode Sumber' => [
                ['key' => 'source_code', 'label' => 'Source Code', 'url' => route('Repository.index', ['kategori' => 'source_code'])],
                ['key' => 'link_source_code', 'label' => 'Link Source Code', 'url' => route('Repository.index', ['kategori' => 'link_source_code'])],
            ],
            'Artefak' => [
                ['key' => 'artefak', 'label' => 'Artefak', 'url' => route('Repository.index', ['kategori' => 'artefak'])],
            ]
        ]);

        return view('Repository.views.dashboard', compact('data'));
    }


    public function list_kelompok_ta()
    {
        return view('Repository.views.list_kelompok_ta');
    }


    // Saabiq Muhyiyuddin Aulawi

    // Muhammad Fahrizal Alzaelani

    // Muhammad Alvyn Adhianto
    public function v0()
    {
        return view('Repository.views.aksesD0');
    }
    public function v1()
    {
        return view('Repository.views.aksesD1');
    }
    public function v2()
    {
        return view('Repository.views.aksesD2');
    }
    public function p1()
    {
        return view('Repository.views.aksesP0');
    }
}
