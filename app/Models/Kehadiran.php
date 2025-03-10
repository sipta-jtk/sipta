<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kehadiran extends Model
{
        protected $table = 'kehadiran';
        protected $primaryKey = 'id_penjadwalan';
        public $timestamps = false;
        protected $fillable = [
            'id_penjadwalan',
            'username',
            'status_hadir'
        ];
}
