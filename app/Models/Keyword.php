<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Keyword extends Model
{
    use HasFactory;

    protected $table = 'keyword';
    protected $primaryKey = 'id_keyword';
    
    protected $fillable = [
        'nama_keyword'
    ];

    // Relationship dengan dokumen (many-to-many)
    public function dokumen()
    {
        return $this->belongsToMany(Dokumen::class, 'dokumen_keyword', 'id_keyword', 'id_dokumen');
    }
}