<?php

namespace App\Modules\PengajuanAlokasiPembimbing\Components\DaftarPengajuanDosbing;

use Illuminate\View\Component;

class TablePengajuan extends Component
{
    public array $kelompokData;

    public string $title;
    public string $subtitle;
    public string $tableId;
    public string $tableClass;
    public string $tableStyle;
    public string $theadClass;
    public string $theadStyle;
    public string $tbodyClass;
    public string $tbodyStyle;

    public function __construct(
        array $kelompokData = [],
        string $title = 'Daftar Pengajuan Dosen Pembimbing',
        string $subtitle = 'Daftar Pengajuan Dosen Pembimbing',
        string $tableId = 'tablePengajuan',
        string $tableClass = 'table table-bordered table-hover',
        string $tableStyle = 'width: 100%',
        string $theadClass = 'table-dark',
        string $theadStyle = '',
        string $tbodyClass = '',
        string $tbodyStyle = ''
    ) {
        $this->kelompokData = $kelompokData;
        $this->title = $title;
        $this->subtitle = $subtitle;
        $this->tableId = $tableId;
        $this->tableClass = $tableClass;
        $this->tableStyle = $tableStyle;
        $this->theadClass = $theadClass;
        $this->theadStyle = $theadStyle;
        $this->tbodyClass = $tbodyClass;
        $this->tbodyStyle = $tbodyStyle;
    }

    public function render()
    {
        return view('PengajuanAlokasiPembimbing.views.DaftarPengajuanDosbing.Components.TablePengajuan');
    }
}