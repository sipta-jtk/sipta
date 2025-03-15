<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KomponenNilaiAkhir extends Model
{
    protected $table = 'komponen_nilai_akhir';
    protected $primaryKey = 'id_komponen';
    
    protected $fillable = [
        'nama_komponen',
        'bobot_komponen',
        'created_at',
        'updated_at'
    ];

    public function sumberNilai()
    {
        return $this->hasMany(SumberNilai::class, 'id_komponen', 'id_komponen');
    }
}