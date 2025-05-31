<?php

namespace App\Services;

use App\Modules\NotificationAndReminder\helper\DynamicPlaceholderParser;
use App\Models\TemplateNotifikasi;
use App\Models\Notifikasi;
use App\Models\NotifikasiKirim;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use App\Modules\NotificationAndReminder\helper\PlaceholderHelper;

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
            throw new \Exception("Template notifikasi tidak ditemukan oleh service");
        }

        // Ambil data default dari .json
        $defaultData = PlaceholderHelper::get($templateJudul);

        // Gabungkan dengan data yang diberikan
        $data = array_merge($defaultData, $data);

        // Tambahkan 'nama' user jika belum ada
        if (!isset($data['nama'])) {
            $data['nama'] = $user->nama;
        }

        // Proses placeholder dinamis (tanggal, waktu, dst.)
        $data = DynamicPlaceholderParser::parse($data);

        // Gantikan placeholder di subject & isi email
        $isiEmail = $this->replacePlaceholders($template->isi_in_email, $data);
        $isiJudul = $this->replacePlaceholders($template->judul_notifikasi, $data);

        // Simpan notifikasi
        $notifikasi = Notifikasi::create([
            'tipe_notifikasi' => $template->jenis_notifikasi,
            'judul' => $isiJudul,
            'isi_notifikasi' => $isiEmail,
        ]);

        // Simpan log pengiriman
        NotifikasiKirim::create([
            'id_notifikasi' => $notifikasi->id_notifikasi,
            'username' => $user->username,
            'kanal' => 'email',
            'status' => 'berhasil',
            'waktu_kirim' => now(),
            'respon_log' => json_encode(['to' => $user->email])
        ]);

        // Kirim email
        Mail::send([], [], function ($message) use ($user, $isiJudul, $isiEmail) {
            $message->to($user->email)
                    ->subject($isiJudul)
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
