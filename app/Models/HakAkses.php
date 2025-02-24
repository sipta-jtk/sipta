<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HakAkses extends Model
{
    protected $table = 'hak_akses';
    protected $primaryKey = 'id_hak_akses';
    protected $fillable = ['id_dokumen','id_kota','username','view','download','edit','delete'];
}
