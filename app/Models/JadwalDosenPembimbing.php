<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JadwalDosenPembimbing extends Model
{
    protected $table = 'jadwal_dosen_pembimbing';
    protected $primaryKey = 'id_jadwal_pembimbing';
    protected $fillable = ['nip', 'hari', 'jam_mulai', 'jam_selesai'];
}
