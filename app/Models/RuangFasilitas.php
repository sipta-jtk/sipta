<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Awobaz\Compoships\Compoships; // Import Compoships

class RuangFasilitas extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'ruang_fasilitas';

    protected $primaryKey = ['id_ruangan', 'id_fasilitas'];
    public $incrementing = false;
    protected $fillable = [
        'id_ruangan',
        'id_fasilitas',
        'jumlah_fasilitas'
    ];

}
