<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KategoriPenilaian extends Model
{
    protected $table = 'kategori_penilaian';
    protected $primaryKey = 'id_kategori';
    public $timestamps = false;
    protected $fillable = [
        'id_kategori',
        'id_form_penilaian',
        'nama_kategori',
        'bobot_kategori'
    ];
}
