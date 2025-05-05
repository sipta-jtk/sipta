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
                'nilaiUtsTeori' => ($item['nilaiUtsTeori'] ?? 0) == 0 ? 'T' : number_format($item['nilaiUtsTeori'], 2),
                'nilaiPraktikumETS' => ($item['nilaiPraktikumETS'] ?? 0) == 0 ? 'T' : number_format($item['nilaiPraktikumETS'], 2),
                'nilaiLainLainETS' => ($item['nilaiLainLainETS'] ?? 0) == 0 ? 'T' : number_format($item['nilaiLainLainETS'], 2),
                'nilaiUasTeori' => ($item['nilaiUasTeori'] ?? 0) == 0 ? 'T' : number_format($item['nilaiUasTeori'], 2),
                'nilaiPraktikumEAS' => ($item['nilaiPraktikumEAS'] ?? 0) == 0 ? 'T' : number_format($item['nilaiPraktikumEAS'], 2),
                'nilaiLainLainEAS' => ($item['nilaiLainLainEAS'] ?? 0) == 0 ? 'T' : number_format($item['nilaiLainLainEAS'], 2),
                'nilaiPjBl' => ($item['nilaiPjBl'] ?? 0) == 0 ? 'T' : number_format($item['nilaiPjBl'], 2),
                'nilaiPartisipatif' => ($item['nilaiPartisipatif'] ?? 0) == 0 ? 'T' : number_format($item['nilaiPartisipatif'], 2),
                'nilaiAkhir' => ($item['nilaiAkhir'] ?? 0) == 0 ? 'T' : number_format($item['nilaiAkhir'], 2),
                'predikat' => ($item['nilaiAkhir'] ?? 0) == 0 ? 'T' : $item['predikat'],
            ];
        });
    }

    public function headings(): array
    {
        return [
            ['NIM', 'Nama', 'Prodi', 'Kelas', 'Kelompok', 'UTS (Teori)', 'Praktikum ETS', 'Lain - lain ETS', 'UAS (Teori)', 'Praktikum EAS', 'Lain - lain EAS', 'PjBL', 'Partisipatif', 'Nilai Akhir', 'Predikat'], // Baris pertama header
        ];
    }

    public function startCell(): string
    {
        return 'A1'; 
    }
}
