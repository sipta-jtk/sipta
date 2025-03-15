<?php

namespace App\Modules\KelolaPenilaianTA\Controllers;

use App\Modules\Controller;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Exports\RekapitulasiNilaiExport;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\FromCollection;

class FormulirPenilaianController extends Controller {

    public function formulirPenilaian(): View
    {
        for ($i = 1; $i <= 100; $i++) {
            $data = [
                [
                    'kode' => 'FTA 04',
                    'nama' => 'Penilaian Seminar I',
                ],
                [
                    'kode' => 'FTA 07',
                    'nama' => 'Penilaian Semester II',
                ],
                [
                    'kode' => 'FTA 08',
                    'nama' => 'Masukan Seminar II',
                ],
                [
                    'kode' => 'FTA 011',
                    'nama' => 'Penilaian Semester III',
                ],
                [
                    'kode' => 'FTA 012',
                    'nama' => 'Masukan Seminar III',
                ],
            ];
        }

        return view('KelolaPenilaianTA.views.formulir-penilaian.formulir_penilaian_ta', compact('data'));
    }

    public function tambahFormulir(): View
    {
        return view('KelolaPenilaianTA.views.formulir-penilaian.tambah_formulir_penilaian');
    }

    public function tambahAspekFormulir(): View
    {
        return view('KelolaPenilaianTA.views.formulir-penilaian.tambah_aspek_formulir');
    }

    public function ubahFormulir()
    {
        // Data dummy untuk sementara
        $formulir = (object) [
            'kodeFTA' => 'FTA.011',
            'namaFTA' => 'PENILAIAN SEMINAR III',
            'tanggalTenggat' => '2025-03-05',
            'waktuTenggat' => '23:59',
            'aspekPenilaian' => [
                (object) [
                    'kriteria' => 'Kejelasan isi dokumen',
                    'bobot' => 40,
                    'detail' => 'Kejelasan kaitan antar bab/sub bab/kaitan (hubungan sebab akibat/ reasoning, rasionalitas)',
                    'lebih80' => 'Deskripsi sangat jelas dan rinci',
                    'tujuhPuluhLima' => 'Deskripsi cukup jelas namun ada bagian yang perlu diperjelas',
                    'tujuhPuluh' => 'Deskripsi kurang jelas dan perlu banyak perbaikan',
                    'enamPuluhLima' => 'Deskripsi tidak jelas dan membingungkan',
                    'enamPuluh' => 'Deskripsi tidak jelas dan membingungkan',
                    'kurang60' => 'Deskripsi tidak jelas dan membingungkan',
                ],
            ],
        ];

        return view('KelolaPenilaianTA.views.formulir-penilaian.ubah_formulir_ta', compact('formulir'));
    }

    public function updateFormulir(Request $request)
    {
        return redirect()->route('formulir-penilaian.index')->with('success', 'Formulir penilaian berhasil diperbarui.');
    }

}