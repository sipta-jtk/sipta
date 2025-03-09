<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AlokasiPembimbing extends Model
{
    protected $table = 'alokasi_pembimbing';
    protected $primaryKey = 'id_alokasi_pembimbing';

    protected $fillable = [
        'id_pengajuan_pembimbing',
        'nip',
        'urutan_prioritas_terpilih'
    ];
}
