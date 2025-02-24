<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Konfirmasi extends Model
{
    use HasFactory;

    protected $table = 'konfirmasi';

    protected $primaryKey = 'id_penjadwalan';

    public $timestamps = false;

    protected $fillable = [
        'id_penjadwalan',
        'nip',
        'status_konfirmasi',
    ];
}
