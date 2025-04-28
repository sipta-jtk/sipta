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
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Support\Facades\Validator;

class PemberianNilaiController extends Controller
{

    public function pengisianNilaiSeminar($namaFta, $idKota, $idProdi): View
    {
        $namaFtaSlug = Str::slug($namaFta, ' ');
        if ($namaFtaSlug == 'seminar ii') {
            return $this->pengisianNilaiBerdasarkanKriteria($namaFtaSlug, $idKota, $idProdi);
        } else {
            return $this->pengisianNilaiBerdasarkanRubrik($namaFtaSlug, $idKota, $idProdi);
        }
    }

    public function pengisianNilaiBerdasarkanKriteria($namaFtaSlug, $idKota, $idProdi): View
    {
        $keteranganUmumPenilaian = Kota::where('id_kota', $idKota)
            ->with('penjadwalan', 'mahasiswa.user', 'mahasiswa.nilaiKriteria')
            ->get();

        // TBD tambahkan where untuk membedakan mana research dan pengembangan
        $detailInformasiFta = FormPenilaian::where('nama_fta', $namaFtaSlug)
        ->where('id_prodi', $idProdi)
        ->where('jenis_form', 'penilaian')
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

    public function pengisianNilaiBerdasarkanRubrik($namaFtaSlug, $idKota, $idProdi): View
    {
        $keteranganUmumPenilaian = Kota::where('id_kota', $idKota)
            ->with('penjadwalan', 'mahasiswa.user')
            ->first();

        // TBD tambahkan where untuk membedakan mana research dan pengembangan
        $detailInformasiFta = FormPenilaian::where('nama_fta', $namaFtaSlug)
            ->where('id_prodi', $idProdi)
            ->where('jenis_form', 'penilaian')
            ->with([
                'kriteriaPenilaian.rubrik' => function ($query) {
                    $query->with('detailRubrik.nilai'); 
                }
            , 'kriteriaPenilaian.rubrik.nilaiRubrik' => function ($query) use ($idKota) {
                $query->whereHas('mahasiswa', function ($q) use ($idKota) {
                    $q->where('id_kota', $idKota);
                });
            }])
            ->get();

        $rubrikList = $detailInformasiFta->flatMap(function ($fta) {
            return $fta->kriteriaPenilaian->flatMap(function ($kriteria) {
                return $kriteria->rubrik;
            });
        })->first()->detailRubrik;

        return view('KelolaPenilaianTA.views.pemberian-nilai-dan-feedback.formulir_penilaian_berdasarkan_rubrik', [
            'keteranganUmumPenilaian' => $keteranganUmumPenilaian,
            'detailInformasiFta' => $detailInformasiFta,
            'rubrikList' => $rubrikList,
            'idKota' => $idKota,
            'namaFta' => $namaFtaSlug,
            'idProdi' => $idProdi,
        ]);
    }

    public function simpanNilaiSeminar(Request $request, $namaFta, $idKota)
    {
        $namaFtaSlug = Str::slug($namaFta, ' ');
        if ($namaFtaSlug == 'seminar ii') {
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

            return redirect()->back()->with('success', 'Nilai berhasil disimpan');
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
    
        $mahasiswa = Mahasiswa::where('id_kota', $idKota)->get();
    
        $formPenilaian = FormPenilaian::where('nama_fta', $namaFtaSlug)
            ->where('jenis_form', 'penilaian')
            ->where('id_prodi', $mahasiswa->first()->id_prodi)
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
    
            return redirect()->back()->with('success', 'Nilai berhasil disimpan');
            
            // return redirect()->route('pengisian.masukan', [$namaFtaSlug, $idKota])->with('success', 'Nilai berhasil disimpan');
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
                    'status_penilaian_dosen' => 'draf'
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
    
}