<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gedung extends Model
{
    protected $table = 'gedung';
    public $timestamps = false;
    protected $primaryKey = 'kode_gedung';
    protected $fillable = ['nama_gedung'];
}
