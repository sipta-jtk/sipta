<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NilaiKriteria extends Model
{
    protected $table = 'nilai_kriteria';

    protected $fillable = [
        'nim',
        'nip',
        'id_kriteria',
        'nilai_kriteria',
        'status_penilaian',
        'created_at',
        'updated_at'
    ];

    public function kriteriaPenilaian()
    {
        return $this->belongsTo(KriteriaPenilaian::class, 'id_kriteria', 'id_kriteria');
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
