<?php

namespace App\Modules\UserManagement\Controllers;

use App\Modules\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TestServiceCallController extends Controller
{
    public function redirectToExternalService(Request $request)
    {
        // Generate token
        $user = Auth::user();
        $token = $user->createToken('external_service_token')->plainTextToken;

        // URL to redirect to
        $externalUrl = 'http://localhost:8080/admin/ruangan';
        
        // Add token as query parameter or in a way the external service expects
        $externalUrl .= "?token={$token}";
        
        // Redirect to external service
        return redirect()->away($externalUrl);
    }
}