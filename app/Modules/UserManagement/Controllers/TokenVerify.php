<?php

namespace App\Modules\UserManagement\Controllers;

use App\Modules\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Laravel\Sanctum\PersonalAccessToken;

class TokenVerify extends Controller
{
    public function verifYToken(Request $request)
    {
        // Get token from query parameter
        $token = $request->query('token');

        if (!$token) {
            return Response::json([
                'message' => 'Token not found'
            ], 401);
        }

        $accessToken = PersonalAccessToken::findToken($token);
        
        if (!$accessToken) {
            return Response::json(['message' => 'Invalid token'], 401);
        }

        $user = $accessToken->tokenable; // Get the associated user

        // return user
        return Response::json([
            'role' => $user->role_user
        ]);
    }
}