<?php

namespace App\Modules\RepositoryTA\Controllers;

use App\Modules\Controller;
use Illuminate\View\View;

class RepositoryTAController extends Controller
{
    public function index(): View
    {
        $data = collect([
            'Laporan Tugas Akhir' => [
                ['key' => 'revisi_sidang', 'label' => 'Laporan Tugas Akhir versi hasil revisi sidang', 'url' => '#'],
                ['key' => 'seminar_3', 'label' => 'Laporan Tugas Akhir versi hasil seminar 3', 'url' => '#'],
                ['key' => 'seminar_2', 'label' => 'Laporan Tugas Akhir versi hasil seminar 2', 'url' => '#'],
                ['key' => 'seminar_1', 'label' => 'Laporan Tugas Akhir versi hasil seminar 1', 'url' => '#'],
            ],
            'Dokumen Pendukung' => [
                ['key' => 'cover_abstrak', 'label' => 'Cover dan Abstrak', 'url' => '#'],
                ['key' => 'artikel', 'label' => 'Artikel Ilmiah', 'url' => '#'],
                ['key' => 'poster', 'label' => 'Poster', 'url' => '#'],
                ['key' => 'fta', 'label' => 'FTA', 'url' => '#'],
            ],
            'Kode Sumber' => [
                ['key' => 'source_code', 'label' => 'Source Code', 'url' => '#'],
                ['key' => 'link_source_code', 'label' => 'Link Source Code', 'url' => '#'],
            ],
            'Artefak' => [
                ['key' => 'artefak', 'label' => 'Artefak', 'url' => '#']
            ]
        ]);

        return view('RepositoryTA.views.view', compact('data'));
    }

    public function list_kelompok_ta(): View
    {
        return view('RepositoryTA.views.list_kelompok_ta');
    }
}