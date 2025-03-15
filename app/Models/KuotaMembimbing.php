<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KuotaMembimbing extends Model
{
    protected $table = 'kuota_membimbing'; 

    public $timestamps = false; 

    protected $fillable = [
        'nip',
        'id_prodi',
        'jumlah',
    ];

    public function dosen()
    {
        return $this->belongsTo(Dosen::class, 'nip', 'nip');
    }

    public function prodi()
    {
        return $this->belongsTo(Prodi::class, 'id_prodi', 'id_prodi');
    }
}
