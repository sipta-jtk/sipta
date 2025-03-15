<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rubrik extends Model
{
    protected $table = 'rubrik';
    protected $primaryKey = 'id_rubrik';

    public $timestamps = false;

    protected $fillable = [
        'id_kriteria',
        'nama_rubrik',
    ];   
    
    public function kriteriaPenilaian()
    {
        return $this->belongsTo(KriteriaPenilaian::class, 'id_kriteria', 'id_kriteria');
    }

    public function detailRubrik()
    {
        return $this->hasMany(DetailRubrik::class, 'id_rubrik', 'id_rubrik');
    }
}
