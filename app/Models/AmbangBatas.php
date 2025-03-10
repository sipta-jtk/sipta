<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AmbangBatas extends Model
{    
    protected $table = 'ambang_batas';
    protected $primaryKey = 'id_ambang_batas';
    
    public $timestamps = false;
    
    protected $fillable = [
        'ambang_batas'
    ];
}
