<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NilaiKategori extends Model
{
    protected $table = 'nilai_kategori';

    public $timestamps = false;

    protected $fillable = [
        'nim',
        'nip',
        'id_kategori',
        'nilai',
    ];

    public function kategoriPenilaian()
    {
        return $this->belongsTo(KategoriPenilaian::class, 'id_kategori', 'id_kategori');
    }

    public function dosen()
    {
        return $this->belongsTo(Dosen::class, 'nip', 'nip');
    }

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'nim', 'nim');
    }
}
