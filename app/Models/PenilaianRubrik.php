<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenilaianRubrik extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'penilaian_rubrik';

    protected $fillable = [
        'nim',
        'nip',
        'id_rubrik',
        'nilai_rubrik',
        'detail_feedback'
    ];
}
