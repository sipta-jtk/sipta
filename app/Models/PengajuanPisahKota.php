<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengajuanPisahKota extends Model
{
    protected $table = 'pengajuan_pisah_kota';
    protected $primaryKey = 'id_pengajuan';
    
    public $timestamps = false;

    protected $fillable = [
        'nim',
        'id_kota'
    ];

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'nim', 'nim');
    }

    public function kota()
    {
        return $this->belongsTo(Kota::class, 'id_kota', 'id_kota');
    }
}
