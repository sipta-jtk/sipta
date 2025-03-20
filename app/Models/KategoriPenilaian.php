<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KategoriPenilaian extends Model
{
    protected $table = 'kategori_penilaian';
    protected $primaryKey = 'id_kategori';

    public $timestamps = false;

    protected $fillable = [
        'id_fta',
        'nama_kategori',
        'kunci_penilaian'
    ];

    public function sumberNilai()
    {
        return $this->hasMany(SumberNilai::class, 'sumber', 'id_kategori');
    }
    
    public function formulirPenilaian()
    {
        return $this->belongsTo(FormPenilaian::class, 'id_fta', 'id_fta');
    }

    public function nilaiKategori()
    {
        return $this->hasMany(NilaiKategori::class, 'id_kategori', 'id_kategori');
    }
}

