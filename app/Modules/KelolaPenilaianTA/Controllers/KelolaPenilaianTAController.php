<?php

namespace App\Modules\KelolaPenilaianTA\Controllers;

use App\Modules\Controller;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Resources\Views;


class KelolaPenilaianTAController extends Controller
{
    public function getBeranda(): View
    {
        return view('welcome');
    }
    
}