<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JadwalDosenPembimbing extends Model
{
    protected $table = 'jadwal_dosen_pembimbing';
    protected $primaryKey = 'id_jadwal_dosbim';
    public $timestamps = false;

    protected $fillable = [
        'nip', 
        'hari', 
        'jam_mulai', 
        'jam_selesai'
    ];

    public function dosen()
    {
        return $this->belongsTo(Dosen::class, 'nip', 'nip');
    }
}
