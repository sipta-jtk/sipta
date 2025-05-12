<?php

namespace App\Services;

use App\Models\TemplateNotifikasi;
use App\Models\Notifikasi;
use App\Models\NotifikasiKirim;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class NotifikasiService
{
    public function kirim($templateJudul, $username, array $data)
    {
        $user = User::where('username', $username)->first();
        if (!$user) {
            throw new \Exception('User tidak ditemukan oleh service');
        }

        $template = TemplateNotifikasi::where('judul_notifikasi', trim($templateJudul))->first();
        if (!$template) {
            throw new \Exception("Template email tidak ditemukan oleh service");
        }

        // Tambahkan nama ke data jika belum ada
        if (!isset($data['nama'])) {
            $data['nama'] = $user->nama;
        }

        $isiEmail = $this->replacePlaceholders($template->isi_in_email, $data);

        $notifikasi = Notifikasi::create([
            'tipe_notifikasi' => $template->jenis_notifikasi,
            'judul' => $template->judul_notifikasi,
            'isi_notifikasi' => $isiEmail,
        ]);

        // Simpan ke tabel notifikasi_kirim
        $notifikasiKirim = NotifikasiKirim::create([
            'id_notifikasi' => $notifikasi->id_notifikasi,
            'username' => $user->username,
            'kanal' => 'email',
            'status' => 'berhasil',
            'waktu_kirim' => now(),
            'respon_log' => json_encode(['to' => $user->email])
        ]);


        Mail::send([], [], function ($message) use ($user, $template, $isiEmail, $data) {
            $subject = $this->replacePlaceholders($template->judul_notifikasi, $data);
            $message->to($user->email)
                    ->subject($subject)
                    ->html($isiEmail);
        });

        return [
            'status' => 'success',
            'notifikasi_id' => $notifikasi->id_notifikasi,
            'email_content' => $isiEmail,
            'to' => $user->email,
        ];
    }

    private function replacePlaceholders($text, $data)
    {
        foreach ($data as $key => $value) {
            $text = str_replace(['{{' . $key . '}}', '{' . $key . '}'], $value, $text);
        }
        return $text;
    }
}
