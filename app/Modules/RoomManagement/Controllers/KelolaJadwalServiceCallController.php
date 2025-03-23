<?php

namespace App\Modules\RoomManagement\Controllers;

use App\Modules\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KelolaJadwalServiceCallController extends Controller
{
    public function redirectToKelolaRuangan(Request $request)
    {
        // Generate token
        $user = Auth::user();
        $token = $user->createToken('external_service_token')->plainTextToken;

        // URL to redirect to
        $externalUrl = 'http://localhost:8005/admin/ruangan';
        
        // Add token as query parameter or in a way the external service expects
        $externalUrl .= "?token={$token}";
        
        // Redirect to external service
        return redirect()->away($externalUrl);
    }

    public function redirectToKalender(Request $request)
    {
        $externalUrl = 'http://localhost:8005/penjadwalan-ruangan';
        
        // Redirect to external service
        return redirect()->away($externalUrl);
    }
}