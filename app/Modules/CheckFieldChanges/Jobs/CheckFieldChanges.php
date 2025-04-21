<?php

namespace App\Modules\CheckFieldChanges\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use App\Models\TemplateNotifikasi;
use App\Models\Notifikasi;
use App\Models\PreferensiNotifikasi;
use App\Jobs\SendNotification;

class CheckFieldChanges implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $modelClass; // Nama model (e.g., Dokumen::class)
    protected $recordId; // ID record yang dipantau
    protected $fieldsToCheck; // Field yang dipantau
    protected $originalValues; // Nilai awal field

    public function __construct($modelClass, $recordId, array $fieldsToCheck, array $originalValues)
    {
        $this->modelClass = $modelClass;
        $this->recordId = $recordId;
        $this->fieldsToCheck = $fieldsToCheck;
        $this->originalValues = $originalValues;
    }

    public function handle()
    {
        $record = $this->modelClass::find($this->recordId);

        if (!$record) return;

        $changedFields = [];
        $changes = [];

        // Periksa setiap field yang dipantau
        foreach ($this->fieldsToCheck as $field) {
            $currentValue = $record->$field;
            $originalValue = $this->originalValues[$field] ?? null;

            if ($currentValue !== $originalValue) {
                $changedFields[] = $field;
                $changes[] = [
                    'field' => $field,
                    'old_value' => $originalValue,
                    'new_value' => $currentValue,
                ];
            }
        }

        if (empty($changedFields)) {
            return $this->dispatchAgain(); // Ulangi job jika belum ada perubahan
        }

        // Proses notifikasi untuk setiap field yang berubah
        foreach ($changes as $change) {
            $judulNotifikasi = 'Perubahan ' . ucfirst($change['field']);
            $template = TemplateNotifikasi::where('judul_notifikasi', $judulNotifikasi)->first();

            if (!$template) continue;

            $notifikasi = Notifikasi::create([
                'tipe_notifikasi' => $template->jenis_notifikasi,
                'judul' => $template->judul_notifikasi,
                'isi_notifikasi' => str_replace(
                    ['{field}', '{old_value}', '{new_value}'],
                    [$change['field'], $change['old_value'], $change['new_value']],
                    $template->isi_in_apps
                ),
                'sumber_notifikasi' => 'sistem'
            ]);

            $preferensi = PreferensiNotifikasi::where('username', $this->getUsername($record))
                ->where('tipe_notifikasi', $template->jenis_notifikasi)
                ->first();

            if ($preferensi) {
                dispatch(new SendNotification($preferensi, $notifikasi));
            }
        }
    }

    private function getUsername($record)
    {
        if ($record instanceof \App\Models\Dokumen) {
            return $record->username;
        } elseif ($record instanceof \App\Models\AlokasiPembimbing) {
            return $record->pengajuanPembimbing->mahasiswa->username;
        } elseif ($record instanceof \App\Models\DetailFeedback) {
            return $record->dosen->user->username;
        } elseif ($record instanceof \App\Models\Kehadiran) {
            return $record->mahasiswa->username;
        }
        return null;
    }

    private function dispatchAgain()
    {
        dispatch(new self(
            $this->modelClass,
            $this->recordId,
            $this->fieldsToCheck,
            $this->originalValues
        ))->delay(now()->addSeconds(10));
    }
}