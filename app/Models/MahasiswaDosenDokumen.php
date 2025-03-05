<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MahasiswaDosenDokumen extends Model
{
    use HasFactory;
    
    protected $table = 'MahasiswaDosenDokumen'; // Nama tabel

    protected $fillable = [
        'nip',
        'nim',
        'id_dokumen'
    ];
}
