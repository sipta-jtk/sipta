<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Notifications\TestEmailNotification;
use App\Models\User;
use App\Models\TemplateNotifikasi;

class SendTestEmail extends Command
{
    protected $signature = 'email:test {email : The email address to send the test to} {template? : Template judul to use}';
    protected $description = 'Send a test email notification using template';

    public function handle()
    {
        $email = $this->argument('email');
        $templateJudul = $this->argument('template');

        // Find user by email
        $user = User::where('email', $email)->first();

        if (!$user) {
            $this->error("User dengan email {$email} tidak ditemukan!");
            return 1;
        }

        if (!$templateJudul) {
            // List available templates
            $templates = TemplateNotifikasi::pluck('judul_notifikasi')->toArray();
            $templateJudul = $this->choice(
                'Pilih template yang akan digunakan:',
                $templates
            );
        }

        try {
            $data = [
                'nama' => $user->nama,
                'email' => $user->email,
                'username' => $user->username
            ];
            
            $user->notify(new TestEmailNotification($templateJudul, $data));
            $this->info("Test email notification berhasil dikirim ke: {$email}");
            $this->info("Template yang digunakan: {$templateJudul}");
        } catch (\Exception $e) {
            $this->error("Failed to send email: " . $e->getMessage());
        }
    }
}
