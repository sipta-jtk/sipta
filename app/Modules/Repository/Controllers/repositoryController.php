<?php

namespace App\Modules\repository\Controllers;

use App\Modules\Controller;
use Illuminate\View\View;

class RepositoryController extends Controller
{
    public function index(): View
    {
        return view('Repository.Views.view');
    }

    public function logAktivitas(): View
    {
        return view('Repository.Views.log_aktivitas');
    }

    public function monitoringPenyimpanan(): View
    {
        return view('Repository.Views.monitoring_penyimpanan');
    }
}