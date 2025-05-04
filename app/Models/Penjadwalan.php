<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Penjadwalan extends Model
{
    protected $table = 'penjadwalan';
    protected $primaryKey = 'id_penjadwalan';
    
    public $timestamps = false;

    protected $fillable = [
        'sesi',
        'agenda',
        'id_ruangan',
        'nama_ruangan',
        'tanggal',
        'id_kota',
        'start',
        'end'
    ];

    public function kota()
    {
        return $this->belongsTo(Kota::class, 'id_kota', 'id_kota');
    }

    public function kehadiran()
    {
        return $this->hasMany(Kehadiran::class, 'id_penjadwalan', 'id_penjadwalan');
    }

    public function pengajuanJadwalKota()
    {
        return $this->hasMany(PengajuanJadwalKota::class, 'id_penjadwalan', 'id_penjadwalan');
    }

    public function pembatalan()
    {
        return $this->hadMany(Pembatalan::class, 'id_penjadwalan', 'id_penjadwalan');
    }
}
