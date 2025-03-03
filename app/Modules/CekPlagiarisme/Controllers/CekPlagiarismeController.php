<?php

namespace App\Modules\CekPlagiarisme\Controllers;

use App\Modules\Controller;
use Illuminate\View\View;

class CekPlagiarismeController extends Controller
{
    public function index(): View
    {
        return view('CekPlagiarisme.views.view');
    }
}