<?php

namespace App\Services;

use App\Models\TemplateNotifikasi;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class NotifikasiService
{
    public function kirimEmail($templateId, $userId, $data)
    {
        // Retrieve the user by ID
        $user = User::find($userId);
    
        if (!$user) {
            throw new \Exception('User tidak ditemukan');
        }
    
        // Retrieve the email template by ID
        $template = TemplateNotifikasi::find($templateId);
    
        if (!$template) {
            throw new \Exception("Template email tidak ditemukan");
        }
    
        // Replace placeholders in the subject and body with data
        $subject = $this->replacePlaceholders($template->judul_notifikasi, $data);
        $body = $data['email_content'] ?? $this->replacePlaceholders($template->isi_in_email, $data);
    
        // Send the email using the generated subject and body
        Mail::send([], [], function ($message) use ($user, $subject, $body) {
            $message->to($user->email)
                    ->subject($subject)
                    ->html($body); // Use 'html()' for HTML email content
        });
    
        return "Email berhasil dikirim ke {$user->email}";
    }

    private function replacePlaceholders($text, $data)
    {
        foreach ($data as $key => $value) {
            $text = str_replace("{{{$key}}}", $value, $text); // Replace placeholders like {{key}}
        }
        return $text;
    }
}