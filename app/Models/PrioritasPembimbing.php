<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PrioritasPembimbing extends Model
{
    protected $table = 'prioritas_pembimbing';
    protected $primaryKey = 'id_prioritas_pembimbing';

    public $timestamps = false;
    
    protected $fillable = [
        'id_pengajuan',
        'nip',
        'urutan_prioritas'
    ];

    public function pengajuanPembimbing()
    {
        return $this->belongsTo(PengajuanPembimbing::class, 'id_pengajuan', 'id_pengajuan_pembimbing');
    }

    public function dosen()
    {
        return $this->belongsTo(Dosen::class, 'nip', 'nip');
    }
}
