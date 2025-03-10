<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrioritasPembimbing extends Model
{
    use HasFactory;
    protected $table = 'prioritas_pembimbing';
    protected $primaryKey = 'id_prioritas_pembimbing';
    public $timestamps = false;
    protected $fillable = [
        'id_pengajuan',
        'nip',
        'urutan_prioritas'
    ];
}
