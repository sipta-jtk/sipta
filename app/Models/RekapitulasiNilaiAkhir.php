<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RekapitulasiNilaiAkhir extends Model
{
    protected $table = 'rekapitulasi_nilai_akhir';
    protected $primaryKey = 'id_rekap';

    public $timestamps = false;

    protected $fillable = [
        'nim',
        'nilai_uts',
        'nilai_uas',
        'nilai_lain_lain',
        'nilai_akhir'
    ];
}
