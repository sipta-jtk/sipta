<?php

namespace App\Modules\UserManagement\Controllers;

use App\Modules\Controller;
use App\Models\User;

class UserManagementController extends Controller
{
    public function render()
    {
        return view('UserManagement.views.index');
    }
}