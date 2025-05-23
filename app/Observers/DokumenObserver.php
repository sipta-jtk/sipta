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

    public function deleted(Dokumen $dokumen)
    {
        $this->log('Menghapus dokumen', $dokumen);
    }

    protected function log(string $action, Dokumen $dokumen)
    {
        LogAktivitas::create([
            'username' => Auth::user()->username ?? 'system', // pastikan user login
            'id_kota' => $dokumen->id_kota,
            'id_dokumen' => $dokumen->id_dokumen,
            'action' => $action,
            'waktu_aktivitas' => now(),
        ]);
    }
}
