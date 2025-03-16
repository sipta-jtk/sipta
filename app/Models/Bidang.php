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

    public function ketertarikanBidang()
    {
        return $this->hasMany(KetertarikanBidang::class, 'id_bidang', 'id_bidang');
    }

    public function kota()
    {
        return $this->hasMany(Kota::class, 'id_bidang', 'id_bidang');
    }
}
