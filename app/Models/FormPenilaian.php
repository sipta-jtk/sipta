<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FormPenilaian extends Model
{
    protected $table = 'form_penilaian';
    protected $primaryKey = 'id_fta';

    protected $fillable = [
        'kode_fta',
        'nama_fta', 
        'id_prodi', 
        'jenis_form', 
        'tanggal_tenggat_pengisian', 
        'waktu_tenggat_pengisian',
        'created_at', 
        'updated_at'
    ];

    public function prodi()
    {
        return $this->belongsTo(Prodi::class, 'id_prodi', 'id_prodi');
    }

    public function kategoriPenilaian()
    {
        return $this->hasMany(KategoriPenilaian::class, 'id_fta', 'id_fta');
    }

    public function kriteriaPenilaian()
    {
        return $this->hasMany(KriteriaPenilaian::class, 'id_fta', 'id_fta');
    }

    public function aspekFeedback()
    {
        return $this->hasMany(AspekFeedback::class, 'id_fta', 'id_fta');
    }
}
