<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RubrikPenilaian extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'rubrik_penilaian';
    protected $primaryKey = 'id_rubrik';

    protected $fillable = [
        'id_kategori',
        'judul_rubrik',
        'detail_rubrik',
        'bobot_rubrik'
    ];        
}
