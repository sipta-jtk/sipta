<?php

namespace App\Modules\KelolaPenilaianTA\Controllers;

use App\Modules\Controller;
use Illuminate\View\View;

class KelolaPenilaianTAController extends Controller
{
    public function index(): View
    {
        return view('KelolaPenilaianTA.views.view');
    }

    public function indexMonitoringMahasiswa(): View
    {
        return view('KelolaPenilaianTA.views.monitoring_mahasiswa');
    }

    public function indexMonitoringFeedback(): View
    {
        return view('KelolaPenilaianTA.views.monitoring_feedback');
    }

    public function indexMonitoringRubrik(): View
    {
        return view('KelolaPenilaianTA.views.monitoring_rubrik');
    }
}