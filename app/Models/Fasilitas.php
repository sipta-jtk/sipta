<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fasilitas extends Model
{
    protected $table = 'fasilitas';
    public $timestamps = false;
    protected $primaryKey = 'id_fasilitas';
    protected $fillable = ['nama_fasilitas', 'jumlah_total_fasilitas'];
}
