<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notifikasi extends Model
{

    protected $table = 'notifikasi'; // Nama tabel

    protected $primaryKey = 'id_notifikasi'; // Primary key

    protected $fillable = [
        'tipe_notifikasi'
        'judul'
        'isi_notifikasi'
        'sumber_notifikasi'
        'created_at'
    ];
}
