<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MahasiswaDosenDokumen extends Model
{
    use HasFactory;
    
    protected $table = 'mahasiswa_dosen_dokumen';

    public $timestamps = false;

    protected $fillable = [
        'nip',
        'nim',
        'id_dokumen'
    ];
}
