<?php

namespace App\Modules\Repository\Controllers;

use Illuminate\Http\Request;
use App\Models\Dokumen;
use App\Models\Mahasiswa;
use App\Models\Kota;
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
    public function index($id_kota, $kategori)
    {
        $kota = Kota::findOrFail($id_kota);
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

        // Farrel Keiza
        $nim = request('nim');
        // Jika ada NIM, ambil dokumen berdasarkan mahasiswa tertentu
        if ($nim) {
            $dokumen = Dokumen::where('kategori', $kategori)
                            ->where('username', $nim)  // Username adalah NIM mahasiswa
                            ->get();
        } else {
            // Ambil dokumen dari mahasiswa yang sedang login
            $dokumen = Dokumen::where('kategori', $kategori)
                            ->where('username', Auth::user()->username)
                            ->get();
        }

        // Kirimkan maxVersion, dokumen, status_ta dan data lainnya ke view
        return view('Repository.views.cruddDokumen', compact('dokumen', 'kategori', 'subkategoris', 'maxVersion', 'status_ta', 'kota'));
    }



    /**
     * Menyimpan dokumen baru ke database.
     */
    public function store(Request $request, $kategori)
    {
        // Daftar kategori yang diperbolehkan
        $allowedKategori = [
            'laporan', 'poster', 'presentasi',
            'fta', 'artefak', 'cover_abstrak', 'artikel_ilmiah',
            'source_code', 'link_source_code', 'hasil_revisi_sidang',
            'seminar1', 'seminar2', 'seminar3'
        ];

        // Cek kategori valid
        if (!in_array($kategori, $allowedKategori)) {
            return back()->with('error', 'Kategori tidak valid.');
        }

        // dd($request->all());
        $mahasiswa = Mahasiswa::where('nim', Auth::user()->username)->first();

        // Ambil id_kota mahasiswa yang sedang login
        // $id_kota_user = $mahasiswa ? $mahasiswa->id_kota : null;
        // Validasi dasar 
        if ($kategori == 'link_source_code') {
            $request->validate([
                'judul' => 'required|string|max:255',
                'repository_url' => 'required|url|max:255',
                'deskripsi' => 'required|string',
            ]);
        } else {
            $request->validate([
                'judul' => 'required|string|max:255',
                'file' => 'required|file|mimes:pdf,doc,docx,jpg,png,jpeg,xlsx|max:15360',
                'deskripsi' => 'required|string',
            ]);
        }

        // Validasi tambahan khusus
        if ($kategori === 'fta') {
            $request->validate([
                'kode_fta' => 'required|string|max:10',
            ]);
        }

        if ($kategori === 'artefak') {
            $request->validate([
                'id_subkategori' => 'required|exists:subkategori,id_subkategori',
            ]);
        }

        // Ambil versi terbaru
        $latestVersion = Dokumen::where('kategori', $kategori)
            ->when($kategori === 'fta', function ($query) use ($request) {
                return $query->where('kode_fta', $request->kode_fta);
            })
            ->orderByDesc('versi')
            ->value('versi');

        $newVersion = $latestVersion ? $latestVersion + 1 : 1;

        

        // Ambil info user (asumsi login mahasiswa)
        $user = auth()->user();
        $id_kota = $user->mahasiswa->id_kota ?? 1;
        $username = $user->username ?? 'guest';

        // Siapkan data untuk insert
        $data = [
            'judul' => $request->judul,
            'versi' => $newVersion,
            'kategori' => $kategori,
            'deskripsi' => $request->deskripsi,
            'id_kota' => $mahasiswa->id_kota, // Sesuaikan dengan kebutuhan
            'id_subkategori' => $kategori === 'artefak' ? $request->id_subkategori : null, // Sesuaikan dengan kebutuhan
            'status_berkas' => 'valid',
            'username' => $username,
            'created_at' => now()->timezone('Asia/Jakarta'),
            'updated_at' => now()->timezone('Asia/Jakarta'),
        ];

        // Handle file upload
        if ($kategori === 'link_source_code') {
            $data['file_path'] = $request->repository_url;
            $data['ukuran_file'] = 0; // URL doesn't have a file size
        } else {
            $file = $request->file('file');
            $fileName = 'dokumen/' . $file->hashName(); // Simpan hanya path relatif
            $file->storeAs('public/dokumen', $file->hashName());
            $fileSize = $file->getSize() / 1024; // Ukuran file dalam KB

            $data['file_path'] = $fileName;
            $data['ukuran_file'] = $fileSize;
        }

        // Jika kategori adalah "fta", tambahkan kode_fta ke data
        if ($kategori === 'fta') {
            $data['kode_fta'] = $request->kode_fta;
        }

        Dokumen::create($data);

        return redirect()->route('Repository.index.kota', [
            'id_kota' => auth()->user()->mahasiswa->id_kota ?? 1,
            'kategori' => $kategori
        ])->with('success', 'Dokumen berhasil diunggah');
        
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

        return redirect()->route('Repository.index.kota', [
            'id_kota' => auth()->user()->mahasiswa->id_kota ?? auth()->user()->dosen->id_kota ?? 1,
            'kategori' => $kategori
        ])->with('success', 'Dokumen berhasil dihapus');
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
    public function dashboard($id_kota)
    {
        $user = auth()->user();

        // Cek jika user adalah mahasiswa
        if ($user->role_user === 'mahasiswa') {
            $mahasiswa = $user->mahasiswa;
            if ($mahasiswa->id_kota != $id_kota) {
                abort(403, 'Anda tidak boleh mengakses repository kota lain.');
            }
        }

        // Jika dosen, lewati validasi karena bisa akses semua
        $kota = Kota::with('mahasiswa')->findOrFail($id_kota);
        $nims = $kota->mahasiswa->pluck('nim')->toArray();
        $dokumen = Dokumen::whereIn('username', $nims)->get();

        $data = collect([
            'Laporan Tugas Akhir' => [
                ['key' => 'laporan_revisi_sidang', 'label' => 'Laporan Revisi Sidang', 'url' => route('Repository.index.kota', ['id_kota' => $id_kota, 'kategori' => 'hasil_revisi_sidang'])],
                ['key' => 'laporan_seminar_3', 'label' => 'Seminar 3', 'url' => route('Repository.index.kota', ['id_kota' => $id_kota, 'kategori' => 'seminar1'])],
                ['key' => 'laporan_seminar_2', 'label' => 'Seminar 2', 'url' => route('Repository.index.kota', ['id_kota' => $id_kota, 'kategori' => 'seminar2'])],
                ['key' => 'laporan_seminar_1', 'label' => 'Seminar 1', 'url' => route('Repository.index.kota', ['id_kota' => $id_kota, 'kategori' => 'seminar3'])],
            ],
            'Dokumen Pendukung' => [
                ['key' => 'cover_abstrak', 'label' => 'Cover dan Abstrak', 'url' => route('Repository.index.kota', ['id_kota' => $id_kota, 'kategori' => 'cover_abstrak'])],
                ['key' => 'artikel', 'label' => 'Artikel Ilmiah', 'url' => route('Repository.index.kota', ['id_kota' => $id_kota, 'kategori' => 'artikel_ilmiah'])],
                ['key' => 'poster', 'label' => 'Poster', 'url' => route('Repository.index.kota', ['id_kota' => $id_kota, 'kategori' => 'poster'])],
                ['key' => 'fta', 'label' => 'FTA', 'url' => route('Repository.index.kota', ['id_kota' => $id_kota, 'kategori' => 'fta'])],
            ],
            'Kode Sumber' => [
                ['key' => 'source_code', 'label' => 'Source Code', 'url' => route('Repository.index.kota', ['id_kota' => $id_kota, 'kategori' => 'source_code'])],
                ['key' => 'link_source_code', 'label' => 'Link Source Code', 'url' => route('Repository.index.kota', ['id_kota' => $id_kota, 'kategori' => 'link_source_code'])],
            ],
            'Artefak' => [
                ['key' => 'artefak', 'label' => 'Artefak', 'url' => route('Repository.index.kota', ['id_kota' => $id_kota, 'kategori' => 'artefak'])],
            ],
        ]);

        return view('Repository.views.dashboard', compact('data', 'kota'));
    }



    public function lihatRepositoryMahasiswa($nim)
    {
        // Ambil data mahasiswa berdasarkan NIM
        $mahasiswa = Mahasiswa::where('nim', $nim)->firstOrFail();

        // Ambil semua dokumen yang sudah diupload oleh mahasiswa tersebut
        $dokumen = Dokumen::where('username', $nim)->get();

        // Ambil daftar kategori untuk ditampilkan
        $kategoriDokumen = ['hasil_revisi_sidang', 'seminar1', 'seminar2', 'seminar3', 'cover_abstrak', 'artikel_ilmiah','fta', 'poster', 'artefak', 'link_source_code', 'source_code'];

        return view('Repository.views.dosen_dashboard_mahasiswa', compact('mahasiswa', 'dokumen', 'kategoriDokumen'));
    }

    public function list_kelompok_ta()
    {
        // Ambil daftar kelompok berdasarkan tabel `kota` yang memiliki mahasiswa terkait
        $kelompok = Kota::join('mahasiswa', 'kota.id_kota', '=', 'mahasiswa.id_kota')
                        ->select('kota.id_kota', 'kota.judul_ta', 'mahasiswa.nim')
                        ->orderBy('kota.id_kota')
                        ->get();
    
        return view('Repository.views.list_kelompok_ta', compact('kelompok'));
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
