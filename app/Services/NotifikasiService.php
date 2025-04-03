<?php

namespace App\Services;


use App\Models\TemplateNotifikasi;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class NotifikasiService
{
    public function kirimEmail($templateId, $userId, $data)
    {
        // Ambil user berdasarkan ID
        $user = User::find($userId);

        if (!$user) {
            throw new \Exception('User tidak ditemukan');
        }

        // Ambil template berdasarkan ID
        $template = TemplateNotifikasi::find($templateId);

        if (!$template) {
            throw new \Exception("Template email tidak ditemukan");
        }

        // Tambahkan nama user ke data jika belum ada
        if (!isset($data['name'])) {
            $data['name'] = $user->name;
        }

        // Ganti placeholder dengan data
        $subject = $this->replacePlaceholders($template->subject, $data);
        $body = $this->replacePlaceholders($template->body, $data);

        // Kirim email
        Mail::raw($body, function ($message) use ($user, $subject) {
            $message->to($user->email)->subject($subject);
        });

        return "Email berhasil dikirim ke {$user->email}";
    }

    private function replacePlaceholders($text, $data)
    {
        foreach ($data as $key => $value) {
            $text = str_replace("{{{$key}}}", $value, $text);
        }
        return $text;
    }
}