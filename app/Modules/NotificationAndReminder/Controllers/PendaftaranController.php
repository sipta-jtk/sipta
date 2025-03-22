<?php

namespace App\Modules\NotificationAndReminder\Controllers;

use Illuminate\Http\Request;
use App\Services\NotifikasiService;
use App\Models\User;
use App\Models\Notifikasi;
use App\Models\TemplateNotifikasi;
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
            $userId = $request->input('username'); // Get user_id from the request
            $templateId = $request->input('template_id'); // Get template_id from the request

            if (!$userId || !$templateId) {
                return response()->json(['error' => 'username dan template_id wajib diisi'], 400);
            }

            // Retrieve the user from the database based on userId
            $user = User::find($userId);

            if (!$user) {
                throw new \Exception('User tidak ditemukan');
            }

            // Fetch the notification template from the database
            $template = TemplateNotifikasi::find($templateId);

            if (!$template) {
                throw new \Exception('Template notifikasi tidak ditemukan');
            }

            // Customize the email content based on the template and user data
            $isiInEmail = str_replace(
                ['{{name}}', '{{task}}', '{{deadline}}'],
                [$user->name, $request->input('task'), $request->input('deadline')],
                $template->isi_in_email
            );

            // Save the notification data to the 'notifikasi' table using 'isi_in_email'
            $notifikasi = Notifikasi::create([
                'tipe_notifikasi' => $template->jenis_notifikasi,
                'judul' => $template->judul_notifikasi,
                'isi_notifikasi' => $isiInEmail, // Load content from isi_in_email field
            ]);

            // Send the email via NotifikasiService using the customized email content
            $this->notifikasiService->kirimEmail(
                $templateId,
                $userId,
                [
                    'name' => $user->name,
                    'task' => $request->input('task'),
                    'deadline' => $request->input('deadline'),
                    'email_content' => $isiInEmail
                ]
            );

            return response()->json([
                'message' => 'Pendaftaran berhasil, email dikirim',
                'notifikasi_id' => $notifikasi->id_notifikasi, // Return the notification ID for reference
                'email_content' => $isiInEmail, // Include the email content in the response
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}