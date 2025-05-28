<?php

namespace App\Observers;

use App\Models\Dokumen;
use App\Models\LogAktivitas;
use Illuminate\Support\Facades\Auth;

class DokumenObserver
{
    public function created(Dokumen $dokumen)
    {
        $this->log('Membuat dokumen', $dokumen);
    }

    public function updated(Dokumen $dokumen)
    {
        $this->log('Memperbarui dokumen', $dokumen);
    }

    public function deleting(Dokumen $dokumen)
    {
        $this->log('Menghapus dokumen', $dokumen);
    }

    protected function log(string $action, Dokumen $dokumen)
    {
        try {
            if (!Auth::check()) {
                return;
            }
            LogAktivitas::create([
                'username' => Auth::user()->username ?? 'system',
                'id_kota' => $dokumen->id_kota,
                'id_dokumen' => $dokumen->id_dokumen,
                'action' => $action,
                'waktu_aktivitas' => now(),
            ]);
        } catch (\Exception $e) {
            \Log::error('Gagal menyimpan log aktivitas: ' . $e->getMessage());
        }
    }
}
