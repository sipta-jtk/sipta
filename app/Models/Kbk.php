<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kbk extends Model
{
    protected $table = 'kbk';
    protected $primaryKey = 'id_kbk';

    public $timestamps = false;
    
    protected $fillable = [
        'kbk'
    ];

    public function dosen()
    {
        return $this->hasMany(Dosen::class, 'id_kbk', 'id_kbk');
    }
}