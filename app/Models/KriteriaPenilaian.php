<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KriteriaPenilaian extends Model
{
    protected $table = 'kriteria_penilaian'; 
    protected $primaryKey = 'id_kriteria'; 

    public $timestamps = false; 

    protected $fillable = [
        'kode_fta',
        'nama_kriteria',
        'bobot_kriteria',
    ];

    public function formPenilaian()
    {
        return $this->belongsTo(FormPenilaian::class, 'kode_fta', 'kode_fta');
    }

    public function nilaiKriteria()
    {
        return $this->hasMany(NilaiKriteria::class, 'id_kriteria', 'id_kriteria');
    }

    public function rubrik()
    {
        return $this->hasMany(Rubrik::class, 'id_kriteria', 'id_kriteria');
    }
}
