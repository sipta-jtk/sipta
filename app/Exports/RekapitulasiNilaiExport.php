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
                'seminar1Penguji1' => isset($item['seminar1Penguji1']) ? number_format($item['seminar1Penguji1'], 2) : '',
                'seminar1Penguji2' => isset($item['seminar1Penguji2']) ? number_format($item['seminar1Penguji2'], 2) : '',
                'seminar1Penguji3' => isset($item['seminar1Penguji3']) ? number_format($item['seminar1Penguji3'], 2) : '',
                'rataSeminar1' => isset($item['rataSeminar1']) ? number_format($item['rataSeminar1'], 2) : '',
                'seminar2Penguji1' => isset($item['seminar2Penguji1']) ? number_format($item['seminar2Penguji1'], 2) : '',
                'seminar2Penguji2' => isset($item['seminar2Penguji2']) ? number_format($item['seminar2Penguji2'], 2) : '',
                'seminar2Penguji3' => isset($item['seminar2Penguji3']) ? number_format($item['seminar2Penguji3'], 2) : '',
                'rataSeminar2' => isset($item['rataSeminar2']) ? number_format($item['rataSeminar2'], 2) : '',
                'seminar3Penguji1' => isset($item['seminar3Penguji1']) ? number_format($item['seminar3Penguji1'], 2) : '',
                'seminar3Penguji2' => isset($item['seminar3Penguji2']) ? number_format($item['seminar3Penguji2'], 2) : '',
                'seminar3Penguji3' => isset($item['seminar3Penguji3']) ? number_format($item['seminar3Penguji3'], 2) : '',
                'rataSeminar3' => isset($item['rataSeminar3']) ? number_format($item['rataSeminar3'], 2) : '',
                'sidangPenguji1' => isset($item['sidangPenguji1']) ? number_format($item['sidangPenguji1'], 2) : '',
                'sidangPenguji2' => isset($item['sidangPenguji2']) ? number_format($item['sidangPenguji2'], 2) : '',
                'sidangPenguji3' => isset($item['sidangPenguji3']) ? number_format($item['sidangPenguji3'], 2) : '',
                'rataSidang' => isset($item['rataSidang']) ? number_format($item['rataSidang'], 2) : '',
                'pembimbing1' => isset($item['pembimbing1']) ? number_format($item['pembimbing1'], 2) : '',
                'pembimbing2' => isset($item['pembimbing2']) ? number_format($item['pembimbing2'], 2) : '',
                'rataPembimbing' => isset($item['rataPembimbing']) ? number_format($item['rataPembimbing'], 2) : '',
            ];
        });
    }

    public function headings(): array
    {
        return [
            [' ', ' ', ' ', ' ', ' ', 'Seminar 1','Seminar 1', 'Seminar 1', 'Seminar 1', 'Seminar 2','Seminar 2', 'Seminar 2', 'Seminar 2', 'Seminar 3', 'Seminar 3', 'Seminar 3', 'Seminar 3', 'Sidang Akhir', 'Sidang Akhir', 'Sidang Akhir', 'Sidang Akhir', 'Dosen Pembimbing', 'Dosen Pembimbing', 'Dosen Pembimbing'], // Baris pertama header
            ['NIM', 'Nama', 'Prodi', 'Kelas', 'Kelompok', 'P1', 'P2', 'P3', 'Rata-rata', 'P1', 'P2', 'P3', 'Rata-rata', 'P1', 'P2', 'P3', 'Rata-rata', 'P1', 'P2', 'P3', 'Rata-rata', 'Pembimbing 1', 'Pembimbing 2', 'Rata-rata'] // Baris kedua header
        ];
    }

    public function startCell(): string
    {
        return 'A1'; 
    }
}