<?php

namespace App\Services;

use App\Models\TemplateNotifikasi;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class NotifikasiService
{
    public function kirimEmail($templateJudul, $username, $data)
    {
        // Cari user berdasarkan username
        $user = User::where('username', $username)->first();
    
        if (!$user) {
            throw new \Exception('User tidak ditemukan oleh service');
        }
    
        // Retrieve the email template by judul_notifikasi
        $template = TemplateNotifikasi::where('judul_notifikasi', trim($templateJudul))->first();
    
        if (!$template) {
            throw new \Exception("Template email tidak ditemukan oleh service");
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