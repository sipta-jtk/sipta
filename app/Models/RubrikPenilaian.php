<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RubrikPenilaian extends Model
{
    use HasFactory;

    protected $table = 'rubrik_penilaian';
    protected $primaryKey = 'id_rubrik';
    public $timestamps = false;

    protected $fillable = [
        'id_kategori',
        'judul_rubrik',
        'detail_rubrik',
        'bobot_rubrik'
    ];        
}
