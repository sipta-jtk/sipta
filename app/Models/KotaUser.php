<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KotaUser extends Model
{
    protected $table = 'kota_user';

    protected $fillable = [
        'id_kota',
        'username',
    ];

    public function kota()
    {
        return $this->belongsTo(Kota::class, 'id_kota', 'id_kota');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'username', 'username');
    }
}
