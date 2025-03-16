<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ruangan extends Model
{
    use HasFactory;
    protected $table = 'ruangan';  
    protected $primaryKey = 'id_ruang';
    public $timestamps = false;
    protected $fillable = [
        
        'nama_ruangan',
        'kode_ruangan',
        'status_ruangan',
        'kode_gedung',
        'link_ruangan'
    ];

}
