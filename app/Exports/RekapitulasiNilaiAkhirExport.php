<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;

class RekapitulasiNilaiAkhirExport implements FromCollection, WithHeadings, WithCustomStartCell
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
                'nilaiUts' => ($item['nilaiUts'] ?? 0) == 0 ? 'T' : number_format($item['nilaiUts'], 2),
                'nilaiUas' => ($item['nilaiUas'] ?? 0) == 0 ? 'T' : number_format($item['nilaiUas'], 2),
                'nilaiLainLain' => ($item['nilaiLainLain'] ?? 0) == 0 ? 'T' : number_format($item['nilaiLainLain'], 2),
                'nilaiAkhir' => ($item['nilaiAkhir'] ?? 0) == 0 ? 'T' : number_format($item['nilaiAkhir'], 2),
                'predikat' => ($item['nilaiAkhir'] ?? 0) == 0 ? 'T' : $item['predikat'],
            ];
        });
    }

    public function headings(): array
    {
        return [
            ['NIM', 'Nama', 'Prodi', 'Kelas', 'Kelompok', 'UTS', 'UAS', 'Lain-Lain', 'Nilai Akhir', 'Predikat'], // Baris pertama header
        ];
    }

    public function startCell(): string
    {
        return 'A1'; 
    }
}
