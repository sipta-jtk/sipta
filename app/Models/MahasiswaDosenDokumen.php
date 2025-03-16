<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MahasiswaDosenDokumen extends Model
{
    protected $table = 'mahasiswa_dosen_dokumen';

    public $timestamps = false;

    protected $fillable = [
        'nip',
        'nim',
        'id_dokumen'
    ];

    public function dosen()
    {
        return $this->belongsTo(Dosen::class, 'nip', 'nip');
    }

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'nim', 'nim');
    }

    public function dokumen()
    {
        return $this->belongsTo(Dokumen::class, 'id
        _dokumen', 'id_dokumen');
    }
}
