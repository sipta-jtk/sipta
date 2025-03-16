<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotifikasiKirim extends Model
{
    protected $table = 'notifikasi_kirim'; 
    protected $primaryKey = 'id_kirim'; 

    public $timestamps = false;

    protected $fillable = [
        'id_notifikasi',
        'username',
        'kanal',
        'status',
        'waktu_kirim',
        'respon_log'
    ];

    public function notifikasi()
    {
        return $this->belongsTo(Notifikasi::class, 'id_notifikasi', 'id_notifikasi');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'username', 'username');
    }
}
