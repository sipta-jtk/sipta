<?php

namespace App\Modules\KelolaPenilaianTA\Controllers;

use App\Modules\Controller;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Mahasiswa;
use App\Models\KategoriPenilaian;
use App\Models\KriteriaPenilaian;
use App\Models\FormPenilaian;
use App\Models\Kota;
use Illuminate\Support\Facades\Log;

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
            ->with('penjadwalan', 'mahasiswa.user')
            ->get();

        // TBD tambahkan where untuk membedakan mana research dan pengembangan
        $detailInformasiFta = FormPenilaian::where('nama_fta', $namaFtaSlug)
            ->where('id_prodi', $idProdi)
            ->where('jenis_form', 'penilaian')
            ->distinct()
            ->with('kriteriaPenilaian.rubrik')
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
            ])
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

    private function simpanNilaiBerdasarkanKriteria($request, $namaFtaSlug, $idKota)
    {
        $nip = auth()->user()->username;
        $nilai = $request->except('_token');
        $nilai = array_values($nilai);

        $mahasiswa = Mahasiswa::where('id_kota', $idKota)->get();

        $formPenilaian = FormPenilaian::where('nama_fta', $namaFtaSlug)
            ->where('jenis_form', 'penilaian')
            ->where('id_prodi', $mahasiswa->first()->id_prodi)
            ->with('kriteriaPenilaian', 'kategoriPenilaian')
            ->first();

        $kategoriPenilaian = $formPenilaian->kategoriPenilaian;
        $kriteriaPenilaian = $formPenilaian->kriteriaPenilaian;

        $nilai_rata_rata = $this->inputNilaiKeDatabaseNilaiKriteria($nilai, $mahasiswa, $kriteriaPenilaian, $nip);
        $this->inputNilaiKeDatabaseNilaiKategori($nilai_rata_rata, $mahasiswa, $kategoriPenilaian, $nip);

        // return redirect()->back()->with('success', 'Nilai berhasil disimpan');
    }

    private function simpanNilaiBerdasarkanRubrik($request, $namaFtaSlug, $idKota): View
    {
        $nip = auth()->user()->username;
        $nilai = $request->except('_token');
        $nilai = array_values($nilai);
        
    
        $mahasiswa = Mahasiswa::where('id_kota', $idKota)->get();
        if ($mahasiswa->isEmpty()) {
            return response()->json(['error' => 'Tidak ada mahasiswa yang ditemukan'], 404);
        }
    
        $formPenilaian = FormPenilaian::where('nama_fta', $namaFtaSlug)
            ->where('jenis_form', 'penilaian')
            ->where('id_prodi', $mahasiswa->first()->id_prodi)
            ->with('kriteriaPenilaian.rubrik', 'kategoriPenilaian')
            ->first();
    
        $kategoriPenilaian = $formPenilaian->kategoriPenilaian;
        $kriteriaPenilaian = $formPenilaian->kriteriaPenilaian;

        $nilai_rata_rata_rubrik = $this->inputNilaiKeDatabaseNilaiRubrik($nilai, $mahasiswa, $kriteriaPenilaian, $nip);
        $nilai_rata_rata_kriteria = $this->inputNilaiKeDatabaseNilaiKriteria($nilai_rata_rata_rubrik, $mahasiswa, $kriteriaPenilaian, $nip);
        $this->inputNilaiKeDatabaseNilaiKategori($nilai_rata_rata_kriteria, $mahasiswa, $kategoriPenilaian, $nip);

        // return response()->json(['success' => 'Nilai berhasil disimpan']);
    }
    

    private function inputNilaiKeDatabaseNilaiRubrik($nilai, $mahasiswa, $kriteriaPenilaian, $nip): array
    {
        $nilai_rata_rata = [];
    
        foreach ($mahasiswa as $index => $mhs) {
            $nilai_rata_rata[$index] = []; // Array untuk menyimpan rata-rata nilai per kriteria
    
            $nilaiIndex = 0; // Menunjuk indeks nilai yang sedang diproses
    
            foreach ($kriteriaPenilaian as $kriteria) {
                $jumlahRubrik = count($kriteria['rubrik']);
                $totalNilai = 0;
    
                // Mengambil nilai sesuai jumlah rubrik dalam kriteria
                foreach ($kriteria['rubrik'] as $rubrik) {
                    $nilaiRubrik = (double) $nilai[$index][$nilaiIndex];
                    $totalNilai += $nilaiRubrik;
    
                    // Simpan ke database untuk setiap rubrik
                    $mhs->nilaiRubrik()->create([
                        'nim' => $mhs->nim,
                        'nip' => $nip,
                        'id_rubrik' => $rubrik['id_rubrik'],
                        'nilai_rubrik' => $nilaiRubrik,
                        'status_penilaian_dosen' => 'sudah_dinilai'
                    ]);
    
                    $nilaiIndex++;
                }
    
                // Menghitung rata-rata nilai untuk kriteria tersebut
                $rataRata = $jumlahRubrik > 0 ? $totalNilai / $jumlahRubrik : 0;
                $nilai_rata_rata[$index][] = $rataRata;
            }
        }
    
        return $nilai_rata_rata;
    }
    
    
    


    private function inputNilaiKeDatabaseNilaiKriteria($nilai, $mahasiswa, $kriteriaPenilaian, $nip): array
    {
        $nilai_rata_rata = [];
        $bobotKriteria = $kriteriaPenilaian->pluck('bobot_kriteria')->toArray();
        
        foreach ($mahasiswa as $index => $mhs) {
            Log::info("dipanggil");
            $totalNilai = 0;
            $totalBobot = array_sum($bobotKriteria);

            foreach ($kriteriaPenilaian as $kriteriaIndex => $kriteria) {
                $nilaiKriteria = (double) $nilai[$index][$kriteriaIndex];
  
                Log::info('Insert nilai kriteria');
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

    private function inputNilaiKeDatabaseNilaiKategori(array $nilai_rata_rata, $mahasiswa, $kategoriPenilaian, $nip): void
    {
        $idKategori = $kategoriPenilaian->first()->id_kategori;
    
        foreach ($mahasiswa as $index => $mhs) {
            $mhs->nilaiKategori()->create([
                'nim' => $mhs->nim,
                'nip' => $nip,
                'id_kategori' => $idKategori,
                'nilai' => $nilai_rata_rata[$index],
            ]);
        }
    }

    // private function inputNilaiKeDatabaseNilaiRubrik($mahasiswa, $nilai, $nip, $idFta): void
    // {
    //     $kriteriaPenilaian = KriteriaPenilaian::where('id_fta', $idFta)->with('rubrik')->get();
    //     $rubrikDiKriteriaPenilaian = $this->ambilRubrikDiKriteriaPenilaian($kriteriaPenilaian);
    
    //     foreach ($mahasiswa as $index => $mhs) {
    //         foreach ($rubrikDiKriteriaPenilaian as $rubrikIndex => $rubrik) {
    //             $nilaiRubrik = (double) $nilai['nilai' . $index][$rubrikIndex];
    
    //             // Simpan nilai rubrik ke database
    //             $mhs->nilaiRubrik()->create([
    //                 'nim' => $mhs->nim,
    //                 'nip' => $nip,
    //                 'id_rubrik' => $rubrik['id_rubrik'],
    //                 'nilai_rubrik' => $nilaiRubrik,
    //                 'status_penilaian_dosen' => 'sudah_dinilai' //TBD INI MUNGKIN DIHAPUS
    //             ]);
    //         }
    //     }
    // }

    /**
     * Helper function untuk menghitung rata-rata nilai
     */
    public function hitungRataRataNilaiDenganBobot(array $nilai, array $bobot): float
    {
        $totalNilai = 0;
        $totalBobot = 0;

        foreach ($nilai as $index => $value) {
            $totalNilai += $value * $bobot[$index];
            $totalBobot += $bobot[$index];
        }

        return $totalBobot > 0 ? $totalNilai / $totalBobot : 0;
    }

    /**
     * Helper function untuk menghitung nilai rubrik per mahasiswa tanpa bobot kriteria
     */
    private function hitungNilaiRubrikPerMahasiswa($kriteriaPenilaian)
    {
        $nilaiPerMahasiswa = [];
    
        foreach ($kriteriaPenilaian as $kriteria) {
            foreach ($kriteria->rubrik as $rubrik) {
                foreach ($rubrik->nilaiRubrik as $nilaiRubrik) {
                    $nim = $nilaiRubrik['nim'];
                    if (!isset($nilaiPerMahasiswa[$nim])) {
                        $nilaiPerMahasiswa[$nim] = 0;
                    }
                    $nilaiPerMahasiswa[$nim] += $nilaiRubrik['nilai_rubrik'];
                }
            }
        }
    
        return $nilaiPerMahasiswa;
    }

    public function ambilRubrikDiKriteriaPenilaian($kriteriaPenilaian)
    {
        $rubrikArray = [];

        foreach ($kriteriaPenilaian as $kriteria) {
            foreach ($kriteria->rubrik as $rubrik) {
                $rubrikArray[] = $rubrik;
            }
        }

        return $rubrikArray;
    }

    public function editNilaiSeminar(Request $request, $idFta, $idKota): View
    {
        if ($idFta == 2) {
            return $this->editNilaiSeminarII($request, $idFta, $idKota);
        } else if ($idFta == 4) {
            return $this->editNilaiSeminarIII($request, $idFta, $idKota);
        }
    }

    /**
     * Helper function untuk mengedit nilai seminar
     */
    public function editNilaiSeminarII(Request $request, $idFta, $idKota): View
    {
        $nip = auth()->user()->username;
        $nilai = $request->except('_token', '_method');

        $mahasiswa = Mahasiswa::where('id_kota', $idKota)->get();

        $this->updateNilaiKeDatabaseKriteriaPenilaianSeminarII($mahasiswa, $nilai, $nip, $idFta);
        $this->updateNilaiKeDatabaseKategoriPenilaianSeminarII($mahasiswa, $nilai, $nip, $idFta);

        $pengelolaanNilai = new PengelolaanNilaiController();
        return $pengelolaanNilai->detailNilaiMahasiswa($idFta);
    }

    /**
     * Helper function untuk update nilai kriteria penilaian ke database
     */
    private function updateNilaiKeDatabaseKriteriaPenilaianSeminarII($mahasiswa, $nilai, $nip, $idFta): void
    {
        $kriteriaPenilaian = KriteriaPenilaian::where('id_fta', $idFta)->get();

        foreach ($mahasiswa as $index => $mhs) {
            // Hapus nilai kriteria yang sudah ada untuk mahasiswa ini agar tidak perlu update
            $mhs->nilaiKriteria()->whereIn('id_kriteria', $kriteriaPenilaian->pluck('id_kriteria'))->delete();

            foreach ($kriteriaPenilaian as $kriteriaIndex => $kriteria) {
                $nilaiKriteria = (double) $nilai['nilai' . $index][$kriteriaIndex];

                // Masukkan data baru setelah penghapusan
                $mhs->nilaiKriteria()->create([
                    'nim' => $mhs->nim,
                    'nip' => $nip,
                    'id_kriteria' => $kriteria->id_kriteria,
                    'nilai_kriteria' => $nilaiKriteria,
                ]);
            }
        }
    }

    /**
     * Helper function untuk update nilai kategori penilaian ke database tanpa UPDATE
     */
    private function updateNilaiKeDatabaseKategoriPenilaianSeminarII($mahasiswa, $nilai, $nip, $idFta): void
    {
        $nilai_rata_rata = [];
        $bobot = FormPenilaian::where('id_fta', $idFta)->with('kriteriaPenilaian')->first();
        $bobotKriteria = $bobot->kriteriaPenilaian->pluck('bobot_kriteria')->toArray();
        $idKategori = KategoriPenilaian::where('id_fta', $idFta)->first()->id_kategori;

        // Menghitung rata-rata nilai untuk setiap mahasiswa dengan bobot
        foreach ($nilai as $index => $values) {
            $average = $this->hitungRataRataNilaiDenganBobot($values, $bobotKriteria);
            $nilai_rata_rata[] = $average;
        }

        foreach ($mahasiswa as $index => $mhs) {
            // Hapus nilai kategori yang sudah ada agar tidak perlu update
            $mhs->nilaiKategori()->where('id_kategori', $idKategori)->delete();

            // Masukkan data baru setelah penghapusan
            $mhs->nilaiKategori()->create([
                'nim' => $mhs->nim,
                'nip' => $nip,
                'id_kategori' => $idKategori,
                'nilai' => $nilai_rata_rata[$index],
            ]);
        }
    }

    public function editNilaiSeminarIII(Request $request, $idFta, $idKota): View
    {
        $nip = auth()->user()->username;
        $nilai = $request->except('_token', '_method');
    
        $mahasiswa = Mahasiswa::where('id_kota', $idKota)->get();
    
        $this->updateNilaiKeDatabaseNilaiRubrik($mahasiswa, $nilai, $nip, $idFta);
        $this->updateNilaiKeDatabaseNilaiKriteriaSeminarIII($mahasiswa, $nilai, $nip, $idFta);
        $this->updateNilaiKeDatabaseKategoriPenilaianSeminarIII($mahasiswa, $nilai, $nip, $idFta);
    
        $pengelolaanNilai = new PengelolaanNilaiController();
        return $pengelolaanNilai->detailNilaiMahasiswa($idFta);
    }
    
    /**
     * Helper function untuk update nilai rubrik ke database
     */
    private function updateNilaiKeDatabaseNilaiRubrik($mahasiswa, $nilai, $nip, $idFta): void
    {
        $kriteriaPenilaian = KriteriaPenilaian::where('id_fta', $idFta)->with('rubrik')->get();
        $rubrikDiKriteriaPenilaian = $this->ambilRubrikDiKriteriaPenilaian($kriteriaPenilaian);
    
        foreach ($mahasiswa as $index => $mhs) {
            // Hapus nilai rubrik yang sudah ada untuk mahasiswa ini agar tidak perlu update
            $mhs->nilaiRubrik()->whereIn('id_rubrik', array_column($rubrikDiKriteriaPenilaian, 'id_rubrik'))->delete();
    
            foreach ($rubrikDiKriteriaPenilaian as $rubrikIndex => $rubrik) {
                $nilaiRubrik = (double) $nilai['nilai' . $index][$rubrikIndex];
    
                // Masukkan data baru setelah penghapusan
                $mhs->nilaiRubrik()->create([
                    'nim' => $mhs->nim,
                    'nip' => $nip,
                    'id_rubrik' => $rubrik['id_rubrik'],
                    'nilai_rubrik' => $nilaiRubrik,
                    'status_penilaian_dosen' => 'sudah_dinilai' //TBD INI MUNGKIN DIHAPUS
                ]);
            }
        }
    }
    
    /**
     * Helper function untuk update nilai kriteria penilaian ke database
     */
    private function updateNilaiKeDatabaseNilaiKriteriaSeminarIII($mahasiswa, $nilai, $nip, $idFta): void
    {
        $kriteriaPenilaian = KriteriaPenilaian::where('id_fta', $idFta)->with('rubrik.nilaiRubrik')->get();
    
        foreach ($mahasiswa as $index => $mhs) {
            // Hapus nilai kriteria yang sudah ada untuk mahasiswa ini agar tidak perlu update
            $mhs->nilaiKriteria()->whereIn('id_kriteria', $kriteriaPenilaian->pluck('id_kriteria'))->delete();
    
            foreach ($kriteriaPenilaian as $kriteriaIndex => $kriteria) {
                $nilaiKriteria = (double) $nilai['nilai' . $index][$kriteriaIndex];
                $nilaiKriteria = round($nilaiKriteria, 2);
    
                // Masukkan data baru setelah penghapusan
                $mhs->nilaiKriteria()->create([
                    'nim' => $mhs->nim,
                    'nip' => $nip,
                    'id_kriteria' => $kriteria->id_kriteria,
                    'nilai_kriteria' => $nilaiKriteria,
                    'status_penilaian_dosen' => 'draf'
                ]);
            }
        }
    }
    
    /**
     * Helper function untuk update nilai kategori penilaian ke database tanpa UPDATE
     */
    private function updateNilaiKeDatabaseKategoriPenilaianSeminarIII($mahasiswa, $nilai, $nip, $idFta): void
    {
        $nilai_rata_rata = [];
        $idKategori = KategoriPenilaian::where('id_fta', $idFta)->first()->id_kategori;
    
        // Menghitung rata-rata nilai untuk setiap mahasiswa tanpa bobot
        foreach ($nilai as $index => $values) {
            $totalNilai = array_sum($values);
            $jumlahNilai = count($values);
            $average = $jumlahNilai > 0 ? round($totalNilai / $jumlahNilai, 2) : 0;
            $nilai_rata_rata[] = $average;
        }
    
        foreach ($mahasiswa as $index => $mhs) {
            // Hapus nilai kategori yang sudah ada agar tidak perlu update
            $mhs->nilaiKategori()->where('id_kategori', $idKategori)->delete();
    
            // Masukkan data baru setelah penghapusan
            $mhs->nilaiKategori()->create([
                'nim' => $mhs->nim,
                'nip' => $nip,
                'id_kategori' => $idKategori,
                'nilai' => $nilai_rata_rata[$index],
            ]);
        }
    }
}