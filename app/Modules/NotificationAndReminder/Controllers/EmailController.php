<?php

namespace App\Modules\NotificationAndReminder\Controllers;

use Illuminate\Http\Request;
use App\Services\NotifikasiService;
use App\Http\Controllers\Controller;

class EmailController extends Controller
{
    protected $notifikasiService;

    public function __construct(NotifikasiService $notifikasiService)
    {
        $this->notifikasiService = $notifikasiService;
    }

    public function kirimEmail(Request $request)
    {
        try {
            $userId = $request->input('user_id'); // Ambil user_id dari request
    
            $this->notifikasiService->kirimEmail(
                'Welcome Email',
                $userId,
                ['name' => '']
            );
    
            return response()->json(['message' => 'Email berhasil dikirim']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}