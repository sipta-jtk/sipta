<?php

namespace App\Modules\PerencanaanDanPelaksanaanSeminar3DanSidang\Controllers;

use App\Modules\Controller;
use Illuminate\View\View;

class PerencanaanDanPelaksanaanSeminar3DanSidangController extends Controller
{
    public function index(): View
    {
        return view('PerencanaanDanPelaksanaanSeminar3DanSidang.views.view');
    }

    public function indexAlokasiPenguji(): View
    {
        $dosen = collect([
            (object) [
                'id' => 1,
                'nama' => 'Alice'
            ],
            (object) [
                'id' => 2,
                'nama' => 'Bob'
            ],
            (object) [
                'id' => 3,
                'nama' => 'Charlie'
            ],
            (object) [
                'id' => 4,
                'nama' => 'David'
            ],
        ]);
        $KoTA = collect([
            (object) [
                'id' => 1,
                'nama' => 'KoTA 011',
                'judul' => 'Penelitian 1',
                'pembimbing' => 'David'
            ],
            (object) [
                'id' => 2,
                'nama' => 'KoTA 012',
                'judul' => 'Penelitian 2',
                'pembimbing' => 'Alice'
            ],
            (object) [
                'id' => 3,
                'nama' => 'KoTA 013',
                'judul' => 'Penelitian 3',
                'pembimbing' => 'Bob'
            ],
            (object) [
                'id' => 4,
                'nama' => 'KoTA 014',
                'judul' => 'Penelitian 4',
                'pembimbing' => 'Charlie'
            ],
        ]);
        return view('PerencanaanDanPelaksanaanSeminar3DanSidang.views.alokasipenguji.alokasipenguji', compact('dosen', 'KoTA'));
    }
}