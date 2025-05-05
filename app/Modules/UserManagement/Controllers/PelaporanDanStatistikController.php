<?php
 
namespace App\Http\Controllers;
 
use Illuminate\Http\Request;
use App\Modules\Controller;
use App\Modules\User;
use App\Modules\Mahasiswa;
use App\Modules\Dosen;

 
class PelaporanDanStatistikController extends Controller
{
    public function getRoleData()
    {
        $userData = User::with('username')->get();

        $dosen = 
        $koordinatorTA = Dosen::where('role_dosen', 'koordinator_ta')->count();
        $dosenPembimbing = Dosen::where('bersedia_membimbing', 'bersedia')->count();
        
    }
}