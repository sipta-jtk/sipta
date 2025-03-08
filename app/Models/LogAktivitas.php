<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogAktivitas extends Model
{
    use HasFactory;

    protected $table = 'log_aktivitas';
    protected $primaryKey = 'id_log_aktivitas';
    public $timestamps = false;
    protected $fillable = [
        'id_kota',
        'username',
        'id_dokumen',
        'action',
        'waktu_aktivitas'
    ];

}