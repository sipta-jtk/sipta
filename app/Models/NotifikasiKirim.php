<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotifikasiKirim extends Model
{
    use HasFactory;

    protected $table = 'notifikasi_kirim'; // Nama tabel

    protected $primaryKey = 'id_kirim'; // Primary key

    public $timestamps = false;

    protected $fillable = [
        'id_notifikasi',
        'username',
        'kanal',
        'status',
        'waktu_kirim',
        'respon_log'
    ];
}
