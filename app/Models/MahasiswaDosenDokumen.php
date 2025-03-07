<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MahasiswaDosenDokumen extends Model
{
    use HasFactory;
    public $timestamps = false;
    
    
    protected $table = 'mahasiswa_dosen_dokumen'; // Nama tabel

    protected $fillable = [
        'nip',
        'nim',
        'id_dokumen'
    ];
}
