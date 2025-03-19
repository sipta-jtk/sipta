<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pembatalan extends Model
{
    protected $table = 'pembatalan';
    protected $primaryKey = 'id_pembatalan';

    public $timestamps = false;

    protected $fillable = [
        'id_pembatalan',
        'id_penjadwalan',
        'alasan_pembatalan',
        'nip',
        'status_pembatalan'
    ];

    public function penjadwalan()
    {
        return $this->belongsTo(Penjadwalan::class, 'id_penjadwalan', 'id_penjadwalan');
    }

    public function dosen()
    {
        return $this->belongsTo(Dosen::class, 'nip', 'nip');
    }

}
