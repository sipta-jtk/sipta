<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VersiDokumen extends Model
{
    use HasFactory;
    
    protected $table = 'versi_dokumen';
    protected $primaryKey = 'id_versi_dokumen';

    protected $fillable = [
        'id_dokumen',
        'versi',
        'url_file',
        'uploaded_at'
    ];
}
