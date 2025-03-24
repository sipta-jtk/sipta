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
use Illuminate\Support\Facades\Log;

class PemberianNilaiController extends Controller
{
    /**
     * Akses halaman pemberian nilai
     */
    public function pengisianNilaiSeminar($idFta, $idKota) 
    {
        switch ($idFta) {
            case 2:
                return $this->pengisianNilaiSeminarII($idFta, $idKota);
            case 4:
                return $this->pengisianNilaiSeminarIII($idFta, $idKota);
            default:
                abort(404, "Seminar tidak ditemukan");
        }
    }


    /**
     * Menampilkan halaman pemberian nilai seminar
     */
    public function pengisianNilaiSeminarII($idFta, $idKota): View
    {
        $mahasiswa = Mahasiswa::where('id_kota', $idKota)
            ->with('user', 'kota', 'nilaiKriteria')
            ->get();

        $kategoriPenilaian = KategoriPenilaian::where('id_fta', $idFta)
            ->with('formulirPenilaian')
            ->first();

        $kriteriaPenilaian = KriteriaPenilaian::where('id_fta', $idFta)
            ->with(['rubrik', 'nilaiKriteria' => function ($query) use ($idKota) {
                $query->whereHas('mahasiswa', function ($query) use ($idKota) {
                    $query->where('id_kota', $idKota);
                });
            }])
            ->get();

        $nilaiKriteria = $this->getNilaiKriteria($kriteriaPenilaian);

        $data = $this->mappingDataMahasiswa($kategoriPenilaian, $mahasiswa, $idKota);

        return view('KelolaPenilaianTA.views.pemberian-nilai-dan-feedback.pengisian_nilai_seminar_II', compact('data', 'mahasiswa', 'idFta', 'kriteriaPenilaian', 'nilaiKriteria'));
    }

    /**
     * Helper function untuk mendapatkan nilai kriteria
     */
    private function getNilaiKriteria($kriteriaPenilaian)
    {
        $nilaiKriteria = [];

        foreach ($kriteriaPenilaian as $index => $kriteria) {
            foreach ($kriteria->nilaiKriteria as $nilai) {
                $nilaiKriteria[$index][] = $nilai->nilai_kriteria;
            }
        }

        return $nilaiKriteria;
    }

        /**
     * Helper mapping untuk data mahasiswa di form pengisian nilai seminar 2
     */
    private function mappingDataMahasiswa($kategoriPenilaian, $mahasiswa, $idKota)
    {
        return [
            'nama_fta' => $kategoriPenilaian->formulirPenilaian->nama_fta,
            'tanggal' => $kategoriPenilaian->formulirPenilaian->tanggal_tenggat_pengisian,
            'judul_ta' => $mahasiswa->first()->kota->judul_ta,
            'waktu' => date('H:i', strtotime($mahasiswa->first()->kota->penjadwalan[0]->start)),
            'nama_kota' => $mahasiswa->first()->kota->nama_kota,
            'id_kota' => $idKota,
        ];
    }

    /**
     * Menampilkan halaman pemberian nilai seminar 3
     */
    public function pengisianNilaiSeminarIII($idFta, $idKota): View
    {
        $mahasiswa = Mahasiswa::where('id_kota', $idKota)
            ->with('user', 'kota', 'nilaiRubrik')
            ->get();

        $kategoriPenilaian = KategoriPenilaian::where('id_fta', $idFta)
            ->with('formulirPenilaian')
            ->first();

        $kriteriaPenilaian = KriteriaPenilaian::where('id_fta', $idFta)
            ->with(['rubrik.detailRubrik.nilai' , 'rubrik.nilaiRubrik' => function ($query) use ($idKota) {
                $query->whereHas('mahasiswa', function ($query) use ($idKota) {
                    $query->where('id_kota', $idKota);
                });
            }])
            ->get();

        // Log::info(json_encode($kriteriaPenilaian, JSON_PRETTY_PRINT));
        $nilaiBatas = $this->getNilaiBatas($kriteriaPenilaian);
        $data = $this->mappingDataMahasiswa($kategoriPenilaian, $mahasiswa, $idKota);

        return view('KelolaPenilaianTA.views.pemberian-nilai-dan-feedback.pengisian_nilai_seminar_III', compact('data', 'mahasiswa', 'idFta', 'kriteriaPenilaian', 'nilaiBatas'));
    }

    /**
     * Helper function untuk mendapatkan nilai batas
     */
    private function getNilaiBatas($kriteriaPenilaian)
    {
        $nilaiBatas = [];
    
        foreach ($kriteriaPenilaian as $index => $kriteria) {
            if (!empty($kriteria->rubrik) && !empty($kriteria->rubrik[0]->detailRubrik)) {
                foreach ($kriteria->rubrik[0]->detailRubrik as $rubrik) {
                    $nilaiBatas[$index][] = $rubrik->nilai;
                }
            }
        }
    
        return $nilaiBatas;
    }

    /**
     * Menyimpan nilai seminar
     */
    public function simpanNilaiSeminar(Request $request, $idFta, $idKota): View
    {
        if ($idFta == 2) {
            return $this->simpanNilaiSeminarII($request, $idFta, $idKota);
        } else if ($idFta == 4) {
            return $this->simpanNilaiSeminarIII($request, $idFta, $idKota);
        }
    }

    public function simpanNilaiSeminarII($request, $idFta, $idKota): View
    {
        $nip = auth()->user()->username;
        $nilai = $request->except('_token');

        $mahasiswa = Mahasiswa::where('id_kota', $idKota)->get();

        $this->inputNilaiKeDatabaseNilaiKriteriaSeminarII($mahasiswa, $nilai, $nip, $idFta);
        $this->inputNilaiKeDatabaseKategoriPenilaianSeminarII($mahasiswa, $nilai, $nip, $idFta);

        $pengelolaanNilai = new PengelolaanNilaiController();
        return $pengelolaanNilai->detailNilaiMahasiswa($idFta);
    }

    public function simpanNilaiSeminarIII($request, $idFta, $idKota):View
    {
        $nip = auth()->user()->username;
        $nilai = $request->except('_token');

        $mahasiswa = Mahasiswa::where('id_kota', $idKota)->get();

        $this->inputNilaiKeDatabaseNilaiRubrik($mahasiswa, $nilai, $nip, $idFta);
        $this->inputNilaiKeDatabaseNilaiKriteriaSeminarIII($mahasiswa, $nilai, $nip, $idFta);
        $this->inputNilaiKeDatabaseKategoriPenilaianSeminarIII($mahasiswa, $nilai, $nip, $idFta);

        $pengelolaanNilai = new PengelolaanNilaiController();
        return $pengelolaanNilai->detailNilaiMahasiswa($idFta);
    } 

    /**
     * Helper function untuk input nilai kategori penilaian ke database
     */
    private function inputNilaiKeDatabaseKategoriPenilaianSeminarII($mahasiswa, $nilai, $nip, $idFta): void
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
            $mhs->nilaiKategori()->create([
                'nim' => $mhs->nim,
                'nip' => $nip,
                'id_kategori' => $idKategori,
                'nilai' => $nilai_rata_rata[$index],
            ]);
        }
    }

    /**
     * Helper function untuk input nilai kriteria penilaian ke database
     */
    private function inputNilaiKeDatabaseNilaiKriteriaSeminarII($mahasiswa, $nilai, $nip, $idFta): void
    {
        $kriteriaPenilaian = KriteriaPenilaian::where('id_fta', $idFta)->get();

        foreach ($mahasiswa as $index => $mhs) {
            foreach ($kriteriaPenilaian as $kriteriaIndex => $kriteria) {
                $nilaiKriteria = (double) $nilai['nilai' . $index][$kriteriaIndex];

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

    private function inputNilaiKeDatabaseNilaiRubrik($mahasiswa, $nilai, $nip, $idFta): void
    {
        $kriteriaPenilaian = KriteriaPenilaian::where('id_fta', $idFta)->with('rubrik')->get();
        $rubrikDiKriteriaPenilaian = $this->ambilRubrikDiKriteriaPenilaian($kriteriaPenilaian);
    
        foreach ($mahasiswa as $index => $mhs) {
            foreach ($rubrikDiKriteriaPenilaian as $rubrikIndex => $rubrik) {
                $nilaiRubrik = (double) $nilai['nilai' . $index][$rubrikIndex];
    
                // Simpan nilai rubrik ke database
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

    private function inputNilaiKeDatabaseNilaiKriteriaSeminarIII($mahasiswa, $nilai, $nip, $idFta): void
    {
        $kriteriaPenilaian = KriteriaPenilaian::where('id_fta', $idFta)->with('rubrik.nilaiRubrik')->get();
        $nilaiRubrikPerMahasiswa = $this->hitungNilaiRubrikPerMahasiswa($kriteriaPenilaian);
    
        foreach ($mahasiswa as $index => $mhs) {
            foreach ($kriteriaPenilaian as $kriteriaIndex => $kriteria) {
                $nilaiKriteria = (double) $nilai['nilai' . $index][$kriteriaIndex];
                $nilaiKriteria = round($nilaiKriteria, 2);
    
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

    private function inputNilaiKeDatabaseKategoriPenilaianSeminarIII($mahasiswa, $nilai, $nip, $idFta): void
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
            $mhs->nilaiKategori()->create([
                'nim' => $mhs->nim,
                'nip' => $nip,
                'id_kategori' => $idKategori,
                'nilai' => $nilai_rata_rata[$index],
            ]);
        }
    }

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