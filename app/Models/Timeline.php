<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Timeline extends Model
{
    protected $table = 'timeline';

    protected $primaryKey = 'id_timeline';

    protected $fillable = [
        'nama_kegiatan',
        'tanggal_mulai',
        'tanggal_selesai',
        'deskripsi',
    ];

    public function artefak()
    {
        return $this->hasMany(TimelineArtefak::class, 'id_timeline', 'id_timeline');
    }
}
