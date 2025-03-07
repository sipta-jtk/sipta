<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subkategori extends Model
{
    use HasFactory;

    protected $table = 'subkategori';

    protected $primaryKey = 'id_subkategori';
    public $timestamps = false;

    protected $fillable = [
        'nama_subkategori'
    ];
}