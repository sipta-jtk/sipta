<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengajuanJadwalKota extends Model
{
    protected $table = 'pengajuan_jadwal_kota';

    public $timestamps = false;

    protected $fillable = [
        'status_mahasiswa',
        'status_dosen_pembimbing_1',
        'status_dosen_pembimbing_2',
        'status_dosen_penguji_1',
        'status_dosen_penguji_2',
        'status_koordinator_ta',
        'id_penjadwalan',
        'id_kota',
        'nip'
    ];

    public function penjadwalan()
    {
        return $this->belongsTo(Penjadwalan::class, 'id_penjadwalan', 'id_penjadwalan');
    }

    public function kota()
    {
        return $this->belongsTo(Kota::class, 'id_kota', 'id_kota');
    }

    public function dosen()
    {
        return $this->belongsTo(Dosen::class, 'nip', 'nip');
    }
}
