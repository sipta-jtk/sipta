<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kota extends Model
{
    use HasFactory;

    protected $table = 'kota'; // Nama tabel dalam database

    protected $primaryKey = 'id_kota'; // Kolom primary key

    public $timestamps = false; // Karena tidak ada kolom created_at & updated_at

    protected $fillable = [
        'judul_ta',
        'id_bidang',
        'nama_kota',
        'tahun_kota',
        'status_kota'
    ];
}
