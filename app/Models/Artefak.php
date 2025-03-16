<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Artefak extends Model
{
    protected $table = 'artefak';

    protected $primaryKey = 'id_artefak';

    protected $fillable = [
        'nama_artefak',
        'deskripsi',
        'kategori_artefak',
        'tenggat_waktu',
    ];
}
