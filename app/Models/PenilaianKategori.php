<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenilaianKategori extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'penilaian_kategori';

    protected $fillable = [
        'nim',
        'id_kategori',
        'nilai_kategori'
    ];


}
