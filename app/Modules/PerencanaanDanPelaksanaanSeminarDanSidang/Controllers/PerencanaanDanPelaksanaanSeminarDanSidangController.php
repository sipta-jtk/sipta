<?php

namespace App\Modules\PerencanaanDanPelaksanaanSeminarDanSidang\Controllers;

use App\Modules\Controller;
use Illuminate\View\View;

class PerencanaanDanPelaksanaanSeminarDanSidangController extends Controller
{
    public function index(): View
    {
        return view('PerencanaanDanPelaksanaanSeminarDanSidang.views.view');
    }
}