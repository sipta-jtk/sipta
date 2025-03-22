<?php

namespace App\Modules\CheckFieldChanges\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendEmailNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $preferensi; // Data preferensi notifikasi pengguna
    protected $notifikasi; // Data notifikasi yang ingin dikirim

    public function __construct($preferensi, $notifikasi)
    {
        $this->preferensi = $preferensi;
        $this->notifikasi = $notifikasi;
    }

    public function handle()
    {
        // Ambil email pengguna dari relasi user
        $email = $this->preferensi->user->email;

        // Ambil subjek dan isi email dari data notifikasi
        $subject = $this->notifikasi->judul;
        $body = $this->notifikasi->isi_notifikasi;

        // Kirim email menggunakan Laravel Mail
        Mail::raw($body, function ($message) use ($email, $subject) {
            $message->to($email)->subject($subject);
        });
    }
}