<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FormPenilaian extends Model
{
    protected $table = 'form_penilaian';
    protected $primaryKey = 'kode_fta';

    protected $fillable = [
        'nama_fta', 
        'id_prodi', 
        'jenis_form', 
        'tanggal_tenggat_pengisian', 
        'created_at', 
        'updated_at'
    ];

    public function prodi()
    {
        return $this->belongsTo(Prodi::class, 'id_prodi', 'id_prodi');
    }

    public function kategoriPenilaian()
    {
        return $this->hasMany(KategoriPenilaian::class, 'kode_fta', 'kode_fta');
    }

    public function kriteriaPenilaian()
    {
        return $this->hasMany(KriteriaPenilaian::class, 'kode_fta', 'kode_fta');
    }

    public function aspekFeedback()
    {
        return $this->hasMany(AspekFeedback::class, 'kode_fta', 'kode_fta');
    }
}
