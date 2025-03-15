<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mahasiswa extends Model
{
    protected $table = 'mahasiswa'; 
    protected $primaryKey = 'nim';

    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false; 

    protected $fillable = [
        'nim',
        'tahun_masuk',
        'kelas',
        'id_prodi',
        'status_ta',
        'id_kota'
    ];

    public function prodi()
    {
        return $this->belongsTo(Prodi::class, 'id_prodi', 'id_prodi');
    }

    public function kota()
    {
        return $this->belongsTo(Kota::class, 'id_kota', 'id_kota');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'nim', 'username');
    }

    public function pengajuanPisahKota()
    {
        return $this->hasMany(PengajuanPisahKota::class, 'nim', 'nim');
    }

    public function nilaiKategori()
    {
        return $this->hasMany(NilaiKategori::class, 'nim', 'nim');
    }

    public function nilaiKriteria()
    {
        return $this->hasMany(NilaiKriteria::class, 'nim', 'nim');
    }

    public function mahasiswaDosenDokumen()
    {
        return $this->hasMany(MahasiswaDosenDokumen::class, 'nim', 'nim');
    }
}
