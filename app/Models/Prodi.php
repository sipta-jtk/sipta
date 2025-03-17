<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Prodi extends Model
{
    protected $table = 'prodi';
    protected $primaryKey = 'id_prodi';

    public $timestamps = false;

    protected $fillable = [
        'nama_prodi',
        'maksimal_anggota_kota',
        'maksimal_mahasiswa_bimbingan'
    ];

    public function mahasiswa()
    {
        return $this->hasMany(Mahasiswa::class, 'id_prodi', 'id_prodi');
    }

    public function kaprodi()
    {
        return $this->hasOne(Kaprodi::class, 'id_prodi', 'id_prodi');
    }

    public function kuotaMembimbing()
    {
        return $this->hasMany(KuotaMembimbing::class, 'id_prodi', 'id_prodi');
    }

    public function formPenilaian()
    {
        return $this->hasMany(FormPenilaian::class, 'id_prodi', 'id_prodi');
    }
}