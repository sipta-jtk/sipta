<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Kaprodi extends Model
{
    protected $table = 'kaprodi';
    protected $fillable = [
        'nip',
        'id_prodi'
    ];
}
