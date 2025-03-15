<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SumberNilai extends Model
{
    protected $table = 'sumber_nilai';
    protected $primaryKey = 'id_sumber';

    public $timestamps = false;

    protected $fillable = [
        'sumber'
    ];

    public function komponenNilaiAkhir()
    {
        return $this->belongsTo(KomponenNilaiAkhir::class, 'id_komponen', 'id_komponen');
    }

    public function kategoriPenilaian()
    {
        return $this->belongsTo(KategoriPenilaian::class, 'sumber', 'id_kategori');
    }
}
