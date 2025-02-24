<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Topik extends Model
{
    use HasFactory;

    protected $table = 'topik';
    protected $primaryKey = 'id_topik';

    protected $fillable = [
        'topik'
    ];
}
