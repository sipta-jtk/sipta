<?php

namespace App\Modules\KelolaPenilaianTA\Controllers;

use App\Modules\Controller;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\Mahasiswa;
use App\Models\Dosen;
use App\Models\FormPenilaian;
use App\Models\Kota;
use App\Models\Dokumen;
use App\Models\AlokasiDosen;
use App\Models\SubkategoriDokumen;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\FromCollection;
use Carbon\Carbon;

class PemberianNilaiController extends Controller
{

    /**
     * Cek aksesibilitas penilaian berdasarkan nama FTA dan idKota
     * 
     * @param string $namaFtaSlug
     * @param int $idKota
     */
    private function cekAksebilitasPenilaian($idKota)
    {
        $nip = Auth::user()->dosen->nip;
    
        // Ambil semua id_kota yang boleh diakses dosen ini
        $kotaDibimbing = AlokasiDosen::where('nip', $nip)
            ->where('status_alokasi', 'fix')
            ->with([
                'pengajuanPembimbing.kota'
            ])
            ->get()
            ->pluck('pengajuanPembimbing.kota')
            ->flatten()
            ->unique('id_kota');
    
        $bolehAkses = $kotaDibimbing->contains(function ($kota) use ($idKota) {
            return $kota && $kota->id_kota == $idKota;
        });
    
        if (!$bolehAkses) {
            abort(403, 'Anda tidak memiliki akses untuk menilai kelompok ini');
        }
    }
    
    /**
     * Cek apakah dosen ini adalah pembimbing di kota tersebut
     * 
     * @param int $idKota
     */
    private function cekAksesPenilaianPembimbing($idKota)
    {
        $nip = Auth::user()->dosen->nip;
    
        // Cek apakah dosen ini adalah pembimbing di kota tersebut dan status_pengujian 'diterima'
        $isPembimbing = AlokasiDosen::where('nip', $nip)
            ->whereHas('pengajuanPembimbing', function ($query) use ($idKota) {
                $query->where('status_pengajuan', 'diterima')
                      ->whereHas('kota', function ($q) use ($idKota) {
                          $q->where('id_kota', $idKota);
                      });
            })
            ->exists();
    
        if (!$isPembimbing) {
            abort(403, 'Anda bukan pembimbing kelompok ini, sehingga tidak dapat memberi nilai.');
        }
    }

    private function cekAksesPenilaianRubrikTerkunci($kategoriPenilaian, $mahasiswa, $nip)
    {
        // Cek apakah kategori penilaian dikunci
        if ($kategoriPenilaian->kunci_penilaian) {
            // Cek apakah dosen sudah pernah memberi nilai rubrik untuk kategori ini pada salah satu mahasiswa
            $sudahNilai = $mahasiswa->contains(function ($mhs) use ($kategoriPenilaian, $nip) {
                return $mhs->nilaiRubrik()
                    ->where('nip', $nip)
                    ->whereHas('rubrik.kriteriaPenilaian.formPenilaian.kategoriPenilaian', function ($q) use ($kategoriPenilaian) {
                        $q->where('id_kategori', $kategoriPenilaian->id_kategori);
                    })
                    ->exists();
            });
    
            // Jika belum pernah memberi nilai, abort
            if (!$sudahNilai) {
                abort(403, 'Penilaian rubrik untuk kategori ini sudah dikunci. Anda tidak dapat mengisi nilai baru.');
            }
        }
    }
    
    private function cekAksesJadwalDimulai($idKota, $namaFta)
    {
        // Mapping namaFta ke format agenda di database
        $agenda = match (strtolower($namaFta)) {
            'seminar iii' => 'seminar_3',
            'sidang akhir' => 'sidang',
            'dosen pembimbing' => 'dosen_pembimbing',
            default => $namaFta,
        };

        if ($agenda == 'dosen_pembimbing') {
            return;
        }
    
        $jadwalQuery = Kota::where('id_kota', $idKota)
            ->with(['penjadwalan' => function ($q) use ($agenda) {
                if ($agenda) {
                    $q->where(function ($query) use ($agenda) {
                        $query->where('agenda', $agenda);
                    });
                }
            }])
            ->first();
    
        $penjadwalan = $jadwalQuery->penjadwalan->first();
        $start = optional($penjadwalan)->start;
        $agenda = optional($penjadwalan)->agenda ?? $agenda;
    
        // Mapping agenda ke label user-friendly
        $jenisSeminar = match (strtolower($agenda)) {
            'seminar_3', 'seminar 3', 'seminar-3' => 'Seminar III',
            'sidang' => 'Sidang Akhir',
            default => ucfirst(str_replace('_', ' ', $agenda ?? 'Seminar')),
        };

        if (!$start) {
            abort(403, "Jadwal penilaian $jenisSeminar belum ditentukan.");
        }
    
        if (Carbon::now()->lt(Carbon::parse($start))) {
            abort(403, "Penilaian $jenisSeminar belum dapat dilakukan karena jadwal belum dimulai.");
        }
    }

    /**
     * Menampilkan halaman pengisian nilai seminar
     * 
     * @param string $namaFta
     * @param int $idKota
     * @param int $idProdi
     */
    public function pengisianNilaiSeminar($namaFta, $idKota): View
    {
        $namaFtaSlug = Str::slug($namaFta, ' ');
        if ($namaFtaSlug == 'dosen pembimbing') {
            $this->cekAksesPenilaianPembimbing($idKota);
            return $this->pengisianNilaiDosenPembimbing($namaFtaSlug, $idKota);
        } else {
            $this->cekAksebilitasPenilaian($idKota);
            $this->cekAksesJadwalDimulai($idKota, $namaFtaSlug);
            return $this->pengisianNilaiBerdasarkanRubrik($namaFtaSlug, $idKota);
        }
    }

    public function pengisianNilaiBerdasarkanKriteria($namaFtaSlug, $idKota): View
    {
        $keteranganUmumPenilaian = Kota::where('id_kota', $idKota)
            ->with('penjadwalan', 'mahasiswa.user', 'mahasiswa.nilaiKriteria')
            ->get();

        $jenisTa = $keteranganUmumPenilaian->first()->jenis_ta;

        $detailInformasiFta = FormPenilaian::where('nama_fta', $namaFtaSlug)
        ->where('id_prodi', $idProdi)
        ->where('jenis_form', 'penilaian')
        ->where('jenis_ta', $jenisTa)
        ->distinct()
        ->with([
            'kriteriaPenilaian.rubrik',
            'kriteriaPenilaian.nilaiKriteria' => function ($query) use ($idKota) {
                $query->whereHas('mahasiswa', function ($q) use ($idKota) {
                    $q->where('id_kota', $idKota);
                });
            }
        ])
        ->get();

        return view('KelolaPenilaianTA.views.pemberian-nilai-dan-feedback.formulir_penilaian_berdasarkan_kriteria', [
            'detailInformasiFta' => $detailInformasiFta,
            'keteranganUmumPenilaian' => $keteranganUmumPenilaian->first(),
            'namaFta' => $namaFtaSlug,
            'idKota' => $idKota,
            'idProdi' => $idProdi,
        ]);
    }

    public function pengisianNilaiBerdasarkanRubrik($namaFtaSlug, $idKota): View
    {
        $username = auth()->user()->username;

        $keteranganUmumPenilaian = Kota::where('id_kota', $idKota)
            ->with('penjadwalan', 'mahasiswa.user')
            ->first();
        $idProdi = $keteranganUmumPenilaian->mahasiswa->first()->id_prodi;

        $jenisTa = $keteranganUmumPenilaian->jenis_ta;

        $detailInformasiFta = FormPenilaian::where('nama_fta', $namaFtaSlug)
            ->where('id_prodi', $idProdi)
            ->where('jenis_ta', $jenisTa)
            ->where('jenis_form', 'penilaian')
            ->with([
                'kriteriaPenilaian.rubrik.nilaiRubrik' => function ($query) use ($idKota, $username) {
                    $query->whereHas('mahasiswa', function ($q) use ($idKota) {
                        $q->where('id_kota', $idKota);
                    })->where('nip', $username);
                },
                'kategoriPenilaian.nilaiKategori' => function ($query) use ($idKota, $username) {
                    $query->whereHas('mahasiswa', function ($q) use ($idKota) {
                        $q->where('id_kota', $idKota);
                    })
                    ->where('nip', $username);
                }])
            ->get();

        $this->cekAksesPenilaianRubrikTerkunci(
            $detailInformasiFta->first()->kategoriPenilaian->first(),
            $keteranganUmumPenilaian->mahasiswa,
            $username
        );

        $rubrikList = $detailInformasiFta->flatMap(function ($fta) {
            return $fta->kriteriaPenilaian->flatMap(function ($kriteria) {
                return $kriteria->rubrik;
            });
        })->first()->detailRubrik;

        // Ambil dokumen terbaru berdasarkan kota dan kategori
        $dokumen = $this->getLatestDokumenByKota($idKota, $namaFtaSlug);

        $view = $detailInformasiFta->first()->kategoriPenilaian->first()->nilaiKategori->first()?->status_penilaian_dosen == 'dipublikasikan' ? false : true;
        
        return view('KelolaPenilaianTA.views.pemberian-nilai-dan-feedback.formulir_penilaian_berdasarkan_rubrik', [
            'keteranganUmumPenilaian' => $keteranganUmumPenilaian,
            'detailInformasiFta' => $detailInformasiFta,
            'rubrikList' => $rubrikList,
            'idKota' => $idKota,
            'namaFta' => $namaFtaSlug,
            'idProdi' => $idProdi,
            'view' => $view,
            'dokumen' => $dokumen,
        ]);
    }

    /**
     * Ambil dokumen terbaru berdasarkan kota dan kategori
     * 
     * @param int $idKota
     * @param string $kategori
     * @return array
     */
    private function getLatestDokumenByKota($idKota, $kategori)
    {
        $kategori = Str::slug($kategori, '-');
        // Ubah kategori menjadi huruf kecil untuk konsistensi
        $kategori = strtolower($kategori);

        // Mapping manual kategori supaya sesuai dengan format di database
        $kategori = match ($kategori) {
            'seminar-i' => 'seminar1', // Seminar I
            'seminar-ii' => 'seminar2', // Seminar II
            'seminar-iii' => 'seminar3', // Seminar III
            'sidang-akhir' => 'sidang', // Sidang Akhir
            'dosen-pembimbing' => 'sidang', // Untuk dosen pembimbing, ambil dokumen sidang
            default => $kategori // Kategori lainnya
        };

        // Ambil dokumen laporan terbaru berdasarkan kota dan kategori
        $laporan = Dokumen::where('id_kota', $idKota)
            ->where('kategori', $kategori)
            ->where('id_subkategori', 1) // Subkategori 1: Laporan
            ->orderByDesc('versi') // Urutkan berdasarkan versi terbaru
            ->first();

        // Ambil dokumen PowerPoint terbaru berdasarkan kota dan kategori
        $powerpoint = Dokumen::where('id_kota', $idKota)
            ->where('kategori', $kategori)
            ->where('id_subkategori', 3) // Subkategori 3: PowerPoint
            ->orderByDesc('versi') // Urutkan berdasarkan versi terbaru
            ->first();
        
        // Kembalikan dokumen laporan dan PowerPoint dalam bentuk array
        return [
            'laporan' => $laporan,
            'powerpoint' => $powerpoint
        ];
    }

    public function simpanNilaiSeminar(Request $request, $namaFta, $idKota)
    {
        $namaFtaSlug = Str::slug($namaFta, ' ');
        if ($namaFtaSlug == 'seminar ii' || $namaFtaSlug == 'dosen pembimbing') {
            return $this->simpanNilaiBerdasarkanKriteria($request, $namaFtaSlug, $idKota);
        } else {
            return $this->simpanNilaiBerdasarkanRubrik($request, $namaFtaSlug, $idKota);
        }
    }

    public function ubahNilaiSeminar(Request $request, $namaFta, $idKota)
    {
        $namaFtaSlug = Str::slug($namaFta, ' ');
        if ($namaFtaSlug == 'seminar ii') {
            return $this->simpanNilaiBerdasarkanKriteria($request, $namaFtaSlug, $idKota);
        } else {
             return $this->simpanNilaiBerdasarkanRubrik($request, $namaFtaSlug, $idKota);
        }
    }

    private function simpanNilaiBerdasarkanKriteria($request, $namaFtaSlug, $idKota)
    {
        $nip = auth()->user()->username;
        $action = $request->form_action;
        $nilai = $request->except('_token', '_method');
        $nilai = array_values($nilai);

        $mahasiswa = Mahasiswa::where('id_kota', $idKota)->get();

        $formPenilaian = FormPenilaian::where('nama_fta', $namaFtaSlug)
            ->where('jenis_form', 'penilaian')
            ->where('id_prodi', $mahasiswa->first()->id_prodi)
            ->with('kriteriaPenilaian', 'kategoriPenilaian')
            ->first();

        $kategoriPenilaian = $formPenilaian->kategoriPenilaian;
        $kriteriaPenilaian = $formPenilaian->kriteriaPenilaian;

        DB::beginTransaction();

        try {
            $nilai_rata_rata = $this->ubahNilaiKeDatabaseNilaiKriteria($nilai, $mahasiswa, $kriteriaPenilaian, $nip);
            $this->ubahNilaiKeDatabaseNilaiKategori($nilai_rata_rata, $mahasiswa, $kategoriPenilaian, $nip);

            DB::commit();

            return redirect()->route('monitoring.dosen.pembimbing')->with('success', 'Nilai berhasil disimpan');

        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->with('error', 'Gagal menyimpan nilai: ' . $e->getMessage());
        }
    }

    private function simpanNilaiBerdasarkanRubrik($request, $namaFtaSlug, $idKota)
    {
        $nip = auth()->user()->username;
        $action = $request->form_action;
        $nilai = $request->except('_token', '_method');
        $nilai = array_values($nilai);
    
        $mahasiswa = Mahasiswa::where('id_kota', $idKota)
            ->with('kota')
            ->get();
        
        $jenisTa = $mahasiswa->first()->kota->jenis_ta;
    
        $formPenilaian = FormPenilaian::where('nama_fta', $namaFtaSlug)
            ->where('jenis_form', 'penilaian')
            ->where('id_prodi', $mahasiswa->first()->id_prodi)
            ->where('jenis_ta', $jenisTa)
            ->with('kriteriaPenilaian.rubrik', 'kategoriPenilaian')
            ->first();
    
        $kategoriPenilaian = $formPenilaian->kategoriPenilaian;
        $kriteriaPenilaian = $formPenilaian->kriteriaPenilaian;
    
        DB::beginTransaction();
    
        try {
            $nilai_rata_rata_rubrik = $this->ubahNilaiKeDatabaseNilaiRubrik($nilai, $mahasiswa, $kriteriaPenilaian, $nip);
            $nilai_rata_rata_kriteria = $this->ubahNilaiKeDatabaseNilaiKriteria($nilai_rata_rata_rubrik, $mahasiswa, $kriteriaPenilaian, $nip);
            $this->ubahNilaiKeDatabaseNilaiKategori($nilai_rata_rata_kriteria, $mahasiswa, $kategoriPenilaian, $nip);
    
            DB::commit();
    
            if ($namaFtaSlug == 'seminar iii') {
                $namaFtaSlug = 'seminar-iii';
            } elseif ($namaFtaSlug == 'sidang akhir') {
                $namaFtaSlug = 'sidang-akhir';
            }

            return redirect()->route('nilai.index', ['kegiatan' => $namaFtaSlug])->with('success', 'Nilai berhasil disimpan');
        } catch (\Exception $e) {
            DB::rollBack();
    
            return redirect()->back()->with('error', 'Gagal menyimpan nilai: ' . $e->getMessage());
        }
    }
    
    private function ubahNilaiKeDatabaseNilaiRubrik($nilai, $mahasiswa, $kriteriaPenilaian, $nip): array
    {
        $nilai_rata_rata = [];
    
        foreach ($mahasiswa as $index => $mhs) {
            $nilai_rata_rata[$index] = [];
            $nilaiIndex = 0;
    
            foreach ($kriteriaPenilaian as $kriteria) {
                $jumlahRubrik = count($kriteria['rubrik']);
                $totalNilai = 0;
    
                foreach ($kriteria['rubrik'] as $rubrik) {
                    $mhs->nilaiRubrik()
                        ->where('nim', $mhs->nim)
                        ->where('nip', $nip)
                        ->where('id_rubrik', $rubrik['id_rubrik'])
                        ->delete();
    
                    $nilaiRubrik = (double) $nilai[$index][$nilaiIndex];
    
                    $mhs->nilaiRubrik()->create([
                        'nim' => $mhs->nim,
                        'nip' => $nip,
                        'id_rubrik' => $rubrik['id_rubrik'],
                        'nilai_rubrik' => $nilaiRubrik,
                        'status_penilaian_dosen' => 'sudah_dinilai'
                    ]);
    
                    $totalNilai += $nilaiRubrik;
                    $nilaiIndex++;
                }

                $rataRata = $jumlahRubrik > 0 ? $totalNilai / $jumlahRubrik : 0;
                $nilai_rata_rata[$index][] = $rataRata;
            }
        }
    
        return $nilai_rata_rata;
    }
    
    
    private function ubahNilaiKeDatabaseNilaiKriteria($nilai, $mahasiswa, $kriteriaPenilaian, $nip): array
    {
        $nilai_rata_rata = [];
        $bobotKriteria = $kriteriaPenilaian->pluck('bobot_kriteria')->toArray();
    
        foreach ($mahasiswa as $index => $mhs) {
            $totalNilai = 0;
            $totalBobot = array_sum($bobotKriteria);
    
            foreach ($kriteriaPenilaian as $kriteriaIndex => $kriteria) {
                $nilaiKriteria = (double) $nilai[$index][$kriteriaIndex];
    
                $mhs->nilaiKriteria()
                    ->where('id_kriteria', $kriteria->id_kriteria)
                    ->where('nip', $nip)
                    ->where('nim', $mhs->nim)
                    ->delete();
    
                $mhs->nilaiKriteria()->create([
                    'nim' => $mhs->nim,
                    'nip' => $nip,
                    'id_kriteria' => $kriteria->id_kriteria,
                    'nilai_kriteria' => $nilaiKriteria,
                ]);
    
                $totalNilai += $nilaiKriteria * $bobotKriteria[$kriteriaIndex];
            }
    
            $nilai_rata_rata[] = $totalBobot > 0 ? $totalNilai / $totalBobot : 0;
        }
    
        return $nilai_rata_rata;
    }
    
    

    private function ubahNilaiKeDatabaseNilaiKategori(array $nilai_rata_rata, $mahasiswa, $kategoriPenilaian, $nip): void
    {
        $idKategori = $kategoriPenilaian->first()->id_kategori;
    
        foreach ($mahasiswa as $index => $mhs) {
            $mhs->nilaiKategori()
                ->where('nim', $mhs->nim)
                ->where('nip', $nip)
                ->where('id_kategori', $idKategori)
                ->delete();
    
            $mhs->nilaiKategori()->create([
                'nim' => $mhs->nim,
                'nip' => $nip,
                'id_kategori' => $idKategori,
                'nilai' => $nilai_rata_rata[$index],
                'status_penilaian_dosen' => 'draf'
            ]);
        }
    }

    /**
     * Mengimpor nilai dari file Excel
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function importNilai(Request $request)
    {
        // Validasi input file
        $validator = Validator::make($request->all(), [
            'file' => 'required|mimes:xlsx,xls',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        DB::beginTransaction();

        try {
            $file = $request->file('file');

            $data = Excel::toCollection([], $file);

            $rows = $data[0]->slice(1)->toArray(); // Lewati header

            foreach ($rows as $row) {
                $this->prosesBarisExcel($row);
            }

            DB::commit();
            return back()->with('success', 'Nilai berhasil diimport.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Memproses setiap baris dari file Excel
     * 
     * @param array $row
     * @return void
     */
    private function prosesBarisExcel(array $row): void
    {
        $nama = $row[1] ?? null;
        $kelompok = $row[2] ?? null;
        $penguji = [
            strval($row[3] ?? null),
            strval($row[4] ?? null),
            strval($row[5] ?? null),
        ];
        $nilai = [
            $row[6] ?? null,
            $row[7] ?? null,
            $row[8] ?? null,
        ];

        if (empty($nama) || empty($kelompok)) {
            return;
        }

        $mahasiswa = $this->cariMahasiswa($nama, $kelompok);
        if (!$mahasiswa) {
            return;
        }

        foreach ($penguji as $index => $idPenguji) {
            if (!empty($idPenguji)) {
                $this->simpanNilaiKategori($mahasiswa, $idPenguji, $nilai[$index]);
            }
        }
    }

    /**
     * Mencari mahasiswa berdasarkan nama dan kelompok
     * 
     * @param string $nama
     * @param string $kelompok
     * @return \App\Models\Mahasiswa|null
     */
    private function cariMahasiswa(string $nama, string $kelompok): ?Mahasiswa
    {
        return Mahasiswa::select('mahasiswa.nim', 'user.nama as nama', 'mahasiswa.kelas', 'prodi.nama_prodi as prodi', 'kota.nama_kota as kelompok', 'mahasiswa.id_prodi', 'mahasiswa.id_kota')
            ->leftJoin('user', 'mahasiswa.nim', '=', 'user.username')
            ->leftJoin('prodi', 'mahasiswa.id_prodi', '=', 'prodi.id_prodi')
            ->leftJoin('kota', 'mahasiswa.id_kota', '=', 'kota.id_kota')
            ->where('user.nama', $nama)
            ->where('kota.nama_kota', $kelompok)
            ->where('mahasiswa.status_ta', 'mahasiswa_ta')
            ->whereNotNull('mahasiswa.id_kota')
            ->first();
    }

    /**
     * Menyimpan nilai kategori ke database
     * 
     * @param \App\Models\Mahasiswa $mahasiswa
     * @param string $idPenguji
     * @param mixed $nilai
     * @return void
     */
    private function simpanNilaiKategori(Mahasiswa $mahasiswa, string $idPenguji, $nilai): void
    {
        $dosen = Dosen::select('nip')
            ->where('id_dosen', $idPenguji)
            ->where('status_dosen', 'aktif')
            ->first();

        if (!$dosen) {
            return;
        }

        $mahasiswa->nilaiKategori()
            ->where('nim', $mahasiswa->nim)
            ->where('nip', $dosen->nip)
            ->where('id_kategori', 2)
            ->delete();

        $mahasiswa->nilaiKategori()->create([
            'nim' => $mahasiswa->nim,
            'nip' => $dosen->nip,
            'id_kategori' => 2,
            'nilai' => $nilai,
        ]);
    }
    
    public function pengisianNilaiDosenPembimbing($namaFta, $idKota): View
    {
        $namaFtaSlug = Str::slug($namaFta, ' ');
        $username = auth()->user()->username;
    
        $keteranganUmumPenilaian = Kota::where('id_kota', $idKota)
            ->with('penjadwalan', 'mahasiswa.user', 'mahasiswa.nilaiKategori')
            ->get();
    
        $idProdi = $keteranganUmumPenilaian->first()->mahasiswa->first()->id_prodi;
        $mahasiswa = $keteranganUmumPenilaian->first()->mahasiswa;
    
        // Ambil semua id_kategori untuk Sidang Akhir
        $idKategoriSidangAkhir = FormPenilaian::where('nama_fta', 'Sidang Akhir')
            ->where('id_prodi', $idProdi)
            ->where('jenis_form', 'penilaian')
            ->with('kategoriPenilaian')
            ->get()
            ->pluck('kategoriPenilaian')
            ->flatten()
            ->pluck('id_kategori')
            ->toArray();
    
        // Cek apakah sudah ada nilai Sidang Akhir
        $sudahAdaNilaiSidangAkhir = $mahasiswa->contains(function ($mhs) use ($idKategoriSidangAkhir) {
            return collect($mhs->nilaiKategori)
                ->whereIn('id_kategori', $idKategoriSidangAkhir)
                ->isNotEmpty();
        });
    
        if (!$sudahAdaNilaiSidangAkhir) {
            abort(403, 'Kelompok ini belum diberi nilai Sidang Akhir. Pastikan penguji telah mengisi nilai Sidang Akhir terlebih dahulu.');
        }
    
        $detailInformasiFta = FormPenilaian::where('nama_fta', 'dosen pembimbing')
            ->where('id_prodi', $idProdi)
            ->with([
                'kriteriaPenilaian.rubrik',
                'kriteriaPenilaian.nilaiKriteria' => function ($query) use ($idKota, $username) {
                    $query->whereHas('mahasiswa', function ($q) use ($idKota) {
                        $q->where('id_kota', $idKota);
                    })
                    ->where('nip', $username);
                }
            ])
            ->get();
        
        // Ambil dokumen terbaru berdasarkan kota, dengan kategori 'sidang-akhir'
        $dokumen = $this->getLatestDokumenByKota($idKota, 'sidang-akhir');
    
        return view('KelolaPenilaianTA.views.pemberian-nilai-dan-feedback.formulir_penilaian_dosen_pembimbing', [
            'detailInformasiFta' => $detailInformasiFta,
            'keteranganUmumPenilaian' => $keteranganUmumPenilaian->first(),
            'namaFta' => $namaFtaSlug,
            'idKota' => $idKota,
            'dokumen' => $dokumen,
        ]);
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
}