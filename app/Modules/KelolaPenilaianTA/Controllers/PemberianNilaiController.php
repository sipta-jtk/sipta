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
        Log::info('detailInformasiFta'. json_encode($detailInformasiFta, JSON_PRETTY_PRINT));

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
            $this->simpanNilaiBerdasarkanKriteria($request, $namaFtaSlug, $idKota);
        } else {
            $this->simpanNilaiBerdasarkanRubrik($request, $namaFtaSlug, $idKota);
        }

        return redirect()->back()->with('success', 'Nilai berhasil disimpan');
    }

    private function simpanNilaiBerdasarkanKriteria($request, $namaFtaSlug, $idKota)
    {
        $nip = auth()->user()->username;
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

        $nilai_rata_rata = $this->ubahNilaiKeDatabaseNilaiKriteria($nilai, $mahasiswa, $kriteriaPenilaian, $nip);
        $this->ubahNilaiKeDatabaseNilaiKategori($nilai_rata_rata, $mahasiswa, $kategoriPenilaian, $nip);
    }

    private function simpanNilaiBerdasarkanRubrik($request, $namaFtaSlug, $idKota)
    {
        $nip = auth()->user()->username;
        $nilai = $request->except('_token', '_method');
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

        $nilai_rata_rata_rubrik = $this->ubahNilaiKeDatabaseNilaiRubrik($nilai, $mahasiswa, $kriteriaPenilaian, $nip);
        $nilai_rata_rata_kriteria = $this->ubahNilaiKeDatabaseNilaiKriteria($nilai_rata_rata_rubrik, $mahasiswa, $kriteriaPenilaian, $nip);
        $this->ubahNilaiKeDatabaseNilaiKategori($nilai_rata_rata_kriteria, $mahasiswa, $kategoriPenilaian, $nip);
    }
    

    private function ubahNilaiKeDatabaseNilaiRubrik($nilai, $mahasiswa, $kriteriaPenilaian, $nip): array
    {
        $nilai_rata_rata = [];
    
        foreach ($mahasiswa as $index => $mhs) {
            $nilai_rata_rata[$index] = []; // Menyimpan rata-rata nilai per mahasiswa
            $nilaiIndex = 0; // Menunjuk indeks nilai yang sedang diproses
    
            foreach ($kriteriaPenilaian as $kriteria) {
                $jumlahRubrik = count($kriteria['rubrik']);
                $totalNilai = 0;
    
                foreach ($kriteria['rubrik'] as $rubrik) {
                    // Hapus hanya nilai rubrik spesifik untuk mahasiswa ini
                    $mhs->nilaiRubrik()
                        ->where('nim', $mhs->nim)
                        ->where('nip', $nip)
                        ->where('id_rubrik', $rubrik['id_rubrik'])
                        ->delete();
    
                    $nilaiRubrik = (double) $nilai[$index][$nilaiIndex];
    
                    // Insert ulang data baru
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
    
                // Hitung rata-rata nilai rubrik dalam satu kriteria
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
        Log::info('nilai'. json_encode($nilai, JSON_PRETTY_PRINT));
    
        foreach ($mahasiswa as $index => $mhs) {
            $totalNilai = 0;
            $totalBobot = array_sum($bobotKriteria);
    
            foreach ($kriteriaPenilaian as $kriteriaIndex => $kriteria) {
                $nilaiKriteria = (double) $nilai[$index][$kriteriaIndex];
    
                // Hapus data lama berdasarkan composite key
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
    
            // Buat data baru
            $mhs->nilaiKategori()->create([
                'nim' => $mhs->nim,
                'nip' => $nip,
                'id_kategori' => $idKategori,
                'nilai' => $nilai_rata_rata[$index],
            ]);
        }
    }
    

    public function ubahNilaiSeminar(Request $request, $namaFta, $idKota)
    {
        $namaFtaSlug = Str::slug($namaFta, ' ');
        if ($namaFtaSlug == 'seminar ii') {
            $this->simpanNilaiBerdasarkanKriteria($request, $namaFtaSlug, $idKota);
        } else {
             $this->simpanNilaiBerdasarkanRubrik($request, $namaFtaSlug, $idKota);
        }

        return redirect()->back()->with('success', 'Nilai berhasil diubah');
    }
}