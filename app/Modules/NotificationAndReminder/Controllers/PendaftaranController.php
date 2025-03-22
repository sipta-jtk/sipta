<?php

namespace App\Modules\NotificationAndReminder\Controllers;

use Illuminate\Http\Request;
use App\Services\NotifikasiService;
use App\Models\User;
use App\Http\Controllers\Controller;

class PendaftaranController extends Controller
{
    protected $notifikasiService;

    public function __construct(NotifikasiService $notifikasiService)
    {
        $this->notifikasiService = $notifikasiService;
    }

    public function daftarUser(Request $request)
    {
        try {
            $userId = $request->input('user_id'); // Ambil user_id dari request
            $templateId = $request->input('template_id'); // Ambil template_id dari request

            if (!$userId || !$templateId) {
                return response()->json(['error' => 'user_id dan template_id wajib diisi'], 400);
            }

            // Ambil user dari database berdasarkan userId
            $user = User::find($userId);

            if (!$user) {
                throw new \Exception('User tidak ditemukan');
            }

            $this->notifikasiService->kirimEmail(
                $templateId, 
                $userId, 
                [
                    'name' => $user->name, 
                    'task' => $request->input('task'), 
                    'deadline' => $request->input('deadline')
                ]
            );            

            return response()->json(['message' => 'Pendaftaran berhasil, email dikirim']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}