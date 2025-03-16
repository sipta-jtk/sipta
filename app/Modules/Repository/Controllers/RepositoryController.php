<?php

namespace App\Modules\Repository\Controllers;

use App\Modules\Controller;
use Illuminate\View\View;

class RepositoryController extends Controller
{
    public function coba(): View
    {
        return view('Repository.views.view');
    }
    public function index(): View
    {
        return view('Repository.views.aksesD0');
    }
    public function v1(): View
    {
        return view('Repository.views.aksesD1');
    }
    public function v2(): View
    {
        return view('Repository.views.aksesD2');
    }
    public function p1(): View
    {
        return view('Repository.views.aksesP0');
    }
}