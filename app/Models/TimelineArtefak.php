<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TimelineArtefak extends Model
{
    protected $table = 'timeline_artefak';

    protected $fillable = [
        'id_timeline',
        'id_kategori_artefak',
    ];

    public function timeline()
    {
        return $this->belongsTo(Timeline::class, 'id_timeline');
    }

    public function kategoriArtefak()
    {
        return $this->belongsTo(KategoriArtefak::class, 'id_kategori_artefak');
    }
}
