<?php

namespace App\Modules\PengajuanAlokasiPembimbing\Components\DaftarPengajuanDosbing;

use Illuminate\View\Component;
use App\Models\PreferensiKota;
use App\Models\KuotaMembimbing;
use Illuminate\Support\Facades\Auth;

class AlertInfoKuota extends Component
{
    public $message;
    public $type;

    public function __construct()
    {
        $user = Auth::user();
        $dosen = $user->dosen ?? null;

        if ($dosen) {
            $selectedKotaIds = PreferensiKota::where('nip', $dosen->nip)->pluck('id_kota')->toArray();
            $kuotaMembimbing = KuotaMembimbing::where('nip', $dosen->nip)->first();
            $sisaKuota = $kuotaMembimbing ? $kuotaMembimbing->jumlah - count($selectedKotaIds) : 0;

            $this->message = 'Kota yang sudah dipilih: ' . implode(', ', $selectedKotaIds) . '<br>' .
                             'Sisa kuota membimbing: ' . $sisaKuota;
            $this->type = $sisaKuota > 0 ? 'success' : 'warning';
        } else {
            $this->message = 'Data dosen tidak ditemukan.';
            $this->type = 'error';
        }
    }

    public function render()
    {
        return view('DaftarPengajuanDosbing.Components.AlertInfoKuota');
    }
}
