<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PreferensiNotifikasi extends Model
{
    use HasFactory;

    protected $table = 'preferensi_notifikasi';
    protected $primaryKey = 'id_preferensi';
    public $timestamps = false;

    protected $fillable = [
        'username',
        'tipe_notifikasi',
        'whatsapp',
        'in_app',
        'email'
    ];
}