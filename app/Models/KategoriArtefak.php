<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KategoriArtefak extends Model
{
    protected $table = 'kategori_artefak';

    protected $primaryKey = 'id_kategori_artefak';

    protected $fillable = [
        'jenis_artefak',
    ];

    // public function artefak()
    // {
    //     return $this->hasOne(Artefak::class, 'id_kategori_artefak', 'id_kategori_artefak');
    // }
}
