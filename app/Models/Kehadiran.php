<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kehadiran extends Model
{
        protected $table = 'kehadiran';
        
        public $timestamps = false;

        protected $fillable = [
            'id_penjadwalan',
            'username',
            'status_hadir'
        ];

        public function penjadwalan()
        {
            return $this->belongsTo(Penjadwalan::class, 'id_penjadwalan', 'id_penjadwalan');
        }

        public function user()
        {
            return $this->belongsTo(User::class, 'username', 'username');
        }
}
