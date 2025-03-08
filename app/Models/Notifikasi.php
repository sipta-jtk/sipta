<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notifikasi extends Model
{
    use HasFactory;

    protected $table = 'notifikasi'; // Nama tabel

    protected $primaryKey = 'id_notifikasi'; // Primary key

    public $timestamps = false;

    protected $fillable = [
        'tipe_notifikasi',
        'judul',
        'isi_notifikasi',
        'sumber_notifikasi',
        'created_at'
    ];
}
