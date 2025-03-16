<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengajuanPembimbing extends Model
{
    protected $table = 'pengajuan_pembimbing';
    protected $primaryKey = 'id_pengajuan_pembimbing';
    
    protected $fillable = [
        'id_kota',
        'status_pengajuan',
        'created_at',
        'updated_at'
    ];

    public function prioritasPembimbing()
    {
        return $this->hasMany(PrioritasPembimbing::class, 'id_pengajuan', 'id_pengajuan_pembimbing');
    }

    public function kota()
    {
        return $this->belongsTo(Kota::class, 'id_kota', 'id_kota');
    }

    public function alokasiPembimbing()
    {
        return $this->hasMany(AlokasiPembimbing::class, 'id_pengajuan_pembimbing', 'id_pengajuan_pembimbing');
    }
}
