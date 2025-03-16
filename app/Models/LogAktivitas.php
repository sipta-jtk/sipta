<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LogAktivitas extends Model
{
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

    public function kota()
    {
        return $this->belongsTo(Kota::class, 'id_kota', 'id_kota');
    }

    public function dokumen()
    {
        return $this->belongsTo(Dokumen::class, 'id
        _dokumen', 'id_dokumen');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'username', 'username');
    }
}