<?php

namespace App\Modules\UserManagement\Controllers;

// use Illuminate\Http\Request;
use App\Modules\Controller;
use App\Models\User;

class DetailKoTAController extends Controller
{
    public function index()
    {
        return view('UserManagement.views.detail-kota');
    }
}
