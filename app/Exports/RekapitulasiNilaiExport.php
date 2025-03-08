<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class RekapitulasiNilaiExport implements FromCollection, WithHeadings
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function collection()
    {
        return collect($this->data);
    }

    public function headings(): array
    {
        return [
            'NIM', 'Nama', 'Prodi', 'Kelas', 'Kelompok', 'Seminar 2 P1', 'Seminar 2 P2', 'Seminar 2 P3',
            'Seminar 3 P1', 'Seminar 3 P2', 'Seminar 3 P3', 'Sidang P1', 'Sidang P2', 'Sidang P3',
            'Pembimbing 1', 'Pembimbing 2', 'UTS', 'UAS', 'Lain-Lain', 'Nilai Akhir', 'Predikat'
        ];
    }
}