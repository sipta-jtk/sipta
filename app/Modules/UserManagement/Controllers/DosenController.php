<?php

namespace App\Modules\UserManagement\Controllers;

use App\Models\Dosen;
use App\Modules\Controller;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class DosenController extends Controller
{


    public function update_role(Request $request)
    {

        
        $request->validate([
            'nip' => 'required',
            'role' => 'required|in:dosen,koordinator_ta,kajur'
        ]);
        $nip = $request->nip;
        $old_role = Dosen::where('nip', $nip)->value('role_dosen');
        if($request->role == "kajur" || $old_role == "kajur"){
            exit;
        }

        Dosen::where('nip', $nip)->update([
            'role_dosen' => $request->role
        ]);

        return redirect()->route('manage.dosen');
    }

}

