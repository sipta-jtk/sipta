<?php

namespace App\Modules\KelolaPenilaianTA\Controllers;

use App\Modules\Controller;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Exports\RekapitulasiNilaiExport;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\FromCollection;

class MonitoringNilaiMahasiswaController extends Controller{

     /**
     * Menampilkan halaman monitoring nilai mahasiswa
     */
    public function monitoringMahasiswa(): View
    {
        return view('KelolaPenilaianTA.views.monitoring-nilai-mahasiswa.monitoring_mahasiswa');
    }

    /**
     * Menampilkan halaman monitoring feedback
     * 
     */
    public function monitoringFeedback(): View
    {
        return view('KelolaPenilaianTA.views.monitoring-nilai-mahasiswa.monitoring_feedback');
    }

    /**
     * Menampilkan halaman monitoring rubrik
     */
    public function monitoringRubrik(): View
    {
        return view('KelolaPenilaianTA.views.monitoring-nilai-mahasiswa.monitoring_rubrik');
    }

}