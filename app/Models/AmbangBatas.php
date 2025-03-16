<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AmbangBatas extends Model
{    
    protected $table = 'ambang_batas';
    protected $primaryKey = 'id_ambang_batas';
        
    protected $fillable = [
        'ambang_batas',
        'status_ambang_batas',
        'nip',
        'created_at',
        'updated_at'
    ];

    public function dokumen()
    {
        return $this->hasMany(Dokumen::class, 'id_ambang_batas', 'id_ambang_batas');
    }

    public function dosen()
    {
        return $this->belongsTo(Dosen::class, 'nip', 'nip');
    }
}
