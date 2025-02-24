<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Label extends Model
{
    use HasFactory;

    protected $table = 'label'; // Nama tabel dalam database

    protected $primaryKey = 'id_label'; // Primary key tabel

    public $timestamps = false; // Karena tidak ada kolom created_at & updated_at

    protected $fillable = [
        'nama_label',
    ];
}
