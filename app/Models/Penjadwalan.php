<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penjadwalan extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'penjadwalan';
    protected $primaryKey = 'id_penjadwalan';

    protected $fillable = [
        'sesi',
        'agenda',
        'id_ruangan',
        'tanggal',
        'id_kota',
        'nip'
    ];
}
