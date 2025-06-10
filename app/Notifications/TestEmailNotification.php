<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\TemplateNotifikasi;
use App\Modules\NotificationAndReminder\helper\DynamicPlaceholderParser;
use App\Modules\NotificationAndReminder\helper\PlaceholderHelper;
use App\Models\Notifikasi;
use App\Models\NotifikasiKirim;


class TestEmailNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $templateJudul;
    protected $data;

    /**
     * Create a new notification instance.
     */
    public function __construct(string $templateJudul, array $data = [])
    {
        $this->templateJudul = $templateJudul;
        $this->data = $data;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $template = TemplateNotifikasi::where('judul_notifikasi', trim($this->templateJudul))->first();

        if (!$template) {
            throw new \Exception("Template notifikasi tidak ditemukan");
        }

        // Ambil data default dari .json
        $defaultData = PlaceholderHelper::get($this->templateJudul);

        // Gabungkan dengan data yang diberikan
        $data = array_merge($defaultData, $this->data);

        // Tambahkan 'nama' user jika belum ada
        if (!isset($data['nama']) && isset($notifiable->nama)) {
            $data['nama'] = $notifiable->nama;
        }

        // Proses placeholder dinamis
        $data = DynamicPlaceholderParser::parse($data);

        // Gantikan placeholder
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
            'username' => $notifiable->username,
            'kanal' => 'email',
            'status' => 'berhasil',
            'waktu_kirim' => now(),
            'respon_log' => json_encode(['to' => $notifiable->email])
        ]);
    // $preferensi = PreferensiNotifikasi::where('username', $notifiable->username)
    //     ->first();

    // Periksa apakah $preferensi ada (tidak null) DAN nilai emailnya adalah '1'
    // if ($preferensi && $preferensi->email == '1') {
    //     return (new MailMessage)
    //         ->subject($isiJudul)
    //         ->view('vendor.notifications.email', ['content' => $isiEmail]);
    // }
    return (new MailMessage)
            ->subject($isiJudul)
            ->view('vendor.notifications.email', ['content' => $isiEmail]);
    }

    private function replacePlaceholders($text, $data)
    {
        foreach ($data as $key => $value) {
            $text = str_replace(['{{' . $key . '}}', '{' . $key . '}'], $value, $text);
        }
        return $text;
    }
}