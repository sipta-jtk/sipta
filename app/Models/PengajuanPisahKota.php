<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengajuanPisahKota extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'pengajuan_pisah_kota';

    protected $primaryKey = 'id_pengajuan';

    protected $fillable = [
        'nim',
        'id_kota'
    ];
}
