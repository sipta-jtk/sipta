<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengajuanPembimbing extends Model
{
    use HasFactory;

    protected $table = 'pengajuan_pembimbing';
    protected $primaryKey = 'id_pengajuan_pembimbing';
    public $timestamps = false;
    
    protected $fillable = [
        'id_kota',
        'status_pengajuan',
        'created_at'
    ];
}
