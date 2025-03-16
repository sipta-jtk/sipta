<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KategoriPenilaian extends Model
{
    protected $table = 'kategori_penilaian';
    protected $primaryKey = 'id_kategori';

    public $timestamps = false;

    protected $fillable = [
        'kode_fta',
        'nama_kategori',
    ];

    public function sumberNilai()
    {
        return $this->hasMany(SumberNilai::class, 'sumber', 'id_kategori');
    }

    public function kategoriPenilaian()
    {
        return $this->belongsTo(KategoriPenilaian::class, 'kode_fta', 'kode_fta');
    }

    public function nilaiKategori()
    {
        return $this->hasMany(NilaiKategori::class, 'id_kategori', 'id_kategori');
    }
}

