<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenilaianKategori extends Model
{
    use HasFactory;

    protected $table = 'penilaian_kategori';
    public $timestamps = false;

    protected $fillable = [
        'nim',
        'id_kategori',
        'nilai_kategori'
    ];

    
}