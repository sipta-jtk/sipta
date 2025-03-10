<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PeriodePengajuan extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'periode_pengajuan';
    protected $primaryKey = 'id_periode_pengajuan';

    protected $fillable = [
        'periode_mulai',
        'periode_akhir'
    ];
}
