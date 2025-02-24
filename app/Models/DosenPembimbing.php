<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DosenPembimbing extends Model
{
    protected $table = 'dosen_pembimbing';
    protected $primaryKey = 'nip';

    protected $fillable = [
        'kbk',
        'maksimal_d4',
        'maksimal_d3'
    ];
}
