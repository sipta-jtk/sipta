<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kota extends Model
{
    protected $table = 'kota'; 
    protected $primaryKey = 'id_kota'; 

    public $timestamps = false; 

    protected $fillable = [
        'judul_ta',
        'id_bidang',
        'nama_kota',
        'tahun_kota',
        'status_kota'
    ];

    public function penjadwalan()
    {
        return $this->hasMany(Penjadwalan::class, 'id_kota', 'id_kota');
    }

    public function mahasiswa()
    {
        return $this->hasMany(Mahasiswa::class, 'id_kota', 'id_kota');
    }

    public function pengajuanPisahKota()
    {
        return $this->hasMany(PengajuanPisahKota::class, 'id_kota', 'id_kota');
    }

    public function bidang()
    {
        return $this->belongsTo(Bidang::class, 'id_bidang', 'id_bidang');
    }

    public function pengajuanPembimbing()
    {
        return $this->hasMany(PengajuanPembimbing::class, 'id_kota', 'id_kota');
    }

    public function preferensiKota()
    {
        return $this->hasMany(PreferensiKota::class, 'id_kota', 'id_kota');
    }

    public function detailFeedback()
    {
        return $this->hasMany(DetailFeedback::class, 'id_kota', 'id_kota');
    }

    public function pengajuanJadwalKota()
    {
        return $this->hasMany(PengajuanJadwalKota::class, 'id_kota', 'id_kota');
    }

    public function dokumen()
    {
        return $this->hasMany(Dokumen::class, 'id_kota', 'id_kota');
    }

    public function logAktivitas()
    {
        return $this->hasMany(LogAktivitas::class, 'id_kota', 'id_kota');
    }
}
