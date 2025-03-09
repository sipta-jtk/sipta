<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dosen extends Model
{
    protected $table = 'dosen';
    protected $primaryKey = 'nip';

    protected $fillable = [
        'maks_bimbingan_d4',
        'maks_bimbingan_d3',
        'id_kbk',
        'id_dosen',
        'kode_dosen',
        'status_dosen',
        'role_dosen'
    ];
}
