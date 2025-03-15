<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// Model untuk Prodi
class Prodi extends Model
{
    use HasFactory;
    protected $table = 'prodi';
    protected $primaryKey = 'id_prodi';
    public $timestamps = false;

    protected $fillable = [
        'nama_prodi',
        'kode_prodi',
        'maksimal_anggota_kota'
    ];
}