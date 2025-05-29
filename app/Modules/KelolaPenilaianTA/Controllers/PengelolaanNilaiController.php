<?php

namespace App\Modules\KelolaPenilaianTA\Controllers;

use App\Modules\Controller;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Exports\RekapitulasiNilaiExport;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Support\Facades\Log;
use App\Models\Mahasiswa;
use App\Models\KategoriPenilaian;
use App\Models\FormPenilaian;
use App\Models\Kota;
use App\Models\DetailFeedback;
use App\Models\NilaiKategori;
use App\Services\Notifikasi;
use App\Models\Prodi;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use App\Models\User;
use App\Models\Dosen;
use Illuminate\Support\Facades\DB;

class PengelolaanNilaiController extends Controller
{
    /**
     * Menampilkan halaman kelola penilaian
     * 
     */
    public function kelolaNilai(): View
    {
        $kategoriPenilaian = FormPenilaian::whereIn('nama_fta', ['Seminar I', 'Seminar II', 'Seminar III', 'Sidang Akhir'])
            ->where(function ($query) {
                $query->where('jenis_form', 'penilaian')
                    ->orWhere('nama_fta', 'Seminar I'); // Biarkan "Seminar I" tanpa filter jenis_form
            })
            ->where(function ($query) {
                $query->whereNot('jenis_ta', 'penelitian')
                    ->orWhereNull('jenis_ta'); // Sertakan nilai NULL
            })
            ->with('prodi', 'kategoriPenilaian')
            ->orderBy('id_prodi')
            ->orderBy('nama_fta')
            ->get();

        $prodiList = $kategoriPenilaian->pluck('prodi')->unique('nama_prodi')->values();

        return view('KelolaPenilaianTA.views.pengelolaan-nilai.kelola_penilaian_ta', [
            'kategoriPenilaian' => $kategoriPenilaian,
            'prodiList' => $prodiList,
        ]);
    }

    /**
     * Menampilkan detail nilai mahasiswa
     * 
     * @param string $namaFta
     */
    public function detailNilaiMahasiswa($namaFta, $idProdi): View
    {
        $nip = auth()->user()->username;
        $namaFtaSlug = Str::slug($namaFta, ' ');

        $detailInformasiFta = FormPenilaian::where('nama_fta', $namaFtaSlug)
            ->orderBy('nama_fta')
            ->where('id_prodi', $idProdi)
            ->with('prodi')
            ->get();

        $idFtaPenilaian = $detailInformasiFta->where('jenis_form', 'penilaian')
            ->pluck('id_fta');

        $idFtaFeedback = $detailInformasiFta->where('jenis_form', 'feedback')
            ->pluck('id_fta');

        $detailNilaiMahasiswa = Mahasiswa::where('id_prodi', $idProdi)
            ->where('mahasiswa.status_ta', 'mahasiswa_ta')
            ->whereHas('kota', function ($query) {
                $query->where('status_kota', 'aktif');
            })
            ->with([
                'nilaiKategori' => function ($query) use ($idFtaPenilaian) {
                    $query->whereHas('kategoriPenilaian', function ($q) use ($idFtaPenilaian) {
                        $q->whereIn('id_fta', $idFtaPenilaian);
                    });
                },
                'nilaiKategori.dosen',
                'user',
                'kota.detailFeedback' => function ($query) use ($idFtaFeedback) {
                    $query->whereHas('aspekFeedback', function ($q) use ($idFtaFeedback) {
                        $q->whereIn('id_fta', $idFtaFeedback);
                    });
                },
                'kota.detailFeedback'
            ])
            ->get();

        $namaFtaLower = strtolower($namaFtaSlug);
        if (in_array($namaFtaLower, ['seminar i', 'seminar ii'])) {
            $viewBlade = 'KelolaPenilaianTA.views.pengelolaan-nilai.detail_nilai_mahasiswa_sem1_2';
        } else {
            $viewBlade = 'KelolaPenilaianTA.views.pengelolaan-nilai.detail_nilai_mahasiswa';
        }


        return view($viewBlade, [
            'detailNilaiMahasiswa' => $detailNilaiMahasiswa,
            'detailInformasiFta' => $detailInformasiFta->first(),
            'namaFta' => $namaFta,
            'nip' => $nip,
        ]);
    }


    public function import(Request $request, $namaFta, $idProdi)
    {
        $namaFtaSlug = Str::slug($namaFta, '-');
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
        ]);

        $file = $request->file('file');
        $spreadsheet = IOFactory::load($file->getPathname());
        $sheet = $spreadsheet->getActiveSheet();
        $columns = $sheet->toArray();

        // Skip the header row
        array_shift($columns);

        $data = [];
        foreach ($columns as $col) {
            // dd($col); // Tampilkan data untuk debugging

            if (empty($col[0]) || empty($col[1]) || empty($col[2])) {
                continue; // Skip baris jika ada data yang kosong
            }
            $data[] = [
                'nim' => $col[0],
                'nama' => $col[1],
                'kelompok' => $col[2],
                'penguji1' => $col[3],
                'penguji2' => $col[4],
                'penguji3' => $col[5],
                'nilaiPenguji1' => $col[6],
                'nilaiPenguji2' => $col[7],
                'nilaiPenguji3' => $col[8],
            ];
        }
        session(['importedData' => $data]); // Simpan langsung ke session

        return redirect()->route('previewDataNilai', [
            'namaFta' => $namaFta,
            'idProdi' => $idProdi,
        ]);
    }

    /**
     * Menampilkan halaman pratinjau nilai
     * 
     */
    public function previewDataNilai($namaFta, $idProdi)
    {
        $data = session('importedData', []);
        $prodi = Prodi::all();
        return view('KelolaPenilaianTA.views.pengelolaan-nilai.preview-data-nilai', compact('data', 'prodi', 'namaFta', 'idProdi'));
    }

    /**
     * Menginput nilai secara bulk
     * 
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function inputBulk(Request $request, $namaFta, $idProdi)
    {
        $validatedData = $request->validate([
            'data.*.nim' => 'required|string|max:22',
            'data.*.nama' => 'required|string|max:100',
            'data.*.kelompok' => 'required|string|max:255',
            'data.*.penguji1' => 'string|max:3',
            'data.*.penguji2' => 'string|max:3',
            'data.*.penguji3' => 'string|max:3',
            'data.*.nilaiPenguji1' => 'numeric|min:0|max:100',
            'data.*.nilaiPenguji2' => 'numeric|min:0|max:100',
            'data.*.nilaiPenguji3' => 'numeric|min:0|max:100',
        ], [
            'data.*.nim.required' => 'NIM tidak boleh kosong',
            'data.*.nim.string' => 'NIM harus berupa string',
            'data.*.nim.max' => 'NIM tidak boleh lebih dari 22 karakter',
            'data.*.nama.required' => 'Nama tidak boleh kosong',
            'data.*.nama.string' => 'Nama harus berupa string',
            'data.*.nama.max' => 'Nama tidak boleh lebih dari 100 karakter',
            'data.*.kelompok.required' => 'Kelompok tidak boleh kosong',
            'data.*.kelompok.string' => 'Kelompok harus berupa string',
            'data.*.kelompok.max' => 'Kelompok tidak boleh lebih dari 255 karakter',
            'data.*.penguji1.string' => 'Penguji 1 harus berupa string',
            'data.*.penguji1.max' => 'Penguji 1 tidak boleh lebih dari 3 karakter',
            'data.*.penguji2.string' => 'Penguji 2 harus berupa string',
            'data.*.penguji2.max' => 'Penguji 2 tidak boleh lebih dari 3 karakter',
            'data.*.penguji3.string' => 'Penguji 3 harus berupa string',
            'data.*.penguji3.max' => 'Penguji 3 tidak boleh lebih dari 3 karakter',
            'data.*.nilaiPenguji1.numeric' => 'Nilai Penguji 1 harus berupa angka',
            'data.*.nilaiPenguji1.min' => 'Nilai Penguji 1 tidak boleh kurang dari 0',
            'data.*.nilaiPenguji1.max' => 'Nilai Penguji 1 tidak boleh lebih dari 100',
            'data.*.nilaiPenguji2.numeric' => 'Nilai Penguji 2 harus berupa angka',
            'data.*.nilaiPenguji2.min' => 'Nilai Penguji 2 tidak boleh kurang dari 0',
            'data.*.nilaiPenguji2.max' => 'Nilai Penguji 2 tidak boleh lebih dari 100',
            'data.*.nilaiPenguji3.numeric' => 'Nilai Penguji 3 harus berupa angka',
            'data.*.nilaiPenguji3.min' => 'Nilai Penguji 3 tidak boleh kurang dari 0',
            'data.*.nilaiPenguji3.max' => 'Nilai Penguji 3 tidak boleh lebih dari 100',
        ]);
        $namaFtaFormatted = Str::title(str_replace('-', ' ', $namaFta));
        $namaFtaFormatted = str_replace(['Ii', 'Iii'], ['II', 'III'], $namaFtaFormatted);

        DB::beginTransaction();

        try {
            foreach ($validatedData['data'] as $row) {
                $mahasiswa = Mahasiswa::where('nim', $row['nim'])->first();
                $mahasiswaKota = Mahasiswa::where('id_kota', $mahasiswa->id_kota)
                    ->with('kota')
                    ->get();

                $formPenilaian = FormPenilaian::where('nama_fta', $namaFtaFormatted)
                    ->where('jenis_form', 'penilaian')
                    ->where('id_prodi', $idProdi)
                    ->where('jenis_ta', $mahasiswaKota->first()->kota->jenis_ta)
                    ->with('kategoriPenilaian')
                    ->first();

                    if (!$formPenilaian) {
                        Log::error("Form Penilaian tidak ditemukan", [
                            'nama_fta' => $namaFtaFormatted,
                            'jenis_form' => 'penilaian',
                            'id_prodi' => $idProdi,
                            'jenis_ta' => $mahasiswaKota->first()->kota->jenis_ta,
                        ]);
                        throw new \Exception("Form Penilaian tidak ditemukan");
                    }
                
                $kategoriPenilaian = $formPenilaian->kategoriPenilaian->first();
                $idKategori = $kategoriPenilaian->id_kategori;

                if (!$kategoriPenilaian) {
                    Log::error("Kategori Penilaian tidak ditemukan", [
                        'form_penilaian_id' => $formPenilaian->id_fta,
                    ]);
                    throw new \Exception("Kategori Penilaian tidak ditemukan");
                }

                if (!$idKategori) {
                    Log::error("ID Kategori tidak ditemukan", [
                        'kategori_penilaian_id' => $kategoriPenilaian->id_kategori,
                    ]);
                    throw new \Exception("ID Kategori tidak ditemukan");
                }

                if (!$mahasiswa) {
                    continue; // Skip jika mahasiswa tidak ditemukan
                }

                $penguji = [
                    $row['penguji1'],
                    $row['penguji2'],
                    $row['penguji3'],
                ];

                $nilai = [
                    $row['nilaiPenguji1'],
                    $row['nilaiPenguji2'],
                    $row['nilaiPenguji3'],
                ];

                foreach ($penguji as $index => $idPenguji) {
                    if (!empty($idPenguji)) {
                        $dosen = Dosen::where('id_dosen', $idPenguji)
                            ->first();
                        
                        if (!$dosen) {
                            continue; // Skip jika dosen tidak ditemukan
                        }

                        // Hapus nilai kategori sebelumnya untuk mahasiswa dan dosen ini
                        DB::table('nilai_kategori')
                            ->where('nim', $mahasiswa->nim)
                            ->where('nip', $dosen->nip)
                            ->where('id_kategori', $idKategori)
                            ->delete();

                        // Simpan nilai kategori baru
                        $mahasiswa->nilaiKategori()->create([
                            'nim' => $mahasiswa->nim,
                            'nip' => $dosen->nip,
                            'id_kategori' => $idKategori, 
                            'nilai' => $nilai[$index],
                            'status_penilaian_dosen' => 'dipublikasikan',
                        ]);
                    }
                }
            }

            DB::commit();
            return redirect()->route('kelola.penilaian.detail', ['namaFta' => $namaFta, 'idProdi' => $idProdi])->with('success', 'Data nilai kategori berhasil disimpan!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('kelola.penilaian.detail', ['namaFta' => $namaFta, 'idProdi' => $idProdi])->with('error', 'Gagal mengimport nilai: ' . $e->getMessage());
        }
    }

    /**
     * Mengubah status publish nilai
     * 
     * @param string $namaFta
     * @param int $idKota
     * @param string $action
     * @return \Illuminate\Http\RedirectResponse
     */
    public function togglePublishNilai($namaFta, $idKota, $action)
    {
        $nip = auth()->user()->username;
        $namaFtaSlug = Str::slug($namaFta, ' ');

        $mahasiswa = Mahasiswa::where('id_kota', $idKota)
            ->with('kota')
            ->get();

        $nimList = $mahasiswa->pluck('nim')->toArray();
        $jenisTa = $mahasiswa->first()->kota->jenis_ta;
        $idProdi = $mahasiswa->first()->id_prodi;

        $formPenilaian = FormPenilaian::where([
            ['nama_fta', $namaFtaSlug],
            ['id_prodi', $idProdi],
            ['jenis_form', 'feedback']
        ])
            ->with('aspekFeedback')
            ->first();

        $aspekFeedback = $formPenilaian->aspekFeedback->pluck('id_feedback')->toArray();

        $status = $action === 'publish' ? 'dipublikasikan' : 'draf';

        $detailFeedback = DetailFeedback::where('id_kota', $idKota)
            ->where('nip', $nip)
            ->whereIn('id_feedback', $aspekFeedback);

        $nilaiKategori = NilaiKategori::where('nip', $nip)
            ->whereIn('nim', $nimList)
            ->whereHas('kategoriPenilaian.formulirPenilaian', function ($query) use ($namaFtaSlug, $idProdi) {
                $query->where('nama_fta', $namaFtaSlug)
                    ->where('jenis_form', 'penilaian')
                    ->where('id_prodi', $idProdi);
            });

        if ($detailFeedback->get()->isEmpty() && $nilaiKategori->get()->isEmpty()) {
            return back()->with('error', 'Feedback dan Nilai untuk Kelompok ini belum lengkap.');
        } elseif ($detailFeedback->get()->isEmpty()) {
            return back()->with('error', 'Feedback untuk Kelompok ini belum lengkap.');
        } else if ($nilaiKategori->get()->isEmpty()) {
            return back()->with('error', 'Nilai untuk Kelompok ini belum lengkap.');
        }

        try {
            // Get all mahasiswa with same id_kota
            $mahasiswaKota = Mahasiswa::where('id_kota', $idKota)
                ->with('user')
                ->get();

            foreach ($mahasiswaKota as $mhs) {
                if ($mhs->user) {
                    $mhs->user->notify(new \App\Notifications\TestEmailNotification(
                        '[Pemberitahuan] Nilai sudah di publikasikan',
                        [
                            'nama_dosen' => auth()->user()->nama,
                        ]
                    ));
                }
            }
        } catch (\Exception $notifEx) {
            \Log::error('Gagal mengirim notifikasi publikasi nilai: ' . $notifEx->getMessage());
        }

        $detailFeedback->update(['status_penilaian_dosen' => $status]);
        $nilaiKategori->update(['status_penilaian_dosen' => $status]);

        $message = $action === 'publish' ? 'Nilai berhasil dipublikasikan.' : 'Nilai berhasil diunpublikasikan.';

        return back()->with('success', $message);
    }

    /**
     * Mengunci atau membuka kunci penilaian
     * 
     * @param string $namaFta
     * @param int $idProdi
     * @param string $action
     * @return \Illuminate\Http\RedirectResponse
     */
    public function toggleKunciPenilaian($idKategori, $action)
    {
        $kategoriPenilaian = kategoriPenilaian::where('id_kategori', $idKategori)
            ->with('formulirPenilaian')
            ->get()
            ->first();

        $namaFta = $kategoriPenilaian->formulirPenilaian->nama_fta;
        $idProdi = $kategoriPenilaian->formulirPenilaian->id_prodi;

        $formPenilaian = FormPenilaian::where([
            ['nama_fta', $namaFta],
            ['id_prodi', $idProdi],
            ['jenis_form', 'penilaian']
        ])
            ->with('kategoriPenilaian')
            ->get();

        if ($formPenilaian) {
            $formPenilaian->each(function ($penilaian) use ($action) {
                $penilaian->kategoriPenilaian->each(function ($kategori) use ($action) {
                    $kategori->kunci_penilaian = $action === 'kunci' ? 1 : 0;
                    $kategori->save();
                });
            });

            return back()->with('success', 'Penilaian berhasil ' . ($action === 'kunci' ? 'dikunci.' : 'dibuka kunci.'));
        }

        return back()->with('error', 'Gagal mengubah status kunci penilaian.');
    }
}