<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NilaiRubrik extends Model
{
    protected $table = 'nilai_rubrik';

    protected $fillable = [
        'nim',
        'nip',
        'id_rubrik',
        'nilai_rubrik',
        'status_penilaian_dosen',
        'created_at',
        'updated_at'
    ];

    public function rubrik()
    {
        return $this->belongsTo(Rubrik::class, 'id_rubrik', 'id_rubrik');
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
