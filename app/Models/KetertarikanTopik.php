<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KetertarikanTopik extends Model
{
    protected $table = 'ketertarikan_topik';
    protected $primaryKey = 'id_ketertarikan_topik';

    protected $fillable = [
        'id_ketertarikan_topik',
        'nip',
        'id_topik'
    ];
}
