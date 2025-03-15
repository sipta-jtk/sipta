<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PreferensiKota extends Model
{
    protected $table = 'preferensi_kota';

    public $timestamps = false;

    protected $fillable = [
        'nip',
        'id_kota'
    ];

    public function dosen()
    {
        return $this->belongsTo(Dosen::class, 'nip', 'nip');
    }

    public function kota()
    {
        return $this->belongsTo(Kota::class, 'id_kota', 'id_kota');
    }
}
