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
                'seminar2Penguji1' => $item['seminar2Penguji1'] ?? '',
                'seminar2Penguji2' => $item['seminar2Penguji2'] ?? '',
                'seminar2Penguji3' => $item['seminar2Penguji3'] ?? '',
                'rataSeminar2' => $item['rataSeminar2'] ?? '',
                'seminar3Penguji1' => $item['seminar3Penguji1'] ?? '',
                'seminar3Penguji2' => $item['seminar3Penguji2'] ?? '',
                'seminar3Penguji3' => $item['seminar3Penguji3'] ?? '',
                'rataSeminar3' => $item['rataSeminar3'] ?? '',
                'sidangPenguji1' => $item['sidangPenguji1'] ?? '',
                'sidangPenguji2' => $item['sidangPenguji2'] ?? '',
                'sidangPenguji3' => $item['sidangPenguji3'] ?? '',
                'rataSidang' => $item['rataSidang'] ?? '',
                'pembimbing1' => $item['pembimbing1'] ?? '',
                'pembimbing2' => $item['pembimbing2'] ?? '',
                'rataPembimbing' => $item['rataPembimbing'] ?? '',
            ];
        });
    }

    public function headings(): array
    {
        return [
            [' ', ' ', ' ', ' ', ' ', 'Seminar 2', 'Seminar 2', 'Seminar 2', 'Seminar 2', 'Seminar 3', 'Seminar 3', 'Seminar 3', 'Seminar 3', 'Sidang Akhir', 'Sidang Akhir', 'Sidang Akhir', 'Sidang Akhir', 'Dosen Pembimbing', 'Dosen Pembimbing', 'Dosen Pembimbing'], // Baris pertama header
            ['NIM', 'Nama', 'Prodi', 'Kelas', 'Kelompok', 'P1', 'P2', 'P3', 'Rata-rata', 'P1', 'P2', 'P3', 'Rata-rata', 'P1', 'P2', 'P3', 'Rata-rata', 'Pembimbing 1', 'Pembimbing 2', 'Rata-rata'] // Baris kedua header
        ];
    }

    public function startCell(): string
    {
        return 'A1'; 
    }
}
