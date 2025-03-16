<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Konfirmasi extends Model
{
    use HasFactory;

    protected $table = 'konfirmasi';

    public $timestamps = false;
    
    public $incrementing = false; // Menonaktifkan auto-increment
    
    protected $fillable = [
        'id_penjadwalan',
        'nip',
        'status_konfirmasi',
    ];
}
