<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KotaArtefak extends Model
{
    protected $table = 'kota_artefak';

    protected $primaryKey = 'id_kota_artefak';

    protected $fillable = [
        'id_kota',
        'id_artefak',
        'file_pengumpulan',
        'waktu_pengumpulan',
    ];

    public function kota()
    {
        return $this->belongsTo(Kota::class, 'id_kota');
    }

    public function artefak()
    {
        return $this->belongsTo(Artefak::class, 'id_artefak');
    }
}
