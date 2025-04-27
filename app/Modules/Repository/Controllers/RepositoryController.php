<?php

namespace App\Modules\Repository\Controllers;

use Illuminate\Http\Request;
use App\Models\Dokumen;
use App\Models\Mahasiswa;
use App\Models\Kota;
use App\Models\Subkategori;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Storage;
use App\Models\LogAktivitas;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

Carbon::setlocale(LC_TIME, 'id');

class RepositoryController extends Controller
{

    // Akmal Gonniyu Hartono
    /**
     * Menampilkan daftar dokumen Seminar 1.
     */
    public function index($id_kota, $kategori, Request $request)
    {
        try {
            $user = auth()->user();
            $kota = Kota::findOrFail($id_kota);

            // Ambil status_ta jika mahasiswa
            $mahasiswa = Mahasiswa::where('nim', $user->username)->first();
            $status_ta = $mahasiswa?->status_ta;

            // Base query
            $query = Dokumen::where('kategori', $kategori);

            // Jika mahasiswa, hanya tampilkan dokumen miliknya
            if ($user->can('mahasiswa_ta')) {
                $query->where('username', $user->username);
            }

            // Jika dosen, tampilkan semua dokumen di kota tersebut
            if ($user->can('akses-sidebar-repo-dosen')) {
                $query->where('id_kota', $id_kota);
            }

            // Apply filters
            if ($request->filled('search')) {
                $query->where(function ($q) use ($request) {
                    $q->where('judul', 'like', '%' . $request->search . '%')
                        ->orWhere('versi', 'like', '%' . $request->search . '%');
                });
            }

            if ($request->filled('versi')) {
                $query->where('versi', $request->versi);
            }

            if ($request->filled('tanggal_dibuat')) {
                $query->whereDate('created_at', $request->tanggal_dibuat);
            }

            // Get final data with only the latest 7 versions
            $dokumen = $query->orderBy('versi', 'desc')->take(7)->get();

            // Ambil subkategori jika kategori artefak
            $subkategoris = $kategori === 'artefak' ? Subkategori::all() : [];

            // Ambil versi maksimum
            $maxVersion = Dokumen::where('kategori', $kategori)->max('versi');

            return view('Repository.views.cruddDokumen', compact(
                'dokumen',
                'kategori',
                'subkategoris',
                'maxVersion',
                'status_ta',
                'kota'
            ));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memuat data: ' . $e->getMessage());
        }
    }

    public function store(Request $request, $kategori)
    {
        try {
            // Daftar kategori yang diperbolehkan
            $allowedKategori = [
                'laporan',
                'poster',
                'presentasi',
                'fta',
                'artefak',
                'cover_abstrak',
                'artikel_ilmiah',
                'source_code',
                'link_source_code',
                'hasil_revisi_sidang',
                'seminar1',
                'seminar2',
                'seminar3'
            ];

            if (!in_array($kategori, $allowedKategori)) {
                return back()->with('error', 'Kategori tidak valid.');
            }

            // Validasi umum
            $isLink = $kategori === 'link_source_code';

            $request->validate([
                'judul' => 'required|string|max:255',
                'deskripsi' => 'required|string',
                $isLink ? 'repository_url' : 'file' => $isLink
                    ? 'required|url|max:255'
                    : 'required|file|mimes:pdf,doc,docx,jpg,png,jpeg,xlsx|max:15360'
            ]);

            // Validasi khusus
            if ($kategori === 'fta') {
                $request->validate(['kode_fta' => 'required|string|max:10']);
            }

            if ($kategori === 'artefak') {
                $request->validate(['id_subkategori' => 'required|exists:subkategori,id_subkategori']);
            }

            // Ambil user login
            $user = auth()->user();
            $id_kota = $user->mahasiswa->id_kota ?? $user->dosen->id_kota ?? null;
            $username = $user->username;

            // Cari versi terakhir
            $latestVersion = Dokumen::where('kategori', $kategori)
                ->when($kategori === 'fta', fn($q) => $q->where('kode_fta', $request->kode_fta))
                ->when($kategori === 'artefak', fn($q) => $q->where('id_subkategori', $request->id_subkategori))
                ->orderByDesc('versi')
                ->value('versi');

            $newVersion = $latestVersion ? $latestVersion + 1 : 1;

            // Siapkan data dasar
            $data = [
                'judul' => $request->judul,
                'versi' => $newVersion,
                'kategori' => $kategori,
                'deskripsi' => $request->deskripsi,
                'id_kota' => $id_kota,
                'id_subkategori' => $kategori === 'artefak' ? $request->id_subkategori : null,
                'status_berkas' => 'valid',
                'username' => $username,
                'created_at' => Carbon::now()->translatedFormat('H:i d F Y'),
                'updated_at' => Carbon::now()->translatedFormat('H:i d F Y'),
            ];

            // Handle upload file atau url
            if ($kategori === 'link_source_code') {
                $data['file_path'] = $request->repository_url;
                $data['ukuran_file'] = 0;
            } else {
                $file = $request->file('file');
                $fileName = 'dokumen/' . $file->hashName();
                $file->storeAs('public/dokumen', $file->hashName());

                $data['file_path'] = $fileName;
                $data['ukuran_file'] = $file->getSize() / 1024;
            }

            // Tambah kode_fta jika diperlukan
            if ($kategori === 'fta') {
                $data['kode_fta'] = $request->kode_fta;
            }

            // Simpan ke database
            Dokumen::create($data);

            return redirect()->route('Repository.index.kota', [
                'id_kota' => $id_kota,
                'kategori' => $kategori
            ])->with('success', 'Dokumen berhasil diunggah');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan dokumen: ' . $e->getMessage());
        }
    }


    public function edit($kategori, $id)
    {
        try {
            $dokumen = Dokumen::where('id_dokumen', $id)->where('kategori', $kategori)->firstOrFail();
            return view('repository.edit', compact('dokumen', 'kategori'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memuat halaman edit: ' . $e->getMessage());
        }
    }

    /**
     * Mengupdate dokumen yang sudah ada.
     */
    public function update(Request $request, $kategori, $id)
    {
        try {
            $dokumen = Dokumen::where('id_dokumen', $id)->where('kategori', $kategori)->firstOrFail();

            $request->validate([
                'judul' => 'required|string|max:255',
                'deskripsi' => 'required|string',
                'file' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
            ]);

            $dokumen->judul = $request->judul;
            $dokumen->deskripsi = $request->deskripsi;
            $dokumen->updated_at = now()->timezone('Asia/Jakarta');

            $dokumen->save();

            return redirect()->route('Repository.index.kota', [
                'id_kota' => auth()->user()->mahasiswa->id_kota ?? auth()->user()->dosen->id_kota ?? 1,
                'kategori' => $kategori
            ])->with('success', 'Dokumen berhasil diperbarui');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memperbarui dokumen: ' . $e->getMessage());
        }
    }

    /**
     * Menghapus dokumen dari database dan storage.
     */
    public function destroy($kategori, $id)
    {
        try {
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
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menghapus dokumen: ' . $e->getMessage());
        }
    }


    /**
     * Mengunduh dokumen.
     */
    public function download($kategori, $id)
    {
        try {
            $dokumen = Dokumen::where('id_dokumen', $id)->where('kategori', $kategori)->firstOrFail();

            if (!$dokumen->file_path || !Storage::disk('public')->exists($dokumen->file_path)) {
                return redirect()->route('Repository.index', $kategori)->with('error', 'File tidak ditemukan');
            }

            $extension = pathinfo(storage_path('app/public/' . $dokumen->file_path), PATHINFO_EXTENSION);
            $filename = $dokumen->judul . '-v' . $dokumen->versi . '.' . $extension;

            return response()->download(storage_path('app/public/' . $dokumen->file_path), $filename);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat mengunduh dokumen: ' . $e->getMessage());
        }
    }

    // Farrel Keiza Muhammad Yamin Putra
    public function dashboard($id_kota)
    {
        try {
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
                    ['key' => 'laporan_seminar_3', 'label' => 'Seminar 3', 'url' => route('Repository.index.kota', ['id_kota' => $id_kota, 'kategori' => 'seminar3'])],
                    ['key' => 'laporan_seminar_2', 'label' => 'Seminar 2', 'url' => route('Repository.index.kota', ['id_kota' => $id_kota, 'kategori' => 'seminar2'])],
                    ['key' => 'laporan_seminar_1', 'label' => 'Seminar 1', 'url' => route('Repository.index.kota', ['id_kota' => $id_kota, 'kategori' => 'seminar1'])],
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
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memuat dashboard: ' . $e->getMessage());
        }
    }



    public function lihatRepositoryMahasiswa($nim)
    {
        try {
            // Ambil data mahasiswa berdasarkan NIM
            $mahasiswa = Mahasiswa::where('nim', $nim)->firstOrFail();

            // Ambil semua dokumen yang sudah diupload oleh mahasiswa tersebut
            $dokumen = Dokumen::where('username', $nim)->get();

            // Ambil daftar kategori untuk ditampilkan
            $kategoriDokumen = ['hasil_revisi_sidang', 'seminar1', 'seminar2', 'seminar3', 'cover_abstrak', 'artikel_ilmiah', 'fta', 'poster', 'artefak', 'link_source_code', 'source_code'];

            return view('Repository.views.dosen_dashboard_mahasiswa', compact('mahasiswa', 'dokumen', 'kategoriDokumen'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memuat repository mahasiswa: ' . $e->getMessage());
        }
    }

    public function list_kelompok_ta()
    {
        try {
            // Ambil daftar kelompok berdasarkan tabel `kota` yang memiliki mahasiswa terkait
            $kelompok = Kota::join('mahasiswa', 'kota.id_kota', '=', 'mahasiswa.id_kota')
                ->select('kota.id_kota', 'kota.judul_ta', 'mahasiswa.nim')
                ->orderBy('kota.id_kota')
                ->get();

            return view('Repository.views.list_kelompok_ta', compact('kelompok'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memuat daftar kelompok TA: ' . $e->getMessage());
        }
    }

    // Saabiq Muhyiyuddin Aulawi
    public function logAktivitas(Request $request)
    {
        // Start with a base query
        $query = LogAktivitas::with('user', 'kota');

        // Apply filters if provided
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->whereHas('user', function ($userQuery) use ($request) {
                    $userQuery->where('nama', 'like', '%' . $request->search . '%');
                })
                    ->orWhere('action', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('kota_id')) {
            $query->where('kota_id', $request->kota_id);
        }

        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('waktu_aktivitas', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('waktu_aktivitas', '<=', $request->date_to);
        }

        // Get filtered results
        $logAktivitas = $query->orderBy('waktu_aktivitas', 'desc')->paginate(15);

        // Get data for filter dropdowns
        $users = User::orderBy('nama')->get();
        $kotas = KoTA::orderBy('nama_kota')->get();
        $actions = LogAktivitas::distinct('action')->pluck('action');

        // Return view with all needed data
        return view('Repository.views.log_aktivitas', compact('logAktivitas', 'users', 'kotas', 'actions'));
    }


    // Fungsi untuk menampilkan halaman Monitoring Penyimpanan
    public function monitoringPenyimpanan()
    {
        // Ambil data dokumen dari database, grup berdasarkan kategori dan subkategori serta total ukuran file per kategori dan subkategori
        $penyimpanan = Dokumen::select('kategori', 'id_subkategori', DB::raw('SUM(ukuran_file) as total_ukuran'))
            ->groupBy('kategori', 'id_subkategori')
            ->with('subkategori')  // Pastikan mengambil relasi subkategori
            ->get();

        // Kirim data penyimpanan ke view
        return view('Repository.views.monitoring_penyimpanan', compact('penyimpanan'));
    }


    // Muhammad Fahrizal Alzaelani

    // Muhammad Alvyn Adhianto
    public function v0()
    {
        try {
            return view('Repository.views.aksesD0');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memuat halaman: ' . $e->getMessage());
        }
    }

    public function v1()
    {
        try {
            return view('Repository.views.aksesD1');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memuat halaman: ' . $e->getMessage());
        }
    }

    public function v2()
    {
        try {
            return view('Repository.views.aksesD2');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memuat halaman: ' . $e->getMessage());
        }
    }

    public function p1()
    {
        try {
            return view('Repository.views.aksesP0');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memuat halaman: ' . $e->getMessage());
        }
    }
}