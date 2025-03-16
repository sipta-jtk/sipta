<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mahasiswa extends Model
{
    use HasFactory;

    protected $table = 'mahasiswa'; // Nama tabel
 
    protected $primaryKey = 'nim'; // Primary key

    public $timestamps = false; // Tidak menggunakan created_at & update_at

    protected $fillable = [
        'nim',
        'tahun_masuk',
        'kelas',
        'id_prodi',
        'status_ta',
        'nilai_akhir_ta',
        'id_kota'
    ];
}
