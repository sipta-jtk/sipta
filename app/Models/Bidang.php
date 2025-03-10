<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bidang extends Model
{
    protected $table = 'bidang';
    protected $primaryKey = 'id_bidang';

    public $timestamps = false;

    protected $fillable = [
        'bidang'
    ];
}
