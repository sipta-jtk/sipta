<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;

class RekapitulasiNilaiExport implements FromCollection, WithHeadings, WithCustomStartCell
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function collection()
    {
        return collect($this->data)->map(function ($item) {
            return [
                'nim' => $item['nim'] ?? '',
                'nama' => $item['nama'] ?? '',
                'prodi' => $item['prodi'] ?? '',
                'kelas' => $item['kelas'] ?? '',
                'kelompok' => $item['kelompok'] ?? '',
                'seminar2_penguji1' => isset($item['seminar2_penguji1']) && $item['seminar2_penguji1'] != -1 ? $item['seminar2_penguji1'] : '',
                'seminar2_penguji2' => isset($item['seminar2_penguji2']) && $item['seminar2_penguji2'] != -1 ? $item['seminar2_penguji2'] : '',
                'seminar2_penguji3' => isset($item['seminar2_penguji3']) && $item['seminar2_penguji3'] != -1 ? $item['seminar2_penguji3'] : '',
                'seminar3_penguji1' => isset($item['seminar3_penguji1']) && $item['seminar3_penguji1'] != -1 ? $item['seminar3_penguji1'] : '',
                'seminar3_penguji2' => isset($item['seminar3_penguji2']) && $item['seminar3_penguji2'] != -1 ? $item['seminar3_penguji2'] : '',
                'seminar3_penguji3' => isset($item['seminar3_penguji3']) && $item['seminar3_penguji3'] != -1 ? $item['seminar3_penguji3'] : '',
                'sidang_penguji1' => isset($item['sidang_penguji1']) && $item['sidang_penguji1'] != -1 ? $item['sidang_penguji1'] : '',
                'sidang_penguji2' => isset($item['sidang_penguji2']) && $item['sidang_penguji2'] != -1 ? $item['sidang_penguji2'] : '',
                'sidang_penguji3' => isset($item['sidang_penguji3']) && $item['sidang_penguji3'] != -1 ? $item['sidang_penguji3'] : '',
                'pembimbing1' => isset($item['pembimbing1']) && $item['pembimbing1'] != -1 ? $item['pembimbing1'] : '',
                'pembimbing2' => isset($item['pembimbing2']) && $item['pembimbing2'] != -1 ? $item['pembimbing2'] : '',
                'uts' => $item['uts'] ?? '',
                'uas' => $item['uas'] ?? '',
                'lain_lain' => $item['lain_lain'] ?? '',
                'nilai_akhir' => $item['nilai_akhir'] ?? '',
                'predikat' => $item['predikat'] ?? ''
            ];
        });
    }

    public function headings(): array
    {
        return [
            [' ', ' ', ' ', ' ', ' ', 'Seminar 2', 'Seminar 2', 'Seminar 2', 'Seminar 3', 'Seminar 3', 'Seminar 3', 'Sidang Akhir', 'Sidang Akhir', 'Sidang Akhir', ' ', ' ', ' ', ' ', ' ', ' ', ' '], // Baris pertama header
            ['NIM', 'Nama', 'Prodi', 'Kelas', 'Kelompok', 'P1', 'P2', 'P3', 'P1', 'P2', 'P3', 'P1', 'P2', 'P3', 'Pembimbing 1', 'Pembimbing 2', 'UTS', 'UAS', 'Lain-Lain', 'Nilai Akhir', 'Predikat'] // Baris kedua header
        ];
    }

    public function startCell(): string
    {
        return 'A1'; 
    }
}
