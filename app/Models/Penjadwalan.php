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
        'tanggal',
        'id_kota',
        'nip',
        'start',
        'end'
    ];

    public function kota()
    {
        return $this->belongsTo(Kota::class, 'id_kota', 'id_kota');
    }

    public function dosen()
    {
        return $this->belongsTo(Dosen::class, 'nip', 'nip');
    }

    public function kehadiran()
    {
        return $this->hasMany(Kehadiran::class, 'id_penjadwalan', 'id_penjadwalan');
    }

    public function pengajuanJadwalKota()
    {
        return $this->hasMany(PengajuanJadwalKota::class, 'id_penjadwalan', 'id_penjadwalan');
    }
}
